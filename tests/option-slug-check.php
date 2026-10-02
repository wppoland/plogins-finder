<?php

/**
 * The saved answer value must equal the slug the admin script writes into the
 * results map, or that answer can never match its product.
 *
 * Settings sanitised step values with sanitize_key(), which deletes a space or
 * an accent, while assets/js/admin.js slugify() turns each run of them into a
 * dash. "home gym" was saved as "homegym" on the step and "home-gym" in the
 * results map, so a shopper who picked it always got the fallback product.
 *
 * Expected values below are what slugify() returns in a browser.
 *
 * Run: php tests/option-slug-check.php
 */

declare(strict_types=1);

namespace Finder\Contract {
    interface HasHooks
    {
    }
}

namespace {
    define('ABSPATH', __DIR__);
    require __DIR__ . '/../src/Admin/Settings.php';

    $cases = [
        'home gym'      => 'home-gym',
        'Home  Gym!'    => 'home-gym',
        'a1'            => 'a1',
        'under_50'      => 'under_50',
        ' -Outdoor- '   => 'outdoor',
        'Łódź centrum'  => 'd-centrum',
    ];

    $failures = 0;
    foreach ($cases as $in => $want) {
        $got = \Finder\Admin\Settings::optionSlug($in);
        if ($got !== $want) {
            echo "FAIL: '{$in}' gave '{$got}', admin.js gives '{$want}'\n";
            $failures++;
        }
    }

    echo 0 === $failures ? "OK: saved answer values match the admin results map\n" : '';
    exit($failures > 0 ? 1 : 0);
}
