<?php
/**
 * Boot order: services listed here are resolved from the container and have
 * their registerHooks() called during Plugin::boot(). Each must implement
 * Finder\Contract\HasHooks.
 *
 * @package Finder
 *
 * @return array<class-string>
 */

declare(strict_types=1);

use Finder\Admin\Settings;
use Finder\Service\FinderService;

defined('ABSPATH') || exit;

return [
    FinderService::class,
    ...(is_admin() ? [Settings::class] : []),
];
