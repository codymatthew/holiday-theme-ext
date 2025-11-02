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
 * ACP module info for Seasonal Images
 */
class main_info
{
    public function module()
    {
        return [
            'filename' => '\dfirforum\seasonalimages\acp\main_module',
            'title' => 'ACP_SEASONAL_IMAGES_TITLE',
            'modes' => [
                'settings' => [
                    'title' => 'ACP_SEASONAL_IMAGES_SETTINGS',
                    'auth' => 'ext_dfirforum/seasonalimages && acl_a_board',
                    'cat' => ['ACP_SEASONAL_IMAGES_TITLE'],
                ],
            ],
        ];
    }
}
