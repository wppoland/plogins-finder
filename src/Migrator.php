<?php

declare(strict_types=1);

namespace Finder;

defined('ABSPATH') || exit;

/**
 * Idempotent schema/version migrations, run on every boot. Compares a stored
 * option against VERSION and applies forward steps as needed: drops the
 * unused compare-items table older versions created and seeds the default
 * settings once.
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

        $this->dropLeftoverCompareTable();
        $this->seedDefaultSettings();

        update_option(self::OPTION, VERSION, false);
    }

    /**
     * Drop the compare-items table earlier versions created. Nothing in Finder
     * ever read or wrote it; it was copied from the compare engine.
     */
    private function dropLeftoverCompareTable(): void
    {
        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}finder_compare_items");
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
