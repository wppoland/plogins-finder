<?php
/**
 * Recommendation card, returned as HTML by the REST match endpoint and injected
 * into the quiz result region by the front script. Rendered fresh on every
 * request, so price/stock are current. Every value is escaped here.
 *
 * The product image carries fetchpriority="high" + loading="eager" so the
 * browser prioritises it as the LCP element of this interaction, with explicit
 * dimensions (via wp_get_attachment_image) to avoid layout shift.
 *
 * @package Finder
 *
 * @var array<string, mixed>  $product      id, title, url, image_id, price_html, show_price.
 * @var string                $headline     Optional result headline.
 * @var string                $blurb        Optional result copy.
 * @var string                $cta_label    Button label (falls back to a default).
 * @var string                $accent_color Sanitised hex accent.
 * @var bool                  $new_tab      Open the product links in a new tab.
 * @var array<string, string> $labels       Translated UI strings.
 */

defined('ABSPATH') || exit;

// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited -- template-scope vars injected via extract().

$finder_image_id = (int) ($product['image_id'] ?? 0);
$finder_cta      = $cta_label !== '' ? $cta_label : __('View product', 'elektilo');
$finder_target   = ! empty($new_tab) ? ' target="_blank" rel="noopener noreferrer"' : '';
?>
<div class="plogins-finder-card" style="--finder-accent: <?php echo esc_attr($accent_color); ?>;">
	<p class="plogins-finder-card__eyebrow">
		<?php echo esc_html($headline !== '' ? $headline : ($labels['result_intro'] ?? '')); ?>
	</p>

	<a class="plogins-finder-card__media" href="<?php echo esc_url((string) $product['url']); ?>"<?php echo $finder_target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed internal attribute string. ?>>
		<?php
		if ($finder_image_id > 0) {
			echo wp_get_attachment_image(
				$finder_image_id,
				'woocommerce_single',
				false,
				[
					'class'         => 'plogins-finder-card__img',
					'fetchpriority' => 'high',
					'loading'       => 'eager',
					'decoding'      => 'async',
				]
			);
		} else {
			echo '<img class="plogins-finder-card__img" src="' . esc_url(wc_placeholder_img_src('woocommerce_single')) . '" alt="" width="600" height="600" loading="eager" decoding="async" />';
		}
		?>
	</a>

	<h3 class="plogins-finder-card__title">
		<a href="<?php echo esc_url((string) $product['url']); ?>"<?php echo $finder_target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed internal attribute string. ?>><?php echo esc_html((string) $product['title']); ?></a>
	</h3>

	<?php if (! empty($product['show_price']) && ! empty($product['price_html'])) : ?>
		<div class="plogins-finder-card__price">
			<?php echo wp_kses_post((string) $product['price_html']); ?>
		</div>
	<?php endif; ?>

	<?php if ($blurb !== '') : ?>
		<p class="plogins-finder-card__blurb"><?php echo esc_html($blurb); ?></p>
	<?php endif; ?>

	<a class="plogins-finder-card__cta" href="<?php echo esc_url((string) $product['url']); ?>"<?php echo $finder_target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed internal attribute string. ?>>
		<?php echo esc_html($finder_cta); ?>
	</a>
</div>
<?php
// phpcs:enable WordPress.WP.GlobalVariablesOverride.Prohibited
