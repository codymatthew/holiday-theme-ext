<?php
/**
 *
 * Seasonal Images. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, DFIR Forum
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dfirforum\seasonalimages\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener for seasonal images
 */
class main_listener implements EventSubscriberInterface
{
    /** @var \dfirforum\seasonalimages\core\seasonal_images */
    protected $seasonal_images;

    /** @var \phpbb\template\template */
    protected $template;

    /** @var \phpbb\path_helper */
    protected $path_helper;

    /** @var string */
    protected $ext_path;

    /** @var string */
    protected $php_ext;

    /**
     * Constructor
     *
     * @param \dfirforum\seasonalimages\core\seasonal_images $seasonal_images
     * @param \phpbb\template\template $template
     * @param \phpbb\path_helper $path_helper
     * @param string $ext_path
     * @param string $php_ext
     */
    public function __construct(
        \dfirforum\seasonalimages\core\seasonal_images $seasonal_images,
        \phpbb\template\template $template,
        \phpbb\path_helper $path_helper,
        $ext_path,
        $php_ext
    )
    {
        $this->seasonal_images = $seasonal_images;
        $this->template = $template;
        $this->path_helper = $path_helper;
        $this->ext_path = $ext_path;
        $this->php_ext = $php_ext;
    }

    /**
     * Assign functions defined in this class to event listeners in the core
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'core.page_header' => 'load_seasonal_image',
        ];
    }

    /**
     * Load and display seasonal image if one is active for current date
     *
     * @param \phpbb\event\data $event Event object
     */
    public function load_seasonal_image($event)
    {
        // Get active seasonal image for current date
        $active_image = $this->seasonal_images->get_active_image();

        if ($active_image !== false)
        {
            // Build the image path
            $image_url = $this->path_helper->update_web_root_path(
                $this->ext_path . 'styles/all/theme/images/' . $active_image['image_path']
            );

            // Assign template variables
            $this->template->assign_vars([
                'SEASONAL_IMAGE_ACTIVE' => true,
                'SEASONAL_IMAGE_URL' => $image_url,
                'SEASONAL_IMAGE_POSITION' => $active_image['position'],
                'SEASONAL_IMAGE_DESCRIPTION' => $active_image['description'],
            ]);
        }
        else
        {
            // No active image
            $this->template->assign_vars([
                'SEASONAL_IMAGE_ACTIVE' => false,
            ]);
        }
    }
}
