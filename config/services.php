<?php
/**
 * Service wiring. Returns a closure that registers every service in the
 * container. Keep services thin; the product-finder logic lives in the
 * storefront-kit ProductFinderEngine instantiated inside FinderService with
 * this plugin's text-domain / option prefix / asset URLs.
 *
 * @package Finder
 */

declare(strict_types=1);

use Finder\Admin\Settings;
use Finder\Container;
use Finder\Migrator;
use Finder\Service\FinderService;

defined('ABSPATH') || exit;

return static function (Container $c): void {
    $c->singleton(Migrator::class, static fn (): Migrator => new Migrator());

    // Thin adapter over the storefront-kit ProductFinderEngine.
    $c->singleton(FinderService::class, static fn (): FinderService => new FinderService());

    // Admin (only needed in wp-admin context).
    if (is_admin()) {
        $c->singleton(Settings::class, static fn (): Settings => new Settings());
    }
};
