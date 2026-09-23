=== Trovilo - Product Finder Quiz for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, product finder, product quiz, product recommendation, guided selling
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 8.1
Stable tag: 1.1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A step-by-step product finder quiz for WooCommerce: guided single-choice questions lead each shopper to one product recommendation. No jQuery.

== Description ==

Trovilo adds a guided "help me choose" quiz to any page with the `[trovilo]` shortcode. Shoppers answer a short series of single-choice questions and are shown one recommended product, with its image, price and a button straight to the product page.

Trovilo is developed in the open. The code, and a place to report bugs or request features, live at [github.com/wppoland/plogins-finder](https://github.com/wppoland/plogins-finder).

You define the questions and options, then map every answer combination to a product. A "Generate combinations" button builds the map for you from your steps, so you only pick a product for each path.

= Documentation and links =

* **Documentation**: [plogins.com/plogins-finder/docs/](https://plogins.com/plogins-finder/docs/)
* **Plugin page**: [plogins.com/plogins-finder/](https://plogins.com/plogins-finder/)
* **Source code**: [github.com/wppoland/plogins-finder](https://github.com/wppoland/plogins-finder)
* **Bug reports and feature requests**: [github.com/wppoland/plogins-finder/issues](https://github.com/wppoland/plogins-finder/issues)

= Built for speed and accessibility =

* **No jQuery** in the plugin's own front-end code, the script is vanilla JS, deferred and loaded in the footer.
* **No layout shift (CLS).** Space is reserved for each step and the result card, so advancing never reflows the page. The recommendation image is prioritised for a fast LCP.
* **REST-powered result.** The recommendation is fetched from a lightweight REST endpoint (not admin-ajax), read live so price and stock are always current, and never cached.
* **Keyboard friendly.** Real radio groups, a live progress bar, visible focus styles, a Back button and full keyboard operability.
* **Resumable and shareable.** The current step and answers are kept in the URL and per-tab storage, so a refresh keeps your place and a fully-answered link opens straight on the result.

= Settings =

A WooCommerce-capability settings page (Trovilo menu) lets you:

* Enable or disable the quiz, set the heading, sub-heading and accent colour, and choose whether the price shows on the result card.
* Build the steps: each step is one question with single-choice options; the option "value" is a short slug used to identify the answer.
* Map results: generate one row per answer combination from your steps, then pick the recommended product for each, with an optional headline, description and button label. An at-a-glance callout shows how many combinations still need a product.
* Set a fallback product shown when no combination matches or a mapped product is unavailable.

= Translation ready =

All strings are translatable through the `trovilo` text domain, and a `trovilo.pot` template ships in `/languages`. Deleting the plugin removes its options.

= How it works =

The quiz is rendered server-side (so it can be cached with the page) and advanced entirely client-side, no round-trip between steps. Only the final recommendation is fetched, from a same-origin REST request to your own site, and rendered fresh so price and stock are current. The CSS and JavaScript are enqueued only on pages that contain the `[trovilo]` shortcode.

== Installation ==

1. Upload the plugin to `/wp-content/plugins/trovilo`, or install via Plugins > Add New.
2. Activate it. WooCommerce must be active.
3. Visit the **Trovilo** menu in wp-admin, build your steps, generate the combinations and pick a product for each, then enable the quiz.
4. Add the quiz to any page or post with the `[trovilo]` shortcode.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Yes. Trovilo requires an active WooCommerce installation.

= Does it use jQuery? =

No. The plugin's own front-end script is vanilla JavaScript with no jQuery dependency.

= Where do I put the quiz? =

Anywhere the `[trovilo]` shortcode can go, a page, a post, or a page-builder Raw HTML / shortcode block.

= How many questions can I ask? =

As many steps as you like, each with as many single-choice options as you like. Two or three short steps convert best.

= What happens at the end? =

The shopper sees one recommended product, its image, optional price, an optional headline and description, and a button to the product page. If no combination matches, or the mapped product is unavailable, your fallback product is shown.

= Is the price on the result always up to date? =

Yes. The recommendation is fetched live over REST on each request and is never cached, so price and stock reflect your store at that moment.

= Does this plugin work on WordPress Multisite? =

Yes. Network activate it or activate it on individual sites; each site keeps its own settings.

== Screenshots ==

1. The step-by-step Trovilo quiz on the storefront.
2. The recommendation card with the product and a call to action.
3. The Trovilo settings screen: steps builder and results map.

== External Services ==

Trovilo does not connect to, or send any data to, any external service or third-party server. It bundles no SDK, API client, web font, map tile, CDN asset or analytics call, everything runs on your own site.

All data stays inside your WordPress database: the questions, options, results map and settings live in the `finder_settings` option (with `finder_db_version` tracking the schema). When a shopper finishes the quiz, their answers are sent in a same-origin REST request to your site's own `/wp-json/` endpoint, which returns the recommendation; no outbound HTTP request is ever made. The current step and answers are also kept in the page URL and the browser's per-tab session storage so the quiz is resumable. Deleting the plugin removes its options.

== Changelog ==

= 1.1.1 =
* The shortcode is now `[trovilo]`. It used to be `[finder]`, a tag generic enough that any other plugin could claim it first, and which no longer matched the plugin's name. Nothing else about the quiz changed.
* The admin menu now reads Trovilo rather than Product Finder, and the remaining places the documentation still said Finder were updated.

= 1.1.0 =
* Renamed to Trovilo. The WordPress.org review team asks a plugin name to lead with a distinctive, coined identifier rather than a generic descriptive word. Trovilo is Esperanto for a finding tool. The text domain follows the name; the stored data, the settings and every hook are unchanged.

= 1.0.10 =
* Fixed: arrow glyphs in the admin menu paths, and in the strings handed to translators. An arrow inside a translatable string makes the glyph every translator's problem and changes the layout in any locale that drops it.

= 1.0.9 =
* The short description was 151 characters, one over the limit WordPress.org allows, so the plugin directory cut it off mid-sentence. Shortened by one word; the meaning is unchanged.

= 1.0.8 =
* The translation template was regenerated. It still named an older version of the plugin and pointed at source lines that had since moved, which is what translation tools read to show a string in context.

= 1.0.7 =
* Renamed to Plogins Finder - Product Finder Quiz for WooCommerce so the name leads with the brand rather than a generic word, which is what the WordPress.org plugin review team asks for. The plugin slug is unchanged.

= 1.0.6 =
* Tested against WordPress 7.1. Verified by activating this build on a clean 7.1 install with WooCommerce 11.1, not by editing the header.

= 1.0.5 =
* Accessibility improvements to the admin and storefront markup.

= 1.0.4 =
* Fixed: on desktop the page no longer scrolls down to the finder on load (focus now uses preventScroll).
* Fixed: the URL stays clean on first visit, quiz state (?fa=&fs=) is only written after the visitor answers.

= 1.0.3 =
* Added an "Open in new tab" option so the recommended product opens in a new browser tab.

= 1.0.2 =
* First stable release: guided step-by-step product finder quiz with a friendly steps/results admin, REST-powered live recommendation, accessible vanilla-JS front end and zero layout shift. Radio-style option indicators, subtle motion (reduced-motion aware) and a Polish translation.
