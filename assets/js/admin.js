/**
 * Finder, admin settings enhancement.
 *
 * Builds the steps/options repeater and the results-map grid. No dependencies.
 * All dynamic text is written with textContent / DOM APIs (never innerHTML from
 * data), so product names and labels can never inject markup.
 */
(function () {
	'use strict';

	var CFG = window.ploginsFinderAdmin || {};
	var OPTION = CFG.option || 'finder_settings';
	var PRODUCTS = CFG.products || {};
	var I18N = CFG.i18n || {};

	function t(key, fallback) {
		return typeof I18N[key] === 'string' ? I18N[key] : fallback;
	}

	function el(tag, attrs, text) {
		var node = document.createElement(tag);
		if (attrs) {
			Object.keys(attrs).forEach(function (k) { node.setAttribute(k, attrs[k]); });
		}
		if (text != null) {
			node.textContent = text;
		}
		return node;
	}

	function slugify(value) {
		return String(value).toLowerCase().replace(/[^a-z0-9_\-]+/g, '-').replace(/^-+|-+$/g, '');
	}

	/* ---- Tooltips: keyboard/click toggle + Escape (hover/focus via CSS) ---- */
	function initTooltips() {
		document.querySelectorAll('.finder-help__toggle').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var tip = document.getElementById(btn.getAttribute('aria-describedby'));
				if (tip) {
					tip.classList.toggle('is-open');
				}
			});
			btn.addEventListener('keydown', function (e) {
				if (e.key === 'Escape') {
					var tip = document.getElementById(btn.getAttribute('aria-describedby'));
					if (tip) {
						tip.classList.remove('is-open');
					}
				}
			});
		});
	}

	/* ---- Steps repeater --------------------------------------------------- */
	function buildOptionRow() {
		var row = el('div', { 'class': 'finder-option-row', 'data-finder-option-row': '' });
		var label = el('input', {
			type: 'text', 'class': 'finder-option-row__label',
			placeholder: t('optionLabel', 'Option label'),
			'aria-label': t('optionLabel', 'Option label'),
			'data-finder-name': '[steps][__STEP__][options][__OPT__][label]'
		});
		var value = el('input', {
			type: 'text', 'class': 'finder-option-row__value',
			placeholder: t('optionValue', 'value'),
			'aria-label': t('optionValue', 'Value (slug)'),
			'data-finder-name': '[steps][__STEP__][options][__OPT__][value]',
			'data-finder-value': ''
		});
		// Auto-fill the slug from the label until the merchant edits it.
		label.addEventListener('input', function () {
			if (!value.dataset.touched) {
				value.value = slugify(label.value);
			}
		});
		value.addEventListener('input', function () { value.dataset.touched = '1'; });

		var remove = el('button', {
			type: 'button', 'class': 'button-link finder-option-row__remove',
			'data-finder-remove-option': '', 'aria-label': t('remove', 'Remove')
		}, '×');

		row.appendChild(label);
		row.appendChild(value);
		row.appendChild(remove);
		return row;
	}

	function buildStep() {
		var step = el('div', { 'class': 'finder-step', 'data-finder-step': '' });

		var head = el('div', { 'class': 'finder-step__head' });
		head.appendChild(el('span', { 'class': 'finder-step__badge', 'data-finder-step-badge': '' }, ''));
		head.appendChild(el('button', {
			type: 'button', 'class': 'button-link finder-step__remove', 'data-finder-remove-step': ''
		}, t('removeStep', 'Remove step')));
		step.appendChild(head);

		[['question', t('question', 'Question')], ['help', t('help', 'Hint (optional)')]].forEach(function (pair) {
			var p = el('p');
			p.appendChild(el('label', { 'class': 'finder-field__label' }, pair[1]));
			p.appendChild(el('input', {
				type: 'text', 'class': 'regular-text',
				'aria-label': pair[1],
				'data-finder-name': '[steps][__STEP__][' + pair[0] + ']'
			}));
			step.appendChild(p);
		});

		var options = el('div', { 'class': 'finder-options', 'data-finder-options': '' });
		options.appendChild(buildOptionRow());
		options.appendChild(buildOptionRow());
		step.appendChild(options);

		step.appendChild(el('button', {
			type: 'button', 'class': 'button button-small', 'data-finder-add-option': ''
		}, t('addOption', 'Add option')));

		return step;
	}

	/**
	 * Recompute every step/option input name from its data-finder-name template.
	 */
	function reindexSteps(container) {
		var steps = container.querySelectorAll('[data-finder-step]');
		Array.prototype.forEach.call(steps, function (stepEl, i) {
			var badge = stepEl.querySelector('[data-finder-step-badge]');
			if (badge) {
				badge.textContent = String(i + 1);
			}
			// Step-level inputs (question/help): direct <p> children.
			stepEl.querySelectorAll(':scope > p > [data-finder-name]').forEach(function (inp) {
				inp.name = OPTION + inp.getAttribute('data-finder-name').replace('__STEP__', i);
			});
			// Option rows.
			stepEl.querySelectorAll('[data-finder-option-row]').forEach(function (row, j) {
				row.querySelectorAll('[data-finder-name]').forEach(function (inp) {
					inp.name = OPTION + inp.getAttribute('data-finder-name')
						.replace('__STEP__', i).replace('__OPT__', j);
				});
			});
		});
	}

	/* ---- Results grid ----------------------------------------------------- */
	function buildProductSelect(name, selected) {
		var select = el('select', {
			'class': 'finder-product-select', name: name,
			'aria-label': t('product', 'Recommended product')
		});
		select.appendChild(el('option', { value: '0' }, t('option', '- Select a product -')));
		Object.keys(PRODUCTS).forEach(function (id) {
			var opt = el('option', { value: id }, PRODUCTS[id]);
			if (String(selected) === String(id)) {
				opt.selected = true;
			}
			select.appendChild(opt);
		});
		return select;
	}

	function buildResultRow(index, match, existing) {
		existing = existing || {};
		var base = OPTION + '[results][' + index + ']';
		var row = el('div', { 'class': 'finder-result-row', 'data-finder-result-row': '', 'data-finder-match': match.join('|') });

		var combo = el('div', { 'class': 'finder-result-row__combo' });
		match.forEach(function (v) {
			combo.appendChild(el('span', { 'class': 'finder-chip' }, v));
			combo.appendChild(el('input', { type: 'hidden', name: base + '[match][]', value: v }));
		});
		row.appendChild(combo);

		var fields = el('div', { 'class': 'finder-result-row__fields' });
		fields.appendChild(buildProductSelect(base + '[product_id]', existing.product_id || 0));
		[['headline', t('headline', 'Headline (optional)')], ['blurb', t('blurb', 'Description (optional)')], ['cta_label', t('cta', 'Button label (optional)')]].forEach(function (pair) {
			var inp = el('input', { type: 'text', placeholder: pair[1], 'aria-label': pair[1], name: base + '[' + pair[0] + ']' });
			if (existing[pair[0]]) {
				inp.value = existing[pair[0]];
			}
			fields.appendChild(inp);
		});
		row.appendChild(fields);
		return row;
	}

	/** Cartesian product of the per-step option-value lists. */
	function cartesian(lists) {
		return lists.reduce(function (acc, list) {
			var out = [];
			acc.forEach(function (prefix) {
				list.forEach(function (v) { out.push(prefix.concat([v])); });
			});
			return out;
		}, [[]]);
	}

	function readStepValues(stepsContainer) {
		var lists = [];
		stepsContainer.querySelectorAll('[data-finder-step]').forEach(function (stepEl) {
			var values = [];
			stepEl.querySelectorAll('[data-finder-value]').forEach(function (inp) {
				var v = slugify(inp.value);
				if (v && values.indexOf(v) === -1) {
					values.push(v);
				}
			});
			if (values.length) {
				lists.push(values);
			}
		});
		return lists;
	}

	function readExistingResults(resultsContainer) {
		var map = {};
		resultsContainer.querySelectorAll('[data-finder-result-row]').forEach(function (row) {
			var key = row.getAttribute('data-finder-match') || '';
			var select = row.querySelector('select');
			var texts = row.querySelectorAll('.finder-result-row__fields input[type="text"]');
			map[key] = {
				product_id: select ? select.value : 0,
				headline: texts[0] ? texts[0].value : '',
				blurb: texts[1] ? texts[1].value : '',
				cta_label: texts[2] ? texts[2].value : ''
			};
		});
		return map;
	}

	function updateCoverage(resultsContainer, callout) {
		if (!callout) {
			return;
		}
		var rows = resultsContainer.querySelectorAll('[data-finder-result-row]');
		var missing = 0;
		rows.forEach(function (row) {
			var select = row.querySelector('select');
			if (!select || select.value === '0' || select.value === '') {
				missing++;
			}
		});
		callout.classList.remove('finder-callout--ok', 'finder-callout--warn');
		if (!rows.length) {
			callout.textContent = '';
			return;
		}
		if (missing === 0) {
			callout.textContent = t('coverageDone', 'All combinations have a product. ✓');
			callout.classList.add('finder-callout--ok');
		} else {
			callout.textContent = t('coverageMissing', '{count} combination(s) still need a product.').replace('{count}', missing);
			callout.classList.add('finder-callout--warn');
		}
	}

	function generate(stepsContainer, resultsContainer, callout) {
		var lists = readStepValues(stepsContainer);
		if (!lists.length) {
			window.alert(t('needSteps', 'Define at least one step with options first.'));
			return;
		}
		var existing = readExistingResults(resultsContainer);
		var combos = cartesian(lists);
		resultsContainer.textContent = '';
		combos.forEach(function (combo, i) {
			resultsContainer.appendChild(buildResultRow(i, combo, existing[combo.join('|')]));
		});
		updateCoverage(resultsContainer, callout);
	}

	/* ---- Wiring ----------------------------------------------------------- */
	function init() {
		initTooltips();

		var stepsContainer = document.querySelector('[data-finder-steps]');
		var resultsContainer = document.querySelector('[data-finder-results]');
		var callout = document.querySelector('[data-finder-coverage]');

		if (stepsContainer) {
			var addStep = document.querySelector('[data-finder-add-step]');
			if (addStep) {
				addStep.addEventListener('click', function () {
					stepsContainer.appendChild(buildStep());
					reindexSteps(stepsContainer);
				});
			}

			stepsContainer.addEventListener('click', function (event) {
				if (event.target.closest('[data-finder-remove-step]')) {
					var step = event.target.closest('[data-finder-step]');
					if (step) {
						step.remove();
						reindexSteps(stepsContainer);
					}
				} else if (event.target.closest('[data-finder-add-option]')) {
					var options = event.target.closest('[data-finder-step]').querySelector('[data-finder-options]');
					options.appendChild(buildOptionRow());
					reindexSteps(stepsContainer);
				} else if (event.target.closest('[data-finder-remove-option]')) {
					var row = event.target.closest('[data-finder-option-row]');
					if (row) {
						row.remove();
						reindexSteps(stepsContainer);
					}
				}
			});

			// Ensure server-rendered rows carry correct indices from the start.
			reindexSteps(stepsContainer);
		}

		if (resultsContainer) {
			var generateBtn = document.querySelector('[data-finder-generate]');
			if (generateBtn) {
				generateBtn.addEventListener('click', function () {
					generate(stepsContainer, resultsContainer, callout);
				});
			}
			resultsContainer.addEventListener('change', function () {
				updateCoverage(resultsContainer, callout);
			});
			updateCoverage(resultsContainer, callout);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
