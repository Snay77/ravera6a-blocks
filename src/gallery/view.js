import apiFetch from '@wordpress/api-fetch';

function parseIds(el) {
	try {
		return JSON.parse(el.dataset.ids || '[]');
	} catch (e) {
		return [];
	}
}

async function loadNextBatch(wrapper) {
	const gallery = wrapper.querySelector('.ravera-gallery');
	const btn = wrapper.querySelector('.ravera-gallery__more');
	if (!gallery || !btn) return;

	const ids = parseIds(wrapper);
	const perPage = parseInt(wrapper.dataset.perPage || '9', 10);
	const size = wrapper.dataset.size || 'large';

	let page = parseInt(wrapper.dataset.page || '1', 10);
	page += 1;

	btn.disabled = true;
	btn.setAttribute('aria-busy', 'true');

	try {
		const res = await apiFetch({
			path: '/ravera/v1/gallery',
			method: 'POST',
			data: { ids, page, perPage, size },
		});

		if (res?.html) {
			gallery.insertAdjacentHTML('beforeend', res.html);
		}

		wrapper.dataset.page = String(page);

		if (!res?.hasMore) {
			btn.closest('.ravera-gallery__actions')?.remove();
		} else {
			btn.disabled = false;
		}
	} catch (e) {
		btn.disabled = false;
		console.error('Ravera gallery load more failed:', e);
	} finally {
		btn.removeAttribute('aria-busy');
	}
}

function init() {
	document.querySelectorAll('[data-ravera-gallery="1"]').forEach((wrapper) => {
		const btn = wrapper.querySelector('.ravera-gallery__more');
		if (!btn) return;

		wrapper.dataset.page = wrapper.dataset.page || '1';

		btn.addEventListener('click', (e) => {
			e.preventDefault();
			e.stopPropagation();
			loadNextBatch(wrapper);
		});
	});
}

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', init);
} else {
	init();
}