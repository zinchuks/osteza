(function () {
	'use strict';

	var cfg = window.OSTEZA || {};
	var $ = function (sel, root) { return (root || document).querySelector(sel); };
	var $$ = function (sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); };

	/* Header shadow once the page is scrolled. */
	var header = $('[data-header]');
	if (header) {
		var onScroll = function () { header.classList.toggle('on', window.scrollY > 24); };
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
	}

	/* Mega menu and language dropdown: one open at a time. */
	var toggles = $$('[data-toggle]');
	var closeAll = function (except) {
		toggles.forEach(function (btn) {
			if (btn === except) return;
			btn.setAttribute('aria-expanded', 'false');
			document.getElementById(btn.dataset.toggle).hidden = true;
		});
	};
	toggles.forEach(function (btn) {
		btn.addEventListener('click', function () {
			var open = btn.getAttribute('aria-expanded') !== 'true';
			closeAll(btn);
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
			document.getElementById(btn.dataset.toggle).hidden = !open;
		});
	});
	document.addEventListener('click', function (ev) {
		if (ev.target.closest('[data-close]') || !ev.target.closest('[data-toggle], #mega, #langs')) closeAll();
	});

	/* Mobile menu. */
	var panel = $('#mpanel');
	var burger = $('[data-nav-open]');
	var setNav = function (open) {
		panel.hidden = !open;
		burger.setAttribute('aria-expanded', open ? 'true' : 'false');
		document.documentElement.classList.toggle('nav-open', open);
		if (open) $('[data-nav-close]', panel).focus(); else burger.focus();
	};
	if (panel && burger) {
		burger.addEventListener('click', function () { setNav(true); });
		$$('[data-nav-close]', panel).forEach(function (el) {
			el.addEventListener('click', function () { setNav(false); });
		});
	}
	document.addEventListener('keydown', function (ev) {
		if (ev.key !== 'Escape') return;
		closeAll();
		if (panel && !panel.hidden) setNav(false);
	});

	$$('[data-lang-select]').forEach(function (sel) {
		sel.addEventListener('change', function () { window.location.href = sel.value; });
	});

	/* Services carousel. */
	var track = $('#track');
	if (track) {
		var bar = $('[data-track-progress]');
		var onTrack = function () {
			var max = track.scrollWidth - track.clientWidth;
			bar.style.width = (max > 0 ? Math.round(30 + 70 * track.scrollLeft / max) : 100) + '%';
		};
		track.addEventListener('scroll', onTrack, { passive: true });
		onTrack();
		$$('[data-track-dir]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
				track.scrollBy({ left: Number(btn.dataset.trackDir) * track.clientWidth * 0.75, behavior: reduce ? 'auto' : 'smooth' });
			});
		});
	}

	/* Contact form: validates, then hands the prepared message to WhatsApp or Telegram. */
	var form = $('[data-form]');
	if (form) {
		var keys = ['name', 'phone', 'town', 'service', 'desc'];
		var summary = $('[data-form-errors]', form);
		var copyBtn = $('[data-copy]', form);
		var copyFail = $('[data-copy-fail]', form);
		var value = function (k) { return form.elements[k].value.trim(); };

		var validate = function () {
			var e = {};
			if (!value('name')) e.name = cfg.errors.name;
			if (value('phone').replace(/\D/g, '').length < 9) e.phone = cfg.errors.phone;
			if (!value('town')) e.town = cfg.errors.town;
			if (!value('service')) e.service = cfg.errors.service;
			if (value('desc').length < 10) e.desc = cfg.errors.desc;
			return e;
		};

		var showErrors = function (e) {
			var bad = Object.keys(e);
			keys.forEach(function (k) {
				var field = form.elements[k];
				var msg = $('#e-' + k, form);
				field.setAttribute('aria-invalid', e[k] ? 'true' : 'false');
				msg.textContent = e[k] || '';
				msg.hidden = !e[k];
			});
			summary.hidden = !bad.length;
			summary.textContent = '';
			if (bad.length) {
				var strong = document.createElement('strong');
				strong.textContent = cfg.review.replace('{n}', bad.length);
				summary.append(strong, ' ' + bad.map(function (k) { return cfg.fields[k]; }).join(', '));
			}
			return !bad.length;
		};

		var setState = function (state) {
			$$('[data-state]', form).forEach(function (el) { el.hidden = el.dataset.state !== state; });
		};

		var go = function (kind) {
			if (!showErrors(validate())) { setState('idle'); return; }
			var msg = cfg.message.replace(/\{(\w+)\}/g, function (m, k) { return value(k); });
			$$('[data-msg]', form).forEach(function (el) { el.textContent = msg; });
			$('[data-wa-link]', form).href = cfg.waBase + '?text=' + encodeURIComponent(msg);
			copyBtn.textContent = cfg.copy;
			copyFail.hidden = true;
			setState(kind);
		};

		form.addEventListener('submit', function (ev) { ev.preventDefault(); go('wa'); });
		$('[data-go="tg"]', form).addEventListener('click', function () { go('tg'); });
		$$('[data-edit]', form).forEach(function (btn) {
			btn.addEventListener('click', function () { setState('idle'); });
		});
		copyBtn.addEventListener('click', function () {
			var failed = function () { copyFail.hidden = false; };
			try {
				navigator.clipboard.writeText($('[data-msg]', form).textContent).then(function () { copyBtn.textContent = cfg.copied; }, failed);
			} catch (err) { failed(); }
		});
	}
})();
