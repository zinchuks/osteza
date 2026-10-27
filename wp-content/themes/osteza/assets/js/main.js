(() => {
	'use strict';

	const cfg = window.OSTEZA || {};
	const $ = (sel, root = document) => root.querySelector(sel);
	const $$ = (sel, root = document) => [...root.querySelectorAll(sel)];

	/* Compact header with a shadow once the page is scrolled. The header gets
	   shorter, which moves the page up, so it turns back only well below the
	   threshold: a single cut-off would flip it back and forth near the top. */
	const header = $('[data-header]');
	if (header) {
		const onScroll = () => {
			const y = window.scrollY;
			if (y > 48) header.classList.add('on');
			else if (y < 8) header.classList.remove('on');
		};
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
	}

	/* Back to top, once the page has been scrolled down a screen or so. */
	const totop = $('[data-totop]');
	if (totop) {
		const onScrollTop = () => totop.classList.toggle('on', window.scrollY > Math.min(800, window.innerHeight));
		window.addEventListener('scroll', onScrollTop, { passive: true });
		onScrollTop();
		totop.addEventListener('click', () => {
			const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
			window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
		});
	}

	/* Mega menu and language dropdown: one open at a time. */
	const toggles = $$('[data-toggle]');
	const closeAll = (except) => {
		for (const btn of toggles) {
			if (btn === except) continue;
			btn.setAttribute('aria-expanded', 'false');
			document.getElementById(btn.dataset.toggle).hidden = true;
		}
	};
	for (const btn of toggles) {
		btn.addEventListener('click', () => {
			const open = btn.getAttribute('aria-expanded') !== 'true';
			closeAll(btn);
			btn.setAttribute('aria-expanded', String(open));
			document.getElementById(btn.dataset.toggle).hidden = !open;
		});
	}
	document.addEventListener('click', (ev) => {
		if (ev.target.closest('[data-close]') || !ev.target.closest('[data-toggle], #mega, #langs')) closeAll();
	});

	/* Mobile menu. */
	const panel = $('#mpanel');
	const burger = $('[data-nav-open]');
	const navBackground = [...document.querySelectorAll('.os > *')].filter((el) => el !== panel && el.tagName !== 'SCRIPT');
	const navInert = new Map();
	const setNav = (open) => {
		for (const el of navBackground) {
			if (open) { navInert.set(el, el.inert); el.inert = true; }
			else { el.inert = navInert.get(el) || false; }
		}
		panel.hidden = !open;
		burger.setAttribute('aria-expanded', String(open));
		document.documentElement.classList.toggle('nav-open', open);
		if (open) $('[data-nav-close]', panel).focus(); else burger.focus();
	};
	if (panel && burger) {
		burger.addEventListener('click', () => setNav(true));
		for (const el of $$('[data-nav-close]', panel)) el.addEventListener('click', () => setNav(false));
	}
	document.addEventListener('keydown', (ev) => {
		if (ev.key === 'Tab' && panel && !panel.hidden) {
			const items = $$('a[href], button, input, select, textarea, summary, [tabindex="0"]', panel).filter((el) => !el.disabled && el.getClientRects().length);
			const first = items[0], last = items[items.length - 1];
			if (ev.shiftKey && document.activeElement === first) { ev.preventDefault(); last.focus(); }
			else if (!ev.shiftKey && document.activeElement === last) { ev.preventDefault(); first.focus(); }
		}
		if (ev.key !== 'Escape') return;
		closeAll();
		if (panel && !panel.hidden) setNav(false);
	});

	for (const sel of $$('[data-lang-select]')) {
		sel.addEventListener('change', () => { window.location.href = sel.value; });
	}

	/* Services catalogue: filter the list as the visitor types. */
	const finder = $('[data-catalog-search]');
	if (finder) {
		const none = $('[data-catalog-none]');
		finder.addEventListener('submit', (ev) => ev.preventDefault());
		$('input', finder).addEventListener('input', (ev) => {
			const q = ev.target.value.trim().toLowerCase();
			let total = 0;
			for (const group of $$('[data-group]')) {
				let shown = 0;
				for (const item of $$('[data-item]', group)) {
					const match = !q || item.textContent.toLowerCase().includes(q);
					item.hidden = !match;
					if (match) shown++;
				}
				group.hidden = !shown;
				$(`[data-count="${group.id}"]`).textContent = shown;
				total += shown;
			}
			none.hidden = total > 0;
			$('[data-query]', none).textContent = ev.target.value.trim();
		});
	}

	/* Zones map: swap the schematic SVG for a real map with a dot and a label per town. */
	for (const el of $$('[data-map]')) {
		if (!window.L) break;
		const points = JSON.parse(el.dataset.map);
		const interactive = 'selected' in el.dataset;
		// On a narrow map the town names would overlap, so they show on hover or tap instead.
		const labelled = el.offsetWidth >= 420;
		const dots = {};

		el.textContent = '';
		el.parentNode.classList.add('zmapon');
		const map = L.map(el, { scrollWheelZoom: false, dragging: !L.Browser.mobile, attributionControl: true });
		map.attributionControl.setPrefix(false);
		L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 16,
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a>',
		}).addTo(map);

		for (const p of points) {
			const dot = L.circleMarker(p.geo, { radius: 6, color: '#F8F5F3', weight: 0, fillColor: '#1D1838', fillOpacity: 1 }).addTo(map);
			dot.bindTooltip(p.name, { permanent: labelled, direction: 'left', offset: [-8, 0], className: 'zlabel' });
			if (interactive) {
				dot.on('click', () => $(`[data-zone="${p.slug}"]`)?.dispatchEvent(new Event('mouseenter')));
			}
			dots[p.slug] = dot;
		}
		// Extra room on the left for the labels, which sit to the left of their dots.
		map.fitBounds(L.latLngBounds(points.map((p) => p.geo)), { paddingTopLeft: [labelled ? 130 : 30, 30], paddingBottomRight: [30, 30] });

		el.selectZone = (slug) => {
			for (const [key, dot] of Object.entries(dots)) {
				const on = key === slug;
				dot.setStyle({ weight: on ? 4 : 0 }).setRadius(on ? 11 : 6);
				if (labelled) {
					dot.getTooltip().getElement().classList.toggle('on', on);
				} else if (on) {
					dot.openTooltip();
				} else {
					dot.closeTooltip();
				}
				if (on) dot.bringToFront();
			}
		};
		if (interactive) el.selectZone(el.dataset.selected);
	}

	/* Zones index: hovering or focusing a town selects it on the map and in the summary. */
	const zsel = $('[data-zone-selected]');
	if (zsel) {
		const zmap = $('[data-map][data-selected]');
		const pick = (card) => {
			for (const el of $$('[data-zone]')) el.classList.toggle('on', el === card);
			zmap?.selectZone?.(card.dataset.zone);
			$('[data-zone-name]', zsel).textContent = card.dataset.name;
			$('[data-zone-area]', zsel).textContent = card.dataset.area;
			const link = $('[data-zone-link]', zsel);
			link.href = card.href;
			link.hidden = card.getAttribute('href') === '#';
		};
		for (const card of $$('[data-zone]')) {
			card.addEventListener('mouseenter', () => pick(card));
			card.addEventListener('focus', () => pick(card));
		}
	}

	/* Projects archive: filter the cards by group of services. */
	const pchips = $$('[data-project-filter]');
	for (const chip of pchips) {
		chip.addEventListener('click', () => {
			const group = chip.dataset.projectFilter;
			for (const c of pchips) c.setAttribute('aria-pressed', String(c === chip));
			for (const card of $$('[data-project-group]')) card.hidden = Boolean(group) && card.dataset.projectGroup !== group;
		});
	}

	/* Project case study: before/after slider. */
	for (const wrap of $$('[data-ba]')) {
		const range = $('input[type=range]', wrap.parentNode);
		const update = () => wrap.style.setProperty('--split', `${range.value}%`);
		range.addEventListener('input', update);
		update();
	}

	/* Article: copy its link. */
	for (const btn of $$('[data-copy-link]')) {
		btn.addEventListener('click', async () => {
			try {
				await navigator.clipboard.writeText(window.location.href);
				$('span', btn).textContent = cfg.linked;
			} catch { /* no clipboard: nothing to do */ }
		});
	}

	/* Services carousel. */
	const track = $('#track');
	if (track) {
		const bar = $('[data-track-progress]');
		const onTrack = () => {
			const max = track.scrollWidth - track.clientWidth;
			bar.style.width = `${max > 0 ? Math.round(30 + 70 * track.scrollLeft / max) : 100}%`;
		};
		track.addEventListener('scroll', onTrack, { passive: true });
		onTrack();
		for (const btn of $$('[data-track-dir]')) {
			btn.addEventListener('click', () => {
				const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
				track.scrollBy({ left: Number(btn.dataset.trackDir) * track.clientWidth * 0.75, behavior: reduce ? 'auto' : 'smooth' });
			});
		}
	}

	/* Contact form: validates, then hands the prepared message to WhatsApp or Telegram. */
	const form = $('[data-form]');
	if (form) {
		const keys = ['name', 'phone', 'town', 'service', 'desc'];
		const summary = $('[data-form-errors]', form);
		const copyBtn = $('[data-copy]', form);
		const copyFail = $('[data-copy-fail]', form);
		const value = (k) => form.elements[k].value.trim();

		const validate = () => {
			const e = {};
			if (!value('name')) e.name = cfg.errors.name;
			if (value('phone').replace(/\D/g, '').length < 9) e.phone = cfg.errors.phone;
			if (!value('town')) e.town = cfg.errors.town;
			if (!value('service')) e.service = cfg.errors.service;
			if (value('desc').length < 10) e.desc = cfg.errors.desc;
			return e;
		};

		const showErrors = (e) => {
			const bad = Object.keys(e);
			for (const k of keys) {
				const msg = $(`#e-${k}`, form);
				form.elements[k].setAttribute('aria-invalid', String(Boolean(e[k])));
				msg.textContent = e[k] || '';
				msg.hidden = !e[k];
			}
			summary.hidden = !bad.length;
			summary.textContent = '';
			if (bad.length) {
				const strong = document.createElement('strong');
				strong.textContent = cfg.review.replace('{n}', bad.length);
				summary.append(strong, ` ${bad.map((k) => cfg.fields[k]).join(', ')}`);
			}
			return !bad.length;
		};

		const setState = (state) => {
			for (const el of $$('[data-state]', form)) el.hidden = el.dataset.state !== state;
		};

		const go = (kind) => {
			if (!showErrors(validate())) { setState('idle'); return; }
			const msg = cfg.message.replace(/\{(\w+)\}/g, (m, k) => value(k));
			for (const el of $$('[data-msg]', form)) el.textContent = msg;
			$('[data-wa-link]', form).href = `${cfg.waBase}?text=${encodeURIComponent(msg)}`;
			copyBtn.textContent = cfg.copy;
			copyFail.hidden = true;
			setState(kind);
		};

		form.addEventListener('submit', (ev) => { ev.preventDefault(); go('wa'); });
		$('[data-go="tg"]', form).addEventListener('click', () => go('tg'));
		for (const btn of $$('[data-edit]', form)) btn.addEventListener('click', () => setState('idle'));
		copyBtn.addEventListener('click', async () => {
			try {
				await navigator.clipboard.writeText($('[data-msg]', form).textContent);
				copyBtn.textContent = cfg.copied;
			} catch {
				copyFail.hidden = false;
			}
		});
		for (const btn of $$('[data-handoff]', form)) btn.disabled = false;
	}

	/* Live chat: the AI assistant answers; a request form (contact, place, channel, photos) goes to the team. */
	const chat = $('[data-chat]');
	if (chat && cfg.chat) {
		const cc = cfg.chat;
		const box = $('#chatbox', chat);
		const openBtn = $('[data-chat-open]', chat);
		const log = $('[data-chat-log]', chat);
		const chatForm = $('[data-chat-form]', chat);
		const input = $('textarea', chatForm);
		const sendBtn = $('button', chatForm);
		const humanBtn = $('[data-chat-human]', chat);
		const lead = $('[data-chat-lead]', chat);
		const field = (name) => lead.elements[name];
		const leadErr = $('[data-chat-err]', lead);
		const leadBtn = $('[data-chat-submit]', lead);
		const thumbs = $('[data-chat-thumbs]', lead);
		const photoInput = $('[data-chat-photos]', lead);
		const geoBtn = $('[data-chat-geo]', lead);
		const geoState = $('[data-chat-geo-state]', lead);
		const KEY = 'osteza_chat';
		const MAX_PHOTOS = 5;
		let conv = null;
		let last = 0;
		let busy = false;
		let loaded = false;
		let photos = [];

		try { conv = JSON.parse(window.localStorage.getItem(KEY)); } catch { conv = null; }
		const save = () => {
			try { window.localStorage.setItem(KEY, JSON.stringify(conv)); } catch { /* private mode: the chat lasts for the page */ }
		};

		const scroll = () => { log.scrollTop = log.scrollHeight; };

		/* Message text with its links clickable; nothing is parsed as HTML. Messages go above the open form. */
		const bubble = (from, text, pending = false) => {
			const el = document.createElement('div');
			el.className = `chat-m chat-${from}`;
			if (pending) el.dataset.pending = '';
			text.split(/(https?:\/\/[^\s<>()]+[^\s<>().,;:!?])/).forEach((part, i) => {
				if (i % 2) {
					const a = document.createElement('a');
					a.href = part;
					a.textContent = part.replace(/^https?:\/\/(www\.)?/, '');
					el.append(a);
				} else if (part) {
					el.append(part);
				}
			});
			log.insertBefore(el, lead.hidden ? null : lead);
			scroll();
			return el;
		};

		const typing = (on) => {
			let el = $('.chat-typing', log);
			if (on && !el) {
				el = document.createElement('div');
				el.className = 'chat-m chat-ai chat-typing';
				el.innerHTML = '<i></i><i></i><i></i>';
				log.append(el);
				scroll();
			} else if (!on && el) {
				el.remove();
			}
		};

		const showLead = (prefill = {}) => {
			for (const k of ['name', 'phone', 'town', 'request']) {
				if (prefill[k] && !field(k).value) field(k).value = prefill[k];
			}
			leadErr.hidden = true;
			lead.hidden = false;
			humanBtn.hidden = true;
			log.append(lead);
			scroll();
			field(['name', 'phone', 'town', 'request'].find((k) => !field(k).value) || 'request').focus();
		};
		const hideLead = (sent) => {
			lead.hidden = true;
			humanBtn.hidden = sent;
			input.focus();
		};

		const apply = (data) => {
			for (const el of $$('[data-pending]', log)) el.remove();
			for (const m of data.messages || []) {
				if (m.n <= last) continue;
				last = m.n;
				bubble(m.from, m.text);
			}
			if (data.sent) hideLead(true);
			else if (data.form) showLead(data.form);
		};

		const params = () => ({ id: conv?.id ?? 0, after: last, lang: cc.lang, page: window.location.href });
		const call = async (path, opts = {}) => {
			const headers = { ...opts.headers, ...(conv?.token ? { 'X-OSTEZA-Token': conv.token } : {}) };
			const res = await fetch(cc.api + path, { ...opts, headers, cache: 'no-store' });
			const data = await res.json();
			if (!res.ok && data.data?.token && data.data?.id) { conv = { id: data.data.id, token: data.data.token }; save(); }
			if (!res.ok) throw Object.assign(new Error(data.message || ''), { status: res.status, data: data.data || {} });
			if (data.token) { conv = { id: data.id, token: data.token }; save(); }
			return data;
		};

		const load = async () => {
			if (loaded) return;
			loaded = true;
			bubble('ai', cc.greeting);
			if (!conv) return;
			try {
				apply(await call(`?${new URLSearchParams(params())}`));
			} catch (err) {
				if (err.status === 404) { conv = null; save(); }
			}
		};

		const setOpen = (open) => {
			box.hidden = !open;
			openBtn.setAttribute('aria-expanded', String(open));
			chat.classList.toggle('is-open', open);
			document.documentElement.classList.toggle('chat-open', open);
			if (open) { load(); input.focus(); } else if (openBtn.offsetParent) { openBtn.focus(); }
		};

		/* Once the visitor has read a while or scrolled past the first screen, a note by
		   the button says the chat is live. Opening the chat or closing the note hides it
		   for the rest of the visit (the browser tab's session). */
		const tease = $('[data-chat-tease]', chat);
		const SEEN_KEY = 'osteza_chat_seen';
		const seen = () => {
			try { return window.sessionStorage.getItem(SEEN_KEY) === '1'; } catch { return false; }
		};
		const hideTease = () => {
			tease.hidden = true;
			try { window.sessionStorage.setItem(SEEN_KEY, '1'); } catch { /* private mode */ }
		};
		if (!seen()) {
			const showTease = () => {
				clearTimeout(timer);
				window.removeEventListener('scroll', onScroll);
				if (box.hidden && !seen()) tease.hidden = false;
			};
			const onScroll = () => { if (window.scrollY > window.innerHeight * 0.8) showTease(); };
			const timer = setTimeout(showTease, 25000);
			window.addEventListener('scroll', onScroll, { passive: true });
		}
		$('[data-chat-tease-open]', tease).addEventListener('click', () => { hideTease(); setOpen(true); });
		$('[data-chat-tease-close]', tease).addEventListener('click', hideTease);

		openBtn.addEventListener('click', () => { hideTease(); setOpen(box.hidden); });
		for (const btn of $$('[data-chat-trigger]')) btn.addEventListener('click', () => { hideTease(); setOpen(true); });
		$('[data-chat-close]', chat).addEventListener('click', () => setOpen(false));
		box.addEventListener('keydown', (ev) => { if (ev.key === 'Escape') setOpen(false); });

		const send = async () => {
			const text = input.value.trim();
			if (!text || busy) return;
			busy = true;
			sendBtn.disabled = true;
			input.value = '';
			input.style.height = '';
			bubble('user', text, true);
			typing(true);
			try {
				apply(await call('', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ ...params(), text }) }));
			} catch (err) {
				bubble('note', err.message || cc.error);
				if (!input.value) input.value = text;
				for (const el of $$('[data-pending]', log)) { el.removeAttribute('data-pending'); el.classList.add('chat-failed'); }
			}
			typing(false);
			busy = false;
			sendBtn.disabled = false;
			if (lead.hidden) input.focus();
		};

		chatForm.addEventListener('submit', (ev) => { ev.preventDefault(); send(); });
		input.addEventListener('keydown', (ev) => {
			if (ev.key === 'Enter' && !ev.shiftKey && !ev.isComposing) { ev.preventDefault(); send(); }
		});
		input.addEventListener('input', () => {
			input.style.height = '';
			input.style.height = `${Math.min(input.scrollHeight, 120)}px`;
		});
		humanBtn.addEventListener('click', () => showLead());
		$('[data-chat-back]', lead).addEventListener('click', () => hideLead(false));

		/* Telegram username only when Telegram is the channel. */
		for (const radio of $$('[name="channel"]', lead)) {
			radio.addEventListener('change', () => { $('[data-chat-tg]', lead).hidden = field('channel').value !== 'telegram'; });
		}

		/* The device location, if the visitor agrees: sent as coordinates with the request. */
		if (!navigator.geolocation) geoBtn.parentNode.hidden = true;
		geoBtn.addEventListener('click', () => {
			geoState.textContent = cc.geoWait;
			navigator.geolocation.getCurrentPosition(
				({ coords }) => {
					field('geo').value = `${coords.latitude.toFixed(5)},${coords.longitude.toFixed(5)}`;
					geoState.textContent = cc.geoOk;
				},
				() => {
					field('geo').value = '';
					geoState.textContent = cc.geoFail;
				},
				{ enableHighAccuracy: true, timeout: 15000, maximumAge: 60000 }
			);
		});

		/* Photos are resized in the browser (longest side 1600 px, JPEG) before they are sent. */
		const shrink = (file) => new Promise((resolve) => {
			const img = new Image();
			const url = URL.createObjectURL(file);
			img.onload = () => {
				const k = Math.min(1, 1600 / Math.max(img.naturalWidth, img.naturalHeight));
				const c = document.createElement('canvas');
				c.width = Math.round(img.naturalWidth * k);
				c.height = Math.round(img.naturalHeight * k);
				c.getContext('2d').drawImage(img, 0, 0, c.width, c.height);
				URL.revokeObjectURL(url);
				c.toBlob((blob) => resolve(blob ? { blob, url: URL.createObjectURL(blob) } : null), 'image/jpeg', 0.82);
			};
			img.onerror = () => { URL.revokeObjectURL(url); resolve(null); };
			img.src = url;
		});
		const drawThumbs = () => {
			thumbs.textContent = '';
			photos.forEach((p, i) => {
				const b = document.createElement('button');
				b.type = 'button';
				b.className = 'chat-thumb';
				b.style.backgroundImage = `url(${p.url})`;
				b.setAttribute('aria-label', cc.removePhoto);
				b.addEventListener('click', () => { URL.revokeObjectURL(p.url); photos.splice(i, 1); drawThumbs(); });
				thumbs.append(b);
			});
		};
		photoInput.addEventListener('change', async () => {
			const files = [...photoInput.files].slice(0, Math.max(0, MAX_PHOTOS - photos.length));
			photoInput.value = '';
			photos.push(...(await Promise.all(files.map(shrink))).filter(Boolean));
			drawThumbs();
		});

		lead.addEventListener('submit', async (ev) => {
			ev.preventDefault();
			for (const el of $$('.is-bad', lead)) el.classList.remove('is-bad');
			leadErr.hidden = true;
			leadBtn.disabled = true;
			leadBtn.textContent = cc.sending;
			const fd = new FormData();
			for (const [k, v] of Object.entries(params())) fd.append(k, v);
			for (const k of ['name', 'phone', 'town', 'geo', 'channel', 'telegram', 'request']) fd.append(k, field(k).value);
			photos.forEach((p, i) => fd.append('photos[]', p.blob, `photo${i}.jpg`));
			try {
				const data = await call('/request', { method: 'POST', body: fd });
				for (const p of photos) URL.revokeObjectURL(p.url);
				photos = [];
				drawThumbs();
				lead.reset();
				geoState.textContent = '';
				apply(data);
			} catch (err) {
				leadErr.textContent = err.message || cc.error;
				leadErr.hidden = false;
				for (const k of Object.keys(err.data?.fields || {})) field(k)?.closest?.('label')?.classList.add('is-bad');
			}
			leadBtn.disabled = false;
			leadBtn.textContent = cc.submit;
		});
	}
})();
