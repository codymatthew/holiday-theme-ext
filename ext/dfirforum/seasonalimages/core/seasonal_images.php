<?php
/**
 *
 * Seasonal Images. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, DFIR Forum
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dfirforum\seasonalimages\core;

/**
 * Service class for managing seasonal images
 */
class seasonal_images
{
    /** @var \phpbb\db\driver\driver_interface */
    protected $db;

    /** @var \phpbb\cache\driver\driver_interface */
    protected $cache;

    /** @var \phpbb\user */
    protected $user;

    /** @var string */
    protected $seasonal_images_table;

    /** @var string */
    protected $ext_path;

    /**
     * Constructor
     *
     * @param \phpbb\db\driver\driver_interface $db
     * @param \phpbb\cache\driver\driver_interface $cache
     * @param \phpbb\user $user
     * @param string $seasonal_images_table
     * @param string $ext_path
     */
    public function __construct(
        \phpbb\db\driver\driver_interface $db,
        \phpbb\cache\driver\driver_interface $cache,
        \phpbb\user $user,
        $seasonal_images_table,
        $ext_path
    )
    {
        $this->db = $db;
        $this->cache = $cache;
        $this->user = $user;
        $this->seasonal_images_table = $seasonal_images_table;
        $this->ext_path = $ext_path;
    }

    /**
     * Get the active seasonal image for the current date
     *
     * @return array|false Array with image data or false if no active image
     */
    public function get_active_image()
    {
        // Try to get from cache first
        $active_image = $this->cache->get('_seasonal_images_active');

        if ($active_image === false)
        {
            // Get current date in user's timezone
            $timestamp = time();
            $month = (int) $this->user->format_date($timestamp, 'n');
            $day = (int) $this->user->format_date($timestamp, 'j');

            // Query for active images
            $sql = 'SELECT *
                FROM ' . $this->seasonal_images_table . '
                WHERE enabled = 1
                ORDER BY priority DESC';
            $result = $this->db->sql_query($sql);

            $active_image = false;
            while ($row = $this->db->sql_fetchrow($result))
            {
                if ($this->is_date_in_range($month, $day, $row))
                {
                    $active_image = $row;
                    break;
                }
            }
            $this->db->sql_freeresult($result);

            // Cache for 1 hour
            $this->cache->put('_seasonal_images_active', $active_image, 3600);
        }

        return $active_image;
    }

    /**
     * Check if a date falls within a configured range
     * Handles year-boundary ranges (e.g., Dec 25 - Jan 5)
     *
     * @param int $month Current month (1-12)
     * @param int $day Current day (1-31)
     * @param array $range Range data with start_month, start_day, end_month, end_day
     * @return bool True if date is in range
     */
    protected function is_date_in_range($month, $day, $range)
    {
        $start_month = (int) $range['start_month'];
        $start_day = (int) $range['start_day'];
        $end_month = (int) $range['end_month'];
        $end_day = (int) $range['end_day'];

        // Convert dates to comparable format (month * 100 + day)
        $current = $month * 100 + $day;
        $start = $start_month * 100 + $start_day;
        $end = $end_month * 100 + $end_day;

        // Check if range crosses year boundary
        if ($start <= $end)
        {
            // Normal range (e.g., March 1 - March 31)
            return $current >= $start && $current <= $end;
        }
        else
        {
            // Year-crossing range (e.g., Dec 20 - Jan 5)
            return $current >= $start || $current <= $end;
        }
    }

    /**
     * Get all seasonal images
     *
     * @return array Array of all seasonal image configurations
     */
    public function get_all_images()
    {
        $sql = 'SELECT *
            FROM ' . $this->seasonal_images_table . '
            ORDER BY priority DESC, start_month ASC, start_day ASC';
        $result = $this->db->sql_query($sql);

        $images = [];
        while ($row = $this->db->sql_fetchrow($result))
        {
            $images[] = $row;
        }
        $this->db->sql_freeresult($result);

        return $images;
    }

    /**
     * Get a seasonal image by ID
     *
     * @param int $id Image ID
     * @return array|false Image data or false if not found
     */
    public function get_image($id)
    {
        $sql = 'SELECT *
            FROM ' . $this->seasonal_images_table . '
            WHERE id = ' . (int) $id;
        $result = $this->db->sql_query($sql);
        $row = $this->db->sql_fetchrow($result);
        $this->db->sql_freeresult($result);

        return $row;
    }

    /**
     * Add a new seasonal image
     *
     * @param array $data Image data
     * @return int|false New image ID or false on failure
     */
    public function add_image($data)
    {
        $sql = 'INSERT INTO ' . $this->seasonal_images_table . ' ' .
            $this->db->sql_build_array('INSERT', $data);
        $this->db->sql_query($sql);

        $this->clear_cache();

        return $this->db->sql_nextid();
    }

    /**
     * Update a seasonal image
     *
     * @param int $id Image ID
     * @param array $data Image data
     * @return bool True on success
     */
    public function update_image($id, $data)
    {
        $sql = 'UPDATE ' . $this->seasonal_images_table . '
            SET ' . $this->db->sql_build_array('UPDATE', $data) . '
            WHERE id = ' . (int) $id;
        $this->db->sql_query($sql);

        $this->clear_cache();

        return true;
    }

    /**
     * Delete a seasonal image
     *
     * @param int $id Image ID
     * @return bool True on success
     */
    public function delete_image($id)
    {
        $sql = 'DELETE FROM ' . $this->seasonal_images_table . '
            WHERE id = ' . (int) $id;
        $this->db->sql_query($sql);

        $this->clear_cache();

        return true;
    }

    /**
     * Toggle enabled status of a seasonal image
     *
     * @param int $id Image ID
     * @return bool True on success
     */
    public function toggle_enabled($id)
    {
        $image = $this->get_image($id);
        if ($image)
        {
            $this->update_image($id, ['enabled' => !$image['enabled']]);
            return true;
        }
        return false;
    }

    /**
     * Clear the seasonal images cache
     */
    public function clear_cache()
    {
        $this->cache->destroy('_seasonal_images_active');
    }

    /**
     * Validate date values
     *
     * @param int $month Month (1-12)
     * @param int $day Day (1-31)
     * @return bool True if valid
     */
    public function validate_date($month, $day)
    {
        if ($month < 1 || $month > 12)
        {
            return false;
        }

        $days_in_month = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        if ($day < 1 || $day > $days_in_month[$month - 1])
        {
            return false;
        }

        return true;
    }
}
