<?php
/**
 * Constants needed by PHPStan to analyse the plugin without bootstrapping WordPress.
 *
 * @package Finder
 */

declare(strict_types=1);

namespace {
    if (! defined('ABSPATH')) {
        define('ABSPATH', '/tmp/wordpress/');
    }
    if (! defined('FINDER_DIR')) {
        define('FINDER_DIR', '/tmp/finder/');
    }
    if (! defined('FINDER_URL')) {
        define('FINDER_URL', 'https://example.test/wp-content/plugins/finder/');
    }
}

namespace Finder {
    if (! defined('Finder\\VERSION')) {
        define('Finder\\VERSION', '0.1.0');
    }
    if (! defined('Finder\\PLUGIN_FILE')) {
        define('Finder\\PLUGIN_FILE', '/tmp/finder/finder.php');
    }
}
