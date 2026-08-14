<?php
/**
 * Default settings, merged under the option key `finder_settings`.
 *
 * Ships DISABLED so the merchant reviews the steps and maps every combination to
 * a real product before the quiz goes live. The seeded steps are a ready-made
 * two-question example (where to train x main goal); the merchant edits them and
 * fills the results map from the Finder settings screen. All finder logic lives
 * in the storefront-kit ProductFinderEngine; these values are passed through to
 * it as the resolved settings.
 *
 * @package Finder
 *
 * @return array<string, mixed>
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

return [
    // Off until the merchant has mapped products to every combination.
    'enabled' => false,

    // Presentation.
    'title'        => '',
    'intro'        => '',
    'accent_color' => '#d97706',
    'show_price'   => true,
    'new_tab'      => false,

    /*
     * Steps: an ordered list of single-choice questions. Each option's `value`
     * is a slug used to build the combination key that the results map is keyed
     * on. Seeded with a two-step example; fully editable in the admin.
     */
    'steps' => [
        [
            'question' => '',
            'help'     => '',
            'options'  => [
                ['label' => '', 'value' => 'a1'],
                ['label' => '', 'value' => 'a2'],
            ],
        ],
        [
            'question' => '',
            'help'     => '',
            'options'  => [
                ['label' => '', 'value' => 'b1'],
                ['label' => '', 'value' => 'b2'],
                ['label' => '', 'value' => 'b3'],
                ['label' => '', 'value' => 'b4'],
            ],
        ],
    ],

    /*
     * Results map: one row per combination. `match` is the ordered list of the
     * chosen option values (step 1 value, step 2 value, ...). `product_id` is the
     * recommended WooCommerce product. Empty by default, the admin generates the
     * grid from the defined steps and picks a product per combination.
     *
     * @var list<array{match: list<string>, product_id: int, headline: string, blurb: string, cta_label: string}>
     */
    'results' => [],

    // Shown when no combination matches (or a mapped product is unavailable).
    'fallback' => [
        'product_id' => 0,
        'headline'   => '',
        'blurb'      => '',
        'cta_label'  => '',
    ],
];
