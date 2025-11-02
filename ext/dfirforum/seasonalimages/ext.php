<?php
/**
 *
 * Seasonal Images. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, DFIR Forum
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dfirforum\seasonalimages;

/**
 * Extension class for Seasonal Images
 */
class ext extends \phpbb\extension\base
{
    /**
     * Check whether the extension can be enabled.
     * Provides meaningful(s) error message(s) if the environment isn't suitable
     * for the extension.
     *
     * @return bool|array True if can be enabled, array of error message(s) otherwise
     */
    public function is_enableable()
    {
        // Requires phpBB 3.3.0 due to added functionality and backwards incompatible changes
        $config = $this->container->get('config');
        return phpbb_version_compare($config['version'], '3.3.0', '>=');
    }
}
