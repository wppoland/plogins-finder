<?php

declare(strict_types=1);

namespace Finder;

defined('ABSPATH') || exit;

/**
 * Idempotent schema/version migrations, run on every boot. Compares a stored
 * option against VERSION and applies forward steps as needed: creates the
 * compare-items table and seeds the default settings once.
 */
final class Migrator
{
    private const OPTION   = 'finder_db_version';
    private const SETTINGS = 'finder_settings';

    public function maybeMigrate(): void
    {
        $current = (string) get_option(self::OPTION, '0');

        if (version_compare($current, VERSION, '>=')) {
            return;
        }

        $this->createCompareTable();
        $this->seedDefaultSettings();

        update_option(self::OPTION, VERSION, false);
    }

    /**
     * Compare-items storage: one row per (product, owner) pair, where the owner
     * is either a logged-in user id or a guest session id. Oldest-first via
     * created_at so the engine can enforce the per-list cap.
     */
    private function createCompareTable(): void
    {
        global $wpdb;

        $table           = $wpdb->prefix . 'finder_compare_items';
        $charsetCollate  = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            product_id BIGINT UNSIGNED NOT NULL,
            user_id BIGINT UNSIGNED NULL DEFAULT NULL,
            session_id VARCHAR(64) NULL DEFAULT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY session_id (session_id),
            KEY product_id (product_id)
        ) {$charsetCollate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    /**
     * Seed the default settings once, without clobbering an existing config.
     */
    private function seedDefaultSettings(): void
    {
        if (get_option(self::SETTINGS, null) !== null) {
            return;
        }

        /** @var array<string, mixed> $defaults */
        $defaults = require FINDER_DIR . 'config/defaults.php';

        add_option(self::SETTINGS, $defaults, '', false);
    }
}
