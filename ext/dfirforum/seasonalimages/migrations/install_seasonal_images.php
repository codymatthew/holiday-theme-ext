<?php
/**
 *
 * Seasonal Images. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, DFIR Forum
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dfirforum\seasonalimages\migrations;

/**
 * Migration to create the seasonal_images table and add initial data
 */
class install_seasonal_images extends \phpbb\db\migration\migration
{
    /**
     * Check if the migration is installed
     *
     * @return bool True if this migration is already installed
     */
    public function effectively_installed()
    {
        return $this->db_tools->sql_table_exists($this->table_prefix . 'seasonal_images');
    }

    /**
     * Assign migration file dependencies for this migration
     *
     * @return array Array of migration files
     */
    public static function depends_on()
    {
        return ['\phpbb\db\migration\data\v33x\v330'];
    }

    /**
     * Update database schema
     *
     * @return array Array of schema changes
     */
    public function update_schema()
    {
        return [
            'add_tables' => [
                $this->table_prefix . 'seasonal_images' => [
                    'COLUMNS' => [
                        'id' => ['UINT', null, 'auto_increment'],
                        'start_month' => ['TINT:2', 1],
                        'start_day' => ['TINT:2', 1],
                        'end_month' => ['TINT:2', 1],
                        'end_day' => ['TINT:2', 1],
                        'image_path' => ['VCHAR:255', ''],
                        'enabled' => ['BOOL', 1],
                        'position' => ['VCHAR:20', 'top-right'],
                        'priority' => ['UINT', 0],
                        'description' => ['VCHAR:255', ''],
                    ],
                    'PRIMARY_KEY' => 'id',
                    'KEYS' => [
                        'enabled' => ['INDEX', 'enabled'],
                        'priority' => ['INDEX', 'priority'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Revert database schema changes
     *
     * @return array Array of schema changes
     */
    public function revert_schema()
    {
        return [
            'drop_tables' => [
                $this->table_prefix . 'seasonal_images',
            ],
        ];
    }

    /**
     * Add or update data in the database
     *
     * @return array Array of data update instructions
     */
    public function update_data()
    {
        return [
            // Add ACP module
            ['module.add', [
                'acp',
                'ACP_CAT_DOT_MODS',
                'ACP_SEASONAL_IMAGES_TITLE'
            ]],
            ['module.add', [
                'acp',
                'ACP_SEASONAL_IMAGES_TITLE',
                [
                    'module_basename' => '\dfirforum\seasonalimages\acp\main_module',
                    'modes' => ['settings'],
                ],
            ]],

            // Add sample Christmas configuration
            ['custom', [[$this, 'add_sample_data']]],
        ];
    }

    /**
     * Add sample seasonal image data
     */
    public function add_sample_data()
    {
        // Insert sample Christmas hat configuration
        $sql = 'INSERT INTO ' . $this->table_prefix . 'seasonal_images ' .
            $this->db->sql_build_array('INSERT', [
                'start_month' => 12,
                'start_day' => 20,
                'end_month' => 12,
                'end_day' => 26,
                'image_path' => 'christmas_hat.png',
                'enabled' => 1,
                'position' => 'top-right',
                'priority' => 1,
                'description' => 'Christmas Hat',
            ]);
        $this->db->sql_query($sql);
    }
}
