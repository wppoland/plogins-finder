<?php

declare(strict_types=1);

namespace Finder\Admin;

defined('ABSPATH') || exit;

use Finder\Contract\HasHooks;

/**
 * Admin settings page registered as a top-level "Finder" menu.
 *
 * Stores everything in the `finder_settings` option (array): the master toggle,
 * presentation (title / intro / accent), the ordered quiz steps (question +
 * single-choice options), the results map (one product per answer combination)
 * and a fallback. All output is escaped; all input is sanitised on save. A
 * friendly UI (cards, "?" tooltips, a repeater for steps/results, an at-a-glance
 * coverage callout) lives in this page + assets/{css,js}/admin.
 */
final class Settings implements HasHooks
{
    private const OPTION = 'finder_settings';
    private const PAGE   = 'finder-settings';

    /** Monotonic counter so each help tooltip gets a unique DOM id. */
    private int $tipSeq = 0;

    public function registerHooks(): void
    {
        add_action('admin_menu', [$this, 'addMenuPage']);
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
        add_filter(
            'plugin_action_links_' . plugin_basename(\Finder\PLUGIN_FILE),
            [$this, 'actionLinks']
        );
    }

    /**
     * Add a "Settings" link on the Plugins screen.
     *
     * @param array<int, string> $links
     *
     * @return array<int, string>
     */
    public function actionLinks(array $links): array
    {
        $url = admin_url('admin.php?page=' . self::PAGE);
        array_unshift(
            $links,
            '<a href="' . esc_url($url) . '">' . esc_html__('Settings', 'trovilo') . '</a>'
        );

        return $links;
    }

    /**
     * Enqueue the settings-screen styles/script only on the Finder page.
     */
    public function enqueueAssets(string $hook): void
    {
        if ('toplevel_page_' . self::PAGE !== $hook) {
            return;
        }

        $plugin = \Finder\Plugin::instance();

        wp_enqueue_style('finder-admin', $plugin->url('assets/css/admin.css'), [], \Finder\VERSION);
        wp_enqueue_script(
            'finder-admin',
            $plugin->url('assets/js/admin.js'),
            [],
            \Finder\VERSION,
            ['in_footer' => true, 'strategy' => 'defer']
        );

        wp_localize_script('finder-admin', 'ploginsFinderAdmin', [
            'option'   => self::OPTION,
            'products' => $this->productChoices(),
            'i18n'     => [
                'option'          => __('- Select a product -', 'trovilo'),
                'addStep'         => __('Add step', 'trovilo'),
                'addOption'       => __('Add option', 'trovilo'),
                'remove'          => __('Remove', 'trovilo'),
                'removeStep'      => __('Remove step', 'trovilo'),
                'question'        => __('Question', 'trovilo'),
                'help'            => __('Hint (optional)', 'trovilo'),
                'optionLabel'     => __('Option label', 'trovilo'),
                'optionValue'     => __('Value (slug)', 'trovilo'),
                'generate'        => __('Generate / refresh combinations', 'trovilo'),
                'product'         => __('Recommended product', 'trovilo'),
                'headline'        => __('Headline (optional)', 'trovilo'),
                'blurb'           => __('Description (optional)', 'trovilo'),
                'cta'             => __('Button label (optional)', 'trovilo'),
                'coverageDone'    => __('All combinations have a product. ✓', 'trovilo'),
                'coverageMissing' => __('{count} combination(s) still need a product.', 'trovilo'),
                'needSteps'       => __('Define at least one step with options first.', 'trovilo'),
            ],
        ]);
    }

    public function addMenuPage(): void
    {
        add_menu_page(
            __('Trovilo: product finder quiz', 'trovilo'),
            __('Product Finder', 'trovilo'),
            'manage_woocommerce',
            self::PAGE,
            [$this, 'renderPage'],
            'dashicons-search',
            58
        );
    }

    public function registerSettings(): void
    {
        register_setting(self::PAGE, self::OPTION, [
            'type'              => 'array',
            'sanitize_callback' => [$this, 'sanitize'],
        ]);

        add_filter(
            'option_page_capability_' . self::PAGE,
            static fn (): string => 'manage_woocommerce'
        );
    }

    public function renderPage(): void
    {
        if (! current_user_can('manage_woocommerce')) {
            return;
        }

        $settings = $this->settings();
        $enabled  = (bool) ($settings['enabled'] ?? false);
        $steps    = is_array($settings['steps'] ?? null) ? $settings['steps'] : [];
        $results  = is_array($settings['results'] ?? null) ? $settings['results'] : [];
        $fallback = is_array($settings['fallback'] ?? null) ? $settings['fallback'] : [];
        ?>
        <div class="wrap finder-admin">
            <h1>
                <?php echo esc_html(get_admin_page_title()); ?>
                <?php if ($enabled) : ?>
                    <span class="finder-status finder-status--on"><span class="finder-status__dot" aria-hidden="true"></span><?php esc_html_e('Live', 'trovilo'); ?></span>
                <?php else : ?>
                    <span class="finder-status finder-status--off"><span class="finder-status__dot" aria-hidden="true"></span><?php esc_html_e('Off', 'trovilo'); ?></span>
                <?php endif; ?>
            </h1>

            <p class="finder-admin__intro">
                <?php esc_html_e('Build a friendly step-by-step quiz that guides shoppers to one product. Add it to any page with the shortcode below, define your questions, then map every answer combination to a product.', 'trovilo'); ?>
            </p>

            <p class="finder-admin__shortcode">
                <?php esc_html_e('Shortcode:', 'trovilo'); ?>
                <code>[finder]</code>
            </p>

            <form method="post" action="options.php" class="finder-admin__form">
                <?php settings_fields(self::PAGE); ?>

                <div class="finder-card">
                    <h2><?php esc_html_e('Basics', 'trovilo'); ?></h2>
                    <table class="form-table" role="presentation">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <?php esc_html_e('Enable finder', 'trovilo'); ?>
                                    <?php $this->helpTip(__('The master switch. Keep it off until every combination below has a product mapped, then turn it on so the quiz appears wherever you placed the shortcode.', 'trovilo')); ?>
                                </th>
                                <td>
                                    <label for="finder_enabled">
                                        <input type="checkbox" id="finder_enabled" name="<?php echo esc_attr(self::OPTION); ?>[enabled]" value="1" <?php checked($enabled, true); ?> />
                                        <?php esc_html_e('Show the finder quiz on the storefront.', 'trovilo'); ?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="finder_title"><?php esc_html_e('Heading', 'trovilo'); ?></label></th>
                                <td>
                                    <input type="text" id="finder_title" class="regular-text" name="<?php echo esc_attr(self::OPTION); ?>[title]" value="<?php echo esc_attr((string) ($settings['title'] ?? '')); ?>" placeholder="<?php esc_attr_e('Find your perfect match in 30 seconds', 'trovilo'); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="finder_intro"><?php esc_html_e('Sub-heading', 'trovilo'); ?></label></th>
                                <td>
                                    <input type="text" id="finder_intro" class="large-text" name="<?php echo esc_attr(self::OPTION); ?>[intro]" value="<?php echo esc_attr((string) ($settings['intro'] ?? '')); ?>" placeholder="<?php esc_attr_e('Answer a couple of questions and we’ll recommend the right product.', 'trovilo'); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="finder_accent"><?php esc_html_e('Accent colour', 'trovilo'); ?></label>
                                    <?php $this->helpTip(__('Used for the progress bar, the selected option and the main button. Pick a colour that matches your brand.', 'trovilo')); ?>
                                </th>
                                <td>
                                    <input type="color" id="finder_accent" name="<?php echo esc_attr(self::OPTION); ?>[accent_color]" value="<?php echo esc_attr((string) ($settings['accent_color'] ?? '#d97706')); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e('Show price', 'trovilo'); ?></th>
                                <td>
                                    <label for="finder_show_price">
                                        <input type="checkbox" id="finder_show_price" name="<?php echo esc_attr(self::OPTION); ?>[show_price]" value="1" <?php checked((bool) ($settings['show_price'] ?? true), true); ?> />
                                        <?php esc_html_e('Show the product price on the recommendation card.', 'trovilo'); ?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e('Open in new tab', 'trovilo'); ?></th>
                                <td>
                                    <label for="finder_new_tab">
                                        <input type="checkbox" id="finder_new_tab" name="<?php echo esc_attr(self::OPTION); ?>[new_tab]" value="1" <?php checked((bool) ($settings['new_tab'] ?? false), true); ?> />
                                        <?php esc_html_e('Open the recommended product in a new browser tab.', 'trovilo'); ?>
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="finder-card">
                    <h2>
                        <?php esc_html_e('Steps', 'trovilo'); ?>
                        <?php $this->helpTip(__('Each step is one question with single-choice options. The option “value” is a short slug used to identify the answer, keep it lowercase and unique within a step (e.g. home, gym).', 'trovilo')); ?>
                    </h2>
                    <p class="finder-card__hint"><?php esc_html_e('Add your questions and answers. Two or three short steps convert best.', 'trovilo'); ?></p>

                    <div class="finder-steps" data-finder-steps>
                        <?php foreach (array_values($steps) as $i => $step) : ?>
                            <?php $this->renderStep((int) $i, is_array($step) ? $step : []); ?>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="button button-secondary" data-finder-add-step>
                        <?php esc_html_e('Add step', 'trovilo'); ?>
                    </button>
                </div>

                <div class="finder-card">
                    <h2>
                        <?php esc_html_e('Results map', 'trovilo'); ?>
                        <?php $this->helpTip(__('One row per answer combination. Click “Generate / refresh combinations” to build the list from your steps, then pick the product each combination should recommend. Existing picks are kept.', 'trovilo')); ?>
                    </h2>
                    <p class="finder-callout" data-finder-coverage aria-live="polite"></p>

                    <div class="finder-results" data-finder-results>
                        <?php foreach (array_values($results) as $i => $row) : ?>
                            <?php $this->renderResultRow((int) $i, is_array($row) ? $row : []); ?>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="button button-secondary" data-finder-generate>
                        <?php esc_html_e('Generate / refresh combinations', 'trovilo'); ?>
                    </button>
                </div>

                <div class="finder-card">
                    <h2>
                        <?php esc_html_e('Fallback', 'trovilo'); ?>
                        <?php $this->helpTip(__('Shown when no combination matches, or when a mapped product is unavailable. A safe, popular product is a good choice.', 'trovilo')); ?>
                    </h2>
                    <table class="form-table" role="presentation">
                        <tbody>
                            <tr>
                                <th scope="row"><label for="finder_fallback_product"><?php esc_html_e('Recommended product', 'trovilo'); ?></label></th>
                                <td><?php $this->renderProductSelect(self::OPTION . '[fallback][product_id]', 'finder_fallback_product', (int) ($fallback['product_id'] ?? 0)); ?></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="finder_fallback_headline"><?php esc_html_e('Headline', 'trovilo'); ?></label></th>
                                <td><input type="text" id="finder_fallback_headline" class="regular-text" name="<?php echo esc_attr(self::OPTION); ?>[fallback][headline]" value="<?php echo esc_attr((string) ($fallback['headline'] ?? '')); ?>" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="finder_fallback_blurb"><?php esc_html_e('Description', 'trovilo'); ?></label></th>
                                <td><textarea id="finder_fallback_blurb" class="large-text" rows="2" name="<?php echo esc_attr(self::OPTION); ?>[fallback][blurb]"><?php echo esc_textarea((string) ($fallback['blurb'] ?? '')); ?></textarea></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="finder_fallback_cta"><?php esc_html_e('Button label', 'trovilo'); ?></label></th>
                                <td><input type="text" id="finder_fallback_cta" class="regular-text" name="<?php echo esc_attr(self::OPTION); ?>[fallback][cta_label]" value="<?php echo esc_attr((string) ($fallback['cta_label'] ?? '')); ?>" placeholder="<?php esc_attr_e('View product', 'trovilo'); ?>" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render one step card (question + help + option rows).
     *
     * @param array<string, mixed> $step
     */
    private function renderStep(int $index, array $step): void
    {
        $base    = self::OPTION . '[steps][' . $index . ']';
        $options = is_array($step['options'] ?? null) ? $step['options'] : [];
        ?>
        <div class="finder-step" data-finder-step>
            <div class="finder-step__head">
                <span class="finder-step__badge" data-finder-step-badge><?php echo esc_html((string) ($index + 1)); ?></span>
                <button type="button" class="button-link finder-step__remove" data-finder-remove-step><?php esc_html_e('Remove step', 'trovilo'); ?></button>
            </div>
            <p>
                <label class="finder-field__label"><?php esc_html_e('Question', 'trovilo'); ?></label>
                <input type="text" class="regular-text" aria-label="<?php esc_attr_e('Question', 'trovilo'); ?>" data-finder-name="[steps][__STEP__][question]" name="<?php echo esc_attr($base); ?>[question]" value="<?php echo esc_attr((string) ($step['question'] ?? '')); ?>" />
            </p>
            <p>
                <label class="finder-field__label"><?php esc_html_e('Hint (optional)', 'trovilo'); ?></label>
                <input type="text" class="regular-text" aria-label="<?php esc_attr_e('Hint (optional)', 'trovilo'); ?>" data-finder-name="[steps][__STEP__][help]" name="<?php echo esc_attr($base); ?>[help]" value="<?php echo esc_attr((string) ($step['help'] ?? '')); ?>" />
            </p>
            <div class="finder-options" data-finder-options>
                <?php foreach (array_values($options) as $j => $option) : ?>
                    <?php $this->renderOption($index, (int) $j, is_array($option) ? $option : []); ?>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button button-small" data-finder-add-option><?php esc_html_e('Add option', 'trovilo'); ?></button>
        </div>
        <?php
    }

    /**
     * @param array<string, mixed> $option
     */
    private function renderOption(int $stepIndex, int $optIndex, array $option): void
    {
        $base = self::OPTION . '[steps][' . $stepIndex . '][options][' . $optIndex . ']';
        ?>
        <div class="finder-option-row" data-finder-option-row>
            <input type="text" class="finder-option-row__label" aria-label="<?php esc_attr_e('Option label', 'trovilo'); ?>" placeholder="<?php esc_attr_e('Option label', 'trovilo'); ?>" data-finder-name="[steps][__STEP__][options][__OPT__][label]" name="<?php echo esc_attr($base); ?>[label]" value="<?php echo esc_attr((string) ($option['label'] ?? '')); ?>" />
            <input type="text" class="finder-option-row__value" aria-label="<?php esc_attr_e('Value (slug)', 'trovilo'); ?>" placeholder="<?php esc_attr_e('value', 'trovilo'); ?>" data-finder-name="[steps][__STEP__][options][__OPT__][value]" data-finder-value name="<?php echo esc_attr($base); ?>[value]" value="<?php echo esc_attr((string) ($option['value'] ?? '')); ?>" />
            <button type="button" class="button-link finder-option-row__remove" data-finder-remove-option aria-label="<?php esc_attr_e('Remove option', 'trovilo'); ?>">&times;</button>
        </div>
        <?php
    }

    /**
     * Render one results-map row.
     *
     * @param array<string, mixed> $row
     */
    private function renderResultRow(int $index, array $row): void
    {
        $base  = self::OPTION . '[results][' . $index . ']';
        $match = is_array($row['match'] ?? null) ? array_values($row['match']) : [];
        ?>
        <div class="finder-result-row" data-finder-result-row data-finder-match="<?php echo esc_attr(implode('|', array_map('strval', $match))); ?>">
            <div class="finder-result-row__combo">
                <?php foreach ($match as $value) : ?>
                    <span class="finder-chip"><?php echo esc_html((string) $value); ?></span>
                    <input type="hidden" name="<?php echo esc_attr($base); ?>[match][]" value="<?php echo esc_attr((string) $value); ?>" />
                <?php endforeach; ?>
            </div>
            <div class="finder-result-row__fields">
                <?php $this->renderProductSelect($base . '[product_id]', '', (int) ($row['product_id'] ?? 0)); ?>
                <input type="text" aria-label="<?php esc_attr_e('Headline (optional)', 'trovilo'); ?>" placeholder="<?php esc_attr_e('Headline (optional)', 'trovilo'); ?>" name="<?php echo esc_attr($base); ?>[headline]" value="<?php echo esc_attr((string) ($row['headline'] ?? '')); ?>" />
                <input type="text" aria-label="<?php esc_attr_e('Description (optional)', 'trovilo'); ?>" placeholder="<?php esc_attr_e('Description (optional)', 'trovilo'); ?>" name="<?php echo esc_attr($base); ?>[blurb]" value="<?php echo esc_attr((string) ($row['blurb'] ?? '')); ?>" />
                <input type="text" aria-label="<?php esc_attr_e('Button label (optional)', 'trovilo'); ?>" placeholder="<?php esc_attr_e('Button label (optional)', 'trovilo'); ?>" name="<?php echo esc_attr($base); ?>[cta_label]" value="<?php echo esc_attr((string) ($row['cta_label'] ?? '')); ?>" />
            </div>
        </div>
        <?php
    }

    /**
     * Render a product <select> populated server-side (house pattern, no select2).
     */
    private function renderProductSelect(string $name, string $id, int $selected): void
    {
        // When no id is supplied there is no associated <label> (result-map rows),
        // so give the control an accessible name via aria-label. The fallback
        // caller passes an id and already has a matching <label for>.
        $attrs  = 'name="' . esc_attr($name) . '"';
        $attrs .= $id !== ''
            ? ' id="' . esc_attr($id) . '"'
            : ' aria-label="' . esc_attr__('Recommended product', 'trovilo') . '"';
        echo '<select class="finder-product-select" data-finder-product ' . $attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<option value="0">' . esc_html__('- Select a product -', 'trovilo') . '</option>';

        foreach ($this->productChoices() as $productId => $label) {
            printf(
                '<option value="%d"%s>%s</option>',
                (int) $productId,
                selected($selected, (int) $productId, false),
                esc_html($label)
            );
        }

        echo '</select>';
    }

    /**
     * Purchasable simple/variable products as id => "Name (#id)".
     *
     * @return array<int, string>
     */
    private function productChoices(): array
    {
        $products = wc_get_products([
            'status' => 'publish',
            'limit'  => 200,
            'orderby' => 'title',
            'order'  => 'ASC',
            'return' => 'objects',
        ]);

        $choices = [];

        foreach ($products as $product) {
            if (! $product instanceof \WC_Product) {
                continue;
            }

            $choices[(int) $product->get_id()] = sprintf('%s (#%d)', $product->get_name(), $product->get_id());
        }

        return $choices;
    }

    /**
     * Small, accessible "?" help affordance (aria-describedby > tooltip).
     */
    private function helpTip(string $text): void
    {
        $tipId = 'finder-tip-' . (++$this->tipSeq);
        ?>
        <span class="finder-help">
            <button type="button" class="finder-help__toggle" aria-describedby="<?php echo esc_attr($tipId); ?>" aria-label="<?php esc_attr_e('More information', 'trovilo'); ?>">?</button>
            <span class="finder-help__tip" id="<?php echo esc_attr($tipId); ?>" role="tooltip"><?php echo esc_html($text); ?></span>
        </span>
        <?php
    }

    /**
     * Sanitise submitted settings, preserving defaults for anything off-form.
     *
     * @param mixed $raw
     *
     * @return array<string, mixed>
     */
    public function sanitize(mixed $raw): array
    {
        if (! is_array($raw)) {
            $raw = [];
        }

        $defaults = $this->settings();

        $accent = isset($raw['accent_color']) ? sanitize_hex_color((string) $raw['accent_color']) : '';

        return array_merge($defaults, [
            'enabled'      => ! empty($raw['enabled']),
            'title'        => isset($raw['title']) ? sanitize_text_field(wp_unslash((string) $raw['title'])) : '',
            'intro'        => isset($raw['intro']) ? sanitize_text_field(wp_unslash((string) $raw['intro'])) : '',
            'accent_color' => is_string($accent) && $accent !== '' ? $accent : '#d97706',
            'show_price'   => ! empty($raw['show_price']),
            'new_tab'      => ! empty($raw['new_tab']),
            'steps'        => $this->sanitizeSteps($raw['steps'] ?? null),
            'results'      => $this->sanitizeResults($raw['results'] ?? null),
            'fallback'     => $this->sanitizeResultEntry($raw['fallback'] ?? null),
        ]);
    }

    /**
     * @param mixed $raw
     *
     * @return list<array{question: string, help: string, options: list<array{label: string, value: string}>}>
     */
    private function sanitizeSteps(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $steps = [];

        foreach ($raw as $step) {
            if (! is_array($step)) {
                continue;
            }

            $options = [];

            if (is_array($step['options'] ?? null)) {
                foreach ($step['options'] as $option) {
                    if (! is_array($option)) {
                        continue;
                    }

                    $label = isset($option['label']) ? sanitize_text_field(wp_unslash((string) $option['label'])) : '';
                    $value = isset($option['value']) ? sanitize_key((string) $option['value']) : '';

                    if ($label === '' || $value === '') {
                        continue;
                    }

                    $options[] = ['label' => $label, 'value' => $value];
                }
            }

            if ($options === []) {
                continue;
            }

            $steps[] = [
                'question' => isset($step['question']) ? sanitize_text_field(wp_unslash((string) $step['question'])) : '',
                'help'     => isset($step['help']) ? sanitize_text_field(wp_unslash((string) $step['help'])) : '',
                'options'  => $options,
            ];
        }

        return $steps;
    }

    /**
     * @param mixed $raw
     *
     * @return list<array<string, mixed>>
     */
    private function sanitizeResults(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $results = [];

        foreach ($raw as $row) {
            if (! is_array($row)) {
                continue;
            }

            $match = [];

            if (is_array($row['match'] ?? null)) {
                foreach ($row['match'] as $value) {
                    $slug = sanitize_key((string) $value);

                    if ($slug !== '') {
                        $match[] = $slug;
                    }
                }
            }

            if ($match === []) {
                continue;
            }

            $entry          = $this->sanitizeResultEntry($row);
            $entry['match'] = $match;
            $results[]      = $entry;
        }

        return $results;
    }

    /**
     * @param mixed $raw
     *
     * @return array<string, mixed>
     */
    private function sanitizeResultEntry(mixed $raw): array
    {
        $raw = is_array($raw) ? $raw : [];

        return [
            'product_id' => isset($raw['product_id']) ? absint($raw['product_id']) : 0,
            'headline'   => isset($raw['headline']) ? sanitize_text_field(wp_unslash((string) $raw['headline'])) : '',
            'blurb'      => isset($raw['blurb']) ? sanitize_textarea_field(wp_unslash((string) $raw['blurb'])) : '',
            'cta_label'  => isset($raw['cta_label']) ? sanitize_text_field(wp_unslash((string) $raw['cta_label'])) : '',
        ];
    }

    /**
     * Stored settings merged over packaged defaults.
     *
     * @return array<string, mixed>
     */
    private function settings(): array
    {
        $stored = get_option(self::OPTION, []);

        if (! is_array($stored)) {
            $stored = [];
        }

        /** @var array<string, mixed> $defaults */
        $defaults = require FINDER_DIR . 'config/defaults.php';

        return array_merge($defaults, $stored);
    }
}
