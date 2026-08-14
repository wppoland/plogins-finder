/**
 * Plogins Finder, front-end quiz state machine.
 *
 * Vanilla, no jQuery. Drives step visibility, progress, Back/Restart, state
 * persistence (sessionStorage + URL), and the REST result fetch. Each widget
 * reads its own config from an inline JSON island (`[data-finder-config]`), so
 * it works regardless of script-enqueue timing / page caching and supports
 * several finders on one page.
 *
 * Security: values restored from the URL or sessionStorage are validated
 * against the config allow-list (steps[i]) before use, and are never written to
 * the DOM as markup, they only ever (a) check a matching radio whose value is
 * already in the allow-list, or (b) get sent to the REST endpoint (re-sanitised
 * server-side). The only HTML injected is the recommendation card returned by
 * our own escaped REST template.
 */
(function () {
	'use strict';

	function format(str, values) {
		return String(str).replace(/\{(\w+)\}/g, function (m, k) {
			return Object.prototype.hasOwnProperty.call(values, k) ? values[k] : m;
		});
	}

	function initRoot(root) {
		var configEl = root.querySelector('[data-finder-config]');
		if (!configEl) {
			return;
		}

		var config;
		try {
			config = JSON.parse(configEl.textContent || '{}');
		} catch (e) {
			return;
		}

		var STEPS = Array.isArray(config.steps) ? config.steps : [];
		var I18N = config.i18n || {};
		var total = STEPS.length;
		if (!total) {
			return;
		}

		function t(key, fallback) {
			return typeof I18N[key] === 'string' ? I18N[key] : fallback;
		}

		/** Is `value` a valid option for step `index`? Guards restored state. */
		function isAllowed(index, value) {
			return Array.isArray(STEPS[index]) && STEPS[index].indexOf(value) !== -1;
		}

		var form = root.querySelector('[data-finder-form]');
		var stepEls = Array.prototype.slice.call(root.querySelectorAll('[data-finder-step]'));
		var progressFill = root.querySelector('[data-finder-progress-fill]');
		var progressBar = root.querySelector('[role="progressbar"]');
		var progressText = root.querySelector('[data-finder-progress-text]');
		var resultEl = root.querySelector('[data-finder-result]');
		var footerEl = root.querySelector('[data-finder-footer]');
		var restartBtn = root.querySelector('[data-finder-restart]');

		var answers = [];
		var current = 0;

		function persist() {
			try {
				var params = new URLSearchParams(window.location.search);
				// Pristine load (no answers, no fa in URL), leave the URL untouched.
				if (answers.length || current > 0 || params.has('fa')) {
					params.set('fa', answers.join(','));
					params.set('fs', current === total ? 'r' : String(current));
					window.history.replaceState(null, '', window.location.pathname + '?' + params.toString());
				}
			} catch (e) { /* history/URL unavailable, non-fatal */ }
			try {
				window.sessionStorage.setItem('ploginsFinder', JSON.stringify({ a: answers, s: current }));
			} catch (e) { /* storage blocked, non-fatal */ }
		}

		function restore() {
			var raw = [];
			var step = 0;
			try {
				var params = new URLSearchParams(window.location.search);
				if (params.has('fa')) {
					raw = (params.get('fa') || '').split(',');
					step = params.get('fs') === 'r' ? total : parseInt(params.get('fs') || '0', 10);
				}
			} catch (e) { /* ignore */ }
			if (!raw.length) {
				try {
					var stored = JSON.parse(window.sessionStorage.getItem('ploginsFinder') || 'null');
					if (stored && Array.isArray(stored.a)) {
						raw = stored.a;
						step = parseInt(stored.s, 10) || 0;
					}
				} catch (e) { /* ignore */ }
			}

			// Validate every restored answer against the allow-list. Stop at the
			// first invalid/absent one so we never trust unchecked input.
			answers = [];
			for (var i = 0; i < total; i++) {
				var v = typeof raw[i] === 'string' ? raw[i] : '';
				if (!isAllowed(i, v)) {
					break;
				}
				answers.push(v);
				var input = form.querySelector('[name="finder_step_' + i + '"][value="' + cssEscape(v) + '"]');
				if (input) {
					input.checked = true;
				}
			}

			if (isNaN(step) || step < 0) {
				step = 0;
			}
			current = Math.min(step, answers.length);
			if (answers.length === total && step >= total) {
				current = total;
			}
		}

		function cssEscape(value) {
			if (window.CSS && typeof window.CSS.escape === 'function') {
				return window.CSS.escape(value);
			}
			return String(value).replace(/[^a-zA-Z0-9_-]/g, '\\$&');
		}

		function updateProgress() {
			var shown = Math.min(current + 1, total);
			var pct = Math.round((shown / total) * 100);
			if (progressFill) {
				progressFill.style.width = pct + '%';
			}
			if (progressBar) {
				progressBar.setAttribute('aria-valuenow', String(shown));
			}
			if (progressText) {
				progressText.textContent = format(t('step', 'Step {current} of {total}'), {
					current: shown,
					total: total
				});
			}
		}

		function showStep(index) {
			current = index;
			stepEls.forEach(function (el) {
				var i = parseInt(el.getAttribute('data-finder-step'), 10);
				el.hidden = i !== index;
			});
			if (resultEl) {
				resultEl.hidden = true;
				resultEl.innerHTML = '';
			}
			if (footerEl) {
				footerEl.hidden = true;
			}
			updateProgress();
			persist();

			var active = stepEls[index];
			if (active) {
				var firstInput = active.querySelector('[data-finder-option]');
				if (firstInput) {
					// preventScroll: focusing on initial load must not yank the
					// viewport down to the finder (desktop scrolled past hero).
					firstInput.focus({ preventScroll: true });
				}
			}
		}

		function setResultMessage(message) {
			if (!resultEl) {
				return;
			}
			resultEl.hidden = false;
			resultEl.innerHTML = '';
			var p = document.createElement('p');
			p.className = 'plogins-finder__message';
			p.textContent = message; // textContent, never markup from state.
			resultEl.appendChild(p);
		}

		function showResult() {
			current = total;
			stepEls.forEach(function (el) { el.hidden = true; });
			updateProgress();
			persist();

			if (progressText) {
				progressText.textContent = '';
			}
			setResultMessage(t('loading', 'Finding your match…'));

			var url = config.restUrl + (config.restUrl.indexOf('?') === -1 ? '?' : '&') +
				'answers=' + encodeURIComponent(answers.join(','));

			fetch(url, {
				method: 'GET',
				headers: { 'X-WP-Nonce': config.restNonce || '' },
				credentials: 'same-origin'
			})
				.then(function (r) { return r.ok ? r.json() : Promise.reject(r); })
				.then(function (data) {
					if (data && data.ok && typeof data.html === 'string') {
						// Trusted, server-escaped card HTML from our own endpoint.
						resultEl.hidden = false;
						resultEl.innerHTML = data.html;
						if (footerEl) {
							footerEl.hidden = false;
						}
						if (restartBtn) {
							restartBtn.focus();
						}
					} else {
						setResultMessage((data && data.message) || t('no_match', 'We could not find a match. Please try again.'));
						if (footerEl) {
							footerEl.hidden = false;
						}
					}
				})
				.catch(function () {
					setResultMessage(t('error', 'Something went wrong. Please try again.'));
					if (footerEl) {
						footerEl.hidden = false;
					}
				});
		}

		function choose(index, value) {
			if (!isAllowed(index, value)) {
				return;
			}
			answers = answers.slice(0, index);
			answers[index] = value;

			if (index + 1 >= total) {
				showResult();
			} else {
				showStep(index + 1);
			}
		}

		function restart() {
			answers = [];
			form.reset();
			try {
				window.sessionStorage.removeItem('ploginsFinder');
			} catch (e) { /* ignore */ }
			showStep(0);
		}

		form.addEventListener('change', function (event) {
			var input = event.target.closest('[data-finder-option]');
			if (!input) {
				return;
			}
			var stepEl = input.closest('[data-finder-step]');
			var index = parseInt(stepEl.getAttribute('data-finder-step'), 10);
			choose(index, input.value);
		});

		root.addEventListener('click', function (event) {
			if (event.target.closest('[data-finder-back]')) {
				if (current > 0) {
					showStep(current - 1);
				}
			} else if (event.target.closest('[data-finder-restart]')) {
				restart();
			}
		});

		restore();
		if (current >= total && answers.length === total) {
			showResult();
		} else {
			showStep(current);
		}
	}

	function boot() {
		Array.prototype.forEach.call(document.querySelectorAll('[data-finder-root]'), initRoot);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();
