<?php
/**
 *
 * Seasonal Images. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, DFIR Forum
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dfirforum\seasonalimages\acp;

/**
 * ACP module for Seasonal Images
 */
class main_module
{
    public $u_action;
    public $tpl_name;
    public $page_title;

    /**
     * Main ACP module
     *
     * @param int $id Module ID
     * @param string $mode Module mode
     */
    public function main($id, $mode)
    {
        global $phpbb_container, $user, $template, $request;

        // Load language file
        $user->add_lang_ext('dfirforum/seasonalimages', 'common');

        // Get services
        $seasonal_images = $phpbb_container->get('dfirforum.seasonalimages.core.seasonal_images');

        // Set page title and template
        $this->tpl_name = 'acp_seasonal_images_body';
        $this->page_title = $user->lang('ACP_SEASONAL_IMAGES_TITLE');

        // Form key for security
        add_form_key('dfirforum_seasonalimages');

        // Handle actions
        $action = $request->variable('action', '');
        $id = $request->variable('id', 0);

        switch ($action)
        {
            case 'add':
                $this->add_edit_image($seasonal_images, 0);
                return;
            case 'edit':
                $this->add_edit_image($seasonal_images, $id);
                return;
            case 'delete':
                if (confirm_box(true))
                {
                    $seasonal_images->delete_image($id);
                    trigger_error($user->lang('ACP_SEASONAL_IMAGES_DELETED') . adm_back_link($this->u_action));
                }
                else
                {
                    confirm_box(false, $user->lang('ACP_SEASONAL_IMAGES_DELETE_CONFIRM'), build_hidden_fields([
                        'id' => $id,
                        'action' => 'delete',
                    ]));
                }
                break;
            case 'toggle':
                $seasonal_images->toggle_enabled($id);
                redirect($this->u_action);
                break;
            case 'save':
                if (!check_form_key('dfirforum_seasonalimages'))
                {
                    trigger_error($user->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
                }
                $this->save_image($seasonal_images, $id);
                return;
        }

        // Get all seasonal images
        $images = $seasonal_images->get_all_images();

        // Assign template variables
        foreach ($images as $image)
        {
            $template->assign_block_vars('images', [
                'ID' => $image['id'],
                'DESCRIPTION' => $image['description'],
                'START_DATE' => sprintf('%02d-%02d', $image['start_month'], $image['start_day']),
                'END_DATE' => sprintf('%02d-%02d', $image['end_month'], $image['end_day']),
                'IMAGE_PATH' => $image['image_path'],
                'POSITION' => $image['position'],
                'PRIORITY' => $image['priority'],
                'ENABLED' => $image['enabled'],
                'U_EDIT' => $this->u_action . '&amp;action=edit&amp;id=' . $image['id'],
                'U_DELETE' => $this->u_action . '&amp;action=delete&amp;id=' . $image['id'],
                'U_TOGGLE' => $this->u_action . '&amp;action=toggle&amp;id=' . $image['id'],
            ]);
        }

        $template->assign_vars([
            'U_ACTION' => $this->u_action,
            'U_ADD' => $this->u_action . '&amp;action=add',
        ]);
    }

    /**
     * Display add/edit form
     *
     * @param \dfirforum\seasonalimages\core\seasonal_images $seasonal_images
     * @param int $id Image ID (0 for new)
     */
    protected function add_edit_image($seasonal_images, $id)
    {
        global $user, $template;

        $this->tpl_name = 'acp_seasonal_images_edit';
        $this->page_title = $user->lang($id ? 'ACP_SEASONAL_IMAGES_EDIT' : 'ACP_SEASONAL_IMAGES_ADD');

        // Get image data if editing
        $image = $id ? $seasonal_images->get_image($id) : [
            'start_month' => 1,
            'start_day' => 1,
            'end_month' => 1,
            'end_day' => 1,
            'image_path' => '',
            'enabled' => 1,
            'position' => 'top-right',
            'priority' => 0,
            'description' => '',
        ];

        if (!$image && $id)
        {
            trigger_error($user->lang('ACP_SEASONAL_IMAGES_NOT_FOUND') . adm_back_link($this->u_action), E_USER_WARNING);
        }

        // Position options
        $positions = ['top-left', 'top-right', 'top-center', 'bottom-left', 'bottom-right', 'bottom-center', 'center'];
        foreach ($positions as $position)
        {
            $template->assign_block_vars('positions', [
                'VALUE' => $position,
                'LABEL' => $user->lang('ACP_SEASONAL_IMAGES_POSITION_' . strtoupper(str_replace('-', '_', $position))),
                'SELECTED' => $position === $image['position'],
            ]);
        }

        // Month options
        for ($i = 1; $i <= 12; $i++)
        {
            $template->assign_block_vars('start_months', [
                'VALUE' => $i,
                'LABEL' => $user->lang('datetime', mktime(0, 0, 0, $i, 1, 2000), 'F'),
                'SELECTED' => $i == $image['start_month'],
            ]);
            $template->assign_block_vars('end_months', [
                'VALUE' => $i,
                'LABEL' => $user->lang('datetime', mktime(0, 0, 0, $i, 1, 2000), 'F'),
                'SELECTED' => $i == $image['end_month'],
            ]);
        }

        $template->assign_vars([
            'ID' => $id,
            'START_MONTH' => $image['start_month'],
            'START_DAY' => $image['start_day'],
            'END_MONTH' => $image['end_month'],
            'END_DAY' => $image['end_day'],
            'IMAGE_PATH' => $image['image_path'],
            'ENABLED' => $image['enabled'],
            'POSITION' => $image['position'],
            'PRIORITY' => $image['priority'],
            'DESCRIPTION' => $image['description'],
            'U_ACTION' => $this->u_action . '&amp;action=save&amp;id=' . $id,
            'U_BACK' => $this->u_action,
        ]);
    }

    /**
     * Save image data
     *
     * @param \dfirforum\seasonalimages\core\seasonal_images $seasonal_images
     * @param int $id Image ID (0 for new)
     */
    protected function save_image($seasonal_images, $id)
    {
        global $request, $user;

        // Get form data
        $data = [
            'start_month' => $request->variable('start_month', 1),
            'start_day' => $request->variable('start_day', 1),
            'end_month' => $request->variable('end_month', 1),
            'end_day' => $request->variable('end_day', 1),
            'image_path' => $request->variable('image_path', ''),
            'enabled' => $request->variable('enabled', 1),
            'position' => $request->variable('position', 'top-right'),
            'priority' => $request->variable('priority', 0),
            'description' => $request->variable('description', '', true),
        ];

        // Validate dates
        if (!$seasonal_images->validate_date($data['start_month'], $data['start_day']))
        {
            trigger_error($user->lang('ACP_SEASONAL_IMAGES_INVALID_START_DATE') . adm_back_link($this->u_action), E_USER_WARNING);
        }

        if (!$seasonal_images->validate_date($data['end_month'], $data['end_day']))
        {
            trigger_error($user->lang('ACP_SEASONAL_IMAGES_INVALID_END_DATE') . adm_back_link($this->u_action), E_USER_WARNING);
        }

        if (empty($data['image_path']))
        {
            trigger_error($user->lang('ACP_SEASONAL_IMAGES_INVALID_IMAGE_PATH') . adm_back_link($this->u_action), E_USER_WARNING);
        }

        // Save or update
        if ($id)
        {
            $seasonal_images->update_image($id, $data);
            $message = 'ACP_SEASONAL_IMAGES_UPDATED';
        }
        else
        {
            $seasonal_images->add_image($data);
            $message = 'ACP_SEASONAL_IMAGES_ADDED';
        }

        trigger_error($user->lang($message) . adm_back_link($this->u_action));
    }
}
