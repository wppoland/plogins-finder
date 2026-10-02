<?php
/**
 * Uninstall cleanup for Finder.
 *
 * Runs only when the plugin is deleted from wp-admin. Removes the options this
 * plugin created. Guarded by the WordPress uninstall constant so it can never
 * run in any other context. Finder stores everything in options. Versions up
 * to 1.1.4 also created an unused finder_compare_items table, dropped here.
 *
 * @package Finder
 */

declare(strict_types=1);

defined('WP_UNINSTALL_PLUGIN') || exit;

/**
 * Delete the plugin's options for the current site.
 */
function finder_uninstall_cleanup(): void
{
    delete_option('finder_settings');
    delete_option('finder_db_version');

    global $wpdb;
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}finder_compare_items");
}

if (is_multisite()) {
    $finder_sites = get_sites(['fields' => 'ids', 'number' => 0]);

    foreach ($finder_sites as $finder_site_id) {
        switch_to_blog((int) $finder_site_id);
        finder_uninstall_cleanup();
        restore_current_blog();
    }
} else {
    finder_uninstall_cleanup();
}
