<?php
/**
 * Autoloading: prefer Composer's vendor autoloader (ships the storefront-kit and
 * the optimized classmap). Fall back to a minimal PSR-4 autoloader so the plugin
 * still boots if vendor/ is somehow absent.
 *
 * @package Finder
 */

declare(strict_types=1);

namespace Finder;

defined('ABSPATH') || exit;

$finder_composer = __DIR__ . '/vendor/autoload.php';
if (is_readable($finder_composer)) {
    require_once $finder_composer;
    return;
}

spl_autoload_register(static function (string $class): void {
    $prefixes = [
        'Finder\\'           => __DIR__ . '/src/',
        'WPPoland\\StorefrontKit\\'    => __DIR__ . '/lib/storefront-kit/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }
        $relative = substr($class, $len);
        $file     = $baseDir . str_replace('\\', '/', $relative) . '.php';
        if (is_readable($file)) {
            require_once $file;
        }
        return;
    }
});
