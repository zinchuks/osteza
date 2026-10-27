/**
 * OSTEZA blocks in the editor.
 *
 * One generic editor for every section defined in inc/blocks.php (passed here as
 * window.OSTEZA_EDITOR): the canvas shows the section as the site renders it,
 * and the sidebar holds its fields. No build step: plain wp.element calls.
 */
((wp, cfg) => {
	'use strict';
	if (!wp?.blocks || !cfg) return;

	const el = wp.element.createElement;
	const be = wp.blockEditor;
	const c = wp.components;
	const SSR = wp.serverSideRender;
	const t = cfg.i18n;
	const REF = ['page', 'link', 'zone', 'group'];
	const CHOICE = ['select', 'art', 'icon', ...REF];

	const postId = () => wp.data.select('core/editor')?.getCurrentPostId() ?? 0;

	const choices = (spec) => {
		if (spec.type === 'select') {
			return Object.entries(spec.options).map(([value, label]) => ({ value, label }));
		}
		const empty = REF.includes(spec.type) ? 0 : '';
		return [{ value: empty, label: t.none }, ...(cfg.options[spec.type] || [])];
	};

	const labelOf = (spec, value) => {
		const found = choices(spec).find((o) => String(o.value) === String(value));
		return found && value ? found.label : '';
	};

	const imageField = (key, spec, value, set) =>
		el(c.BaseControl, { key, label: spec.label, __nextHasNoMarginBottom: true },
			el('div', { className: 'osteza-image' },
				el(be.MediaUploadCheck, null,
					el(be.MediaUpload, {
						allowedTypes: ['image'],
						value,
						onSelect: (media) => set(media.id),
						render: ({ open }) => el(c.Button, { variant: 'secondary', onClick: open }, value ? `${t.image} (#${value})` : t.image),
					})
				),
				value ? el(c.Button, { variant: 'link', isDestructive: true, onClick: () => set(0) }, t.clear) : null
			)
		);

	const itemTitle = (spec, item, i) => {
		for (const [k, f] of Object.entries(spec.fields)) {
			const v = item[k];
			if (!v) continue;
			if (f.type === 'text' || f.type === 'textarea') return String(v).slice(0, 48);
			if (REF.includes(f.type)) return labelOf(f, v);
		}
		return `${t.item} ${i + 1}`;
	};

	const listField = (key, spec, value, set) => {
		const items = Array.isArray(value) ? value : [];
		const change = (i, k, v) => set(items.map((item, n) => (n === i ? { ...item, [k]: v } : item)));
		const move = (i, d) => {
			const j = i + d;
			if (j < 0 || j >= items.length) return;
			const next = [...items];
			[next[i], next[j]] = [next[j], next[i]];
			set(next);
		};
		const remove = (i) => set(items.filter((_, n) => n !== i));
		const add = () => set([...items, Object.fromEntries(Object.entries(spec.fields).map(([k, f]) => [k, f.default]))]);

		return el(c.BaseControl, { key, label: spec.label, className: 'osteza-list', __nextHasNoMarginBottom: true },
			items.map((item, i) =>
				el(c.PanelBody, { key: i, title: itemTitle(spec, item, i), initialOpen: false, className: 'osteza-item' },
					Object.entries(spec.fields).map(([k, f]) => field(k, f, item[k], (v) => change(i, k, v))),
					el('div', { className: 'osteza-item-tools' },
						el(c.Button, { size: 'small', variant: 'secondary', disabled: i === 0, onClick: () => move(i, -1) }, `↑ ${t.up}`),
						el(c.Button, { size: 'small', variant: 'secondary', disabled: i === items.length - 1, onClick: () => move(i, 1) }, `↓ ${t.down}`),
						el(c.Button, { size: 'small', variant: 'link', isDestructive: true, onClick: () => remove(i) }, t.remove)
					)
				)
			),
			el(c.Button, { variant: 'secondary', className: 'osteza-add', onClick: add }, `+ ${t.add}`)
		);
	};

	const field = (key, spec, value, set) => {
		switch (spec.type) {
			case 'textarea':
			case 'lines':
			case 'html':
				return el(c.TextareaControl, {
					key,
					label: spec.label,
					value: value || '',
					rows: spec.type === 'textarea' ? 3 : 5,
					help: { html: t.htmlHelp, lines: t.linesHelp }[spec.type],
					onChange: set,
					__nextHasNoMarginBottom: true,
				});
			case 'toggle':
				return el(c.ToggleControl, { key, label: spec.label, checked: Boolean(value), onChange: set, __nextHasNoMarginBottom: true });
			case 'number':
				return el(c.TextControl, { key, type: 'number', label: spec.label, value, onChange: (v) => set(parseInt(v, 10) || 0), __nextHasNoMarginBottom: true });
			case 'image':
				return imageField(key, spec, value, set);
			case 'list':
				return listField(key, spec, value, set);
		}
		if (CHOICE.includes(spec.type)) {
			const numeric = REF.includes(spec.type);
			return el(c.SelectControl, {
				key,
				label: spec.label,
				value,
				options: choices(spec),
				onChange: (v) => set(numeric ? (parseInt(v, 10) || 0) : v),
				__nextHasNoMarginBottom: true,
			});
		}
		return el(c.TextControl, { key, label: spec.label, value: value || '', onChange: set, __nextHasNoMarginBottom: true });
	};

	const prosePreview = (a) =>
		el('div', { className: 'os osteza-prose' },
			el('section', { className: 'sec sec-prose' },
				el('div', { className: 'wrap stack40' },
					(a.kicker || a.title) ? el('div', { className: 'stitle' },
						a.kicker ? el('p', { className: 'kick' }, a.kicker) : null,
						a.title ? el('h2', { className: 'disp h2' }, a.title) : null
					) : null,
					el('div', { className: 'prose' },
						el(be.InnerBlocks, { template: [['core/paragraph', { placeholder: t.inner }]] })
					)
				)
			)
		);

	for (const [name, b] of Object.entries(cfg.blocks)) {
		wp.blocks.registerBlockType(`osteza/${name}`, {
			apiVersion: 3,
			title: b.title,
			icon: b.icon,
			category: 'osteza',
			description: b.help,
			attributes: b.attributes,
			supports: { html: false },
			edit: ({ attributes: a, setAttributes }) => {
				const blockProps = be.useBlockProps({ className: 'osteza-block' });
				const sidebar = el(be.InspectorControls, null,
					el(c.PanelBody, { title: t.fields, initialOpen: true },
						Object.entries(b.fields).map(([k, f]) => field(k, f, a[k], (v) => setAttributes({ [k]: v })))
					)
				);
				const canvas = b.inner ? prosePreview(a) : el('div', { className: 'os osteza-ssr' },
					el(SSR, { block: `osteza/${name}`, attributes: a, httpMethod: 'POST', urlQueryArgs: { post_id: postId() } })
				);
				return el('div', blockProps, sidebar, canvas);
			},
			save: () => (b.inner ? el(be.InnerBlocks.Content) : null),
		});
	}
})(window.wp, window.OSTEZA_EDITOR);
