# Finder, Product Finder Quiz for WooCommerce

A friendly step-by-step product finder quiz for WooCommerce. Guided single-choice
questions lead each shopper to one product recommendation. Accessible, no jQuery,
zero layout shift, REST-powered live result.

- **Plugin page:** https://plogins.com/plogins-finder/
- **Documentation:** https://plogins.com/plogins-finder/docs/
- **Author:** [WPPoland.com](https://wppoland.com)
- **WordPress.org contributor:** motylanogha
- **Source:** https://github.com/wppoland/plogins-finder

## Usage

1. Activate the plugin (WooCommerce required).
2. Go to **Finder** in wp-admin: build your steps (question + single-choice
   options), click **Generate / refresh combinations**, pick a product for each
   combination, set a fallback, then enable the quiz.
3. Add it to any page with the `[finder]` shortcode.

## Architecture

Thin plugin wrapper over the shared `WPPoland\StorefrontKit\Finder\ProductFinderEngine`
(vendored under `lib/storefront-kit/`). The engine owns the shortcode, the REST
match endpoint (`/wp-json/plogins/v1/finder/match`) and asset enqueue; the plugin
supplies text domain, the `finder_settings` option, templates and the
`resolveProduct` closure. Settings live in one option; there is no custom table.

## Development

```
composer install
composer cs        # phpcs
composer analyse   # phpstan level 6
```
