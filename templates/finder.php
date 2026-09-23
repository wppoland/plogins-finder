<?php
/**
 * Product-finder quiz widget.
 *
 * Rendered server-side; the front script (assets/js/finder.js) drives step
 * visibility, progress, state persistence and the REST result fetch. All steps
 * are present in the DOM (only the first is shown) so the quiz needs no network
 * round-trip to advance. Every value here comes from trusted, sanitised settings.
 *
 * @package Finder
 *
 * @var string                              $title        Quiz heading.
 * @var string                              $intro        Sub-heading copy.
 * @var string                              $accent_color Sanitised hex accent.
 * @var list<array<string, mixed>>          $steps        Ordered steps.
 * @var array<string, string>               $labels       Translated UI strings.
 * @var array<string, mixed>                $config       Front-script config (REST + steps allow-list).
 */

defined('ABSPATH') || exit;

// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited -- template-scope vars injected via extract().

$finder_total = count($steps);
?>
<div class="plogins-finder" data-finder-root
     style="--finder-accent: <?php echo esc_attr($accent_color); ?>;">

	<script type="application/json" data-finder-config><?php
	// JSON-encoded with HEX flags so `<`, `>`, `&`, quotes can never break out of
	// the script element; safe to output as-is.
	echo wp_json_encode($config, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?></script>

	<?php if ($title !== '' || $intro !== '') : ?>
		<header class="plogins-finder__header">
			<?php if ($title !== '') : ?>
				<h2 class="plogins-finder__title"><?php echo esc_html($title); ?></h2>
			<?php endif; ?>
			<?php if ($intro !== '') : ?>
				<p class="plogins-finder__intro"><?php echo esc_html($intro); ?></p>
			<?php endif; ?>
		</header>
	<?php endif; ?>

	<div class="plogins-finder__progress" data-finder-progress>
		<div class="plogins-finder__progress-bar" role="progressbar"
			aria-valuemin="1" aria-valuemax="<?php echo esc_attr((string) $finder_total); ?>"
			aria-valuenow="1"
			aria-label="<?php esc_attr_e('Quiz progress', 'elektilo'); ?>">
			<span class="plogins-finder__progress-fill" data-finder-progress-fill></span>
		</div>
		<p class="plogins-finder__progress-text" data-finder-progress-text aria-live="polite"></p>
	</div>

	<form class="plogins-finder__form" data-finder-form novalidate>
		<?php foreach ($steps as $finder_index => $finder_step) : ?>
			<fieldset class="plogins-finder__step" data-finder-step="<?php echo esc_attr((string) $finder_index); ?>"
				<?php echo $finder_index === 0 ? '' : 'hidden'; ?>>
				<legend class="plogins-finder__question">
					<?php echo esc_html((string) $finder_step['question']); ?>
				</legend>

				<?php if (! empty($finder_step['help'])) : ?>
					<p class="plogins-finder__help"><?php echo esc_html((string) $finder_step['help']); ?></p>
				<?php endif; ?>

				<div class="plogins-finder__options" role="radiogroup"
					aria-label="<?php echo esc_attr((string) $finder_step['question']); ?>">
					<?php foreach ($finder_step['options'] as $finder_opt_i => $finder_option) : ?>
						<?php $finder_id = 'finder-' . (int) $finder_index . '-' . (int) $finder_opt_i; ?>
						<label class="plogins-finder__option" for="<?php echo esc_attr($finder_id); ?>">
							<input type="radio" id="<?php echo esc_attr($finder_id); ?>"
								name="finder_step_<?php echo esc_attr((string) $finder_index); ?>"
								value="<?php echo esc_attr((string) $finder_option['value']); ?>"
								data-finder-option>
							<span class="plogins-finder__option-label">
								<?php echo esc_html((string) $finder_option['label']); ?>
							</span>
						</label>
					<?php endforeach; ?>
				</div>

				<p class="plogins-finder__notice" data-finder-notice hidden>
					<?php echo esc_html($labels['choose'] ?? ''); ?>
				</p>

				<div class="plogins-finder__nav">
					<?php if ($finder_index > 0) : ?>
						<button type="button" class="plogins-finder__btn plogins-finder__btn--ghost"
							data-finder-back>
							<?php echo esc_html($labels['back'] ?? ''); ?>
						</button>
					<?php endif; ?>
				</div>
			</fieldset>
		<?php endforeach; ?>
	</form>

	<div class="plogins-finder__result" data-finder-result role="status" aria-live="polite" aria-atomic="true"
		hidden></div>

	<div class="plogins-finder__footer" data-finder-footer hidden>
		<button type="button" class="plogins-finder__btn plogins-finder__btn--ghost" data-finder-restart>
			<?php echo esc_html($labels['start'] ?? ''); ?>
		</button>
	</div>

	<noscript>
		<p class="plogins-finder__noscript">
			<?php esc_html_e('Please enable JavaScript to use the product finder.', 'elektilo'); ?>
		</p>
	</noscript>
</div>
<?php
// phpcs:enable WordPress.WP.GlobalVariablesOverride.Prohibited
