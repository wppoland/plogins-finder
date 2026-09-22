<?php

declare(strict_types=1);

namespace Finder\Service;

defined('ABSPATH') || exit;

use Finder\Contract\HasHooks;
use WPPoland\StorefrontKit\Finder\ProductFinderEngine;

/**
 * Thin adapter over the storefront-kit {@see ProductFinderEngine}.
 *
 * Injects this plugin's text-domain ('trovilo'), option key
 * ('finder_settings'), asset URLs and translated labels into the
 * namespace-neutral engine, and supplies the closures it needs: enabled/settings
 * accessors, a template renderer, and a `resolveProduct` closure that turns a
 * product id into the card's presentation data from a live {@see \WC_Product}
 * (so price/stock are always current). All quiz orchestration (shortcode, REST
 * endpoint, asset enqueue, combination matching) lives in the kit.
 */
final class FinderService implements HasHooks
{
    private const OPTION = 'finder_settings';

    private ?ProductFinderEngine $engine = null;

    public function __construct()
    {
        // The engine ships with storefront-kit >= 1.7.0. When present, wire it
        // with this plugin's text-domain / option key / asset URLs. Otherwise
        // leave the service inert (see registerHooks()).
        if (! class_exists(ProductFinderEngine::class)) {
            return;
        }

        $this->engine = new ProductFinderEngine(
            restNamespace: 'plogins/v1',
            restRoute: 'finder/match',
            nonceAction: 'wp_rest',
            assetHandle: 'trovilo',
            styleUrl: \Finder\Plugin::instance()->url('assets/css/finder.css'),
            scriptUrl: \Finder\Plugin::instance()->url('assets/js/finder.js'),
            version: \Finder\VERSION,
            shortcodeTag: 'finder',
            widgetTemplate: 'finder',
            resultTemplate: 'result-card',
            labels: [
                'start'         => __('Start over', 'trovilo'),
                'back'          => __('Back', 'trovilo'),
                'step'          => __('Step {current} of {total}', 'trovilo'),
                'choose'        => __('Pick an option to continue.', 'trovilo'),
                'result_intro'  => __('Here is your match!', 'trovilo'),
                'loading'       => __('Finding your match…', 'trovilo'),
                'no_match'      => __('We could not find a match. Please try again.', 'trovilo'),
                'not_available' => __('That recommendation is not available right now.', 'trovilo'),
                'error'         => __('Something went wrong. Please try again.', 'trovilo'),
            ],
            isEnabled: fn (): bool => $this->isEnabled(),
            settings: fn (): array => $this->settings(),
            renderToString: function (string $template, array $context): string {
                ob_start();
                $this->renderTemplate($template, $context);

                return (string) ob_get_clean();
            },
            resolveProduct: fn (int $productId): ?array => $this->resolveProduct($productId),
        );
    }

    public function registerHooks(): void
    {
        if ($this->engine instanceof ProductFinderEngine) {
            $this->engine->registerHooks();

            return;
        }

        // TODO: storefront-kit < 1.7.0 has no ProductFinderEngine. Bump the
        // wppoland/storefront-kit copy (lib/storefront-kit) to enable the quiz.
        // No hooks are registered until the engine is present.
    }

    /**
     * Presentation data for a recommended product, or null when it cannot be
     * recommended (missing, not purchasable or out of stock). Read live so
     * price and stock are always current.
     *
     * Variable products are recommended like any other. The card is a link: it
     * shows the title, image, price and a link to the product page, with no
     * add-to-cart and no variation to choose, so a variable parent renders
     * correctly with its price range. The admin dropdown has always offered
     * them, so refusing them here left a merchant able to pick a product the
     * storefront would then decline.
     *
     * @return array<string, mixed>|null
     */
    private function resolveProduct(int $productId): ?array
    {
        $product = wc_get_product($productId);

        if (! $product instanceof \WC_Product || ! $product->is_purchasable() || ! $product->is_in_stock()) {
            return null;
        }

        return [
            'id'         => $product->get_id(),
            'title'      => $product->get_name(),
            'url'        => (string) $product->get_permalink(),
            'image_id'   => (int) $product->get_image_id(),
            'price_html' => (string) $product->get_price_html(),
            'show_price' => (bool) ($this->settings()['show_price'] ?? true),
        ];
    }

    private function isEnabled(): bool
    {
        return (bool) ($this->settings()['enabled'] ?? false);
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

    /**
     * @param array<string, mixed> $context
     */
    private function renderTemplate(string $template, array $context): void
    {
        $file = FINDER_DIR . 'templates/' . $template . '.php';

        if (! is_readable($file)) {
            return;
        }

        extract($context, EXTR_SKIP);
        require $file;
    }
}
