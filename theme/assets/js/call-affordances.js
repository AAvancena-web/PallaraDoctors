/**
 * Pallara Medical - call affordances.
 *
 * header.php prints a "call-icon-mobile" widget just before the hamburger,
 * and call-affordances.css restyles it into a dark circular button. If that
 * widget area is ever emptied in the admin, this drops an equivalent button
 * into the same spot so the header never loses its click-to-call.
 */
(function () {
	'use strict';

	function ready(fn) {
		if (document.readyState !== 'loading') {
			fn();
		} else {
			document.addEventListener('DOMContentLoaded', fn);
		}
	}

	ready(function () {
		var settings = window.pmCallAffordances || {};
		var tel = settings.tel || '';

		if (!tel) {
			return;
		}

		var nav = document.getElementById('site-navigation');
		var toggle = nav ? nav.querySelector('.reflex-menu-toggle') : null;

		if (!nav || !toggle) {
			return;
		}

		// Nothing to do while the widget is providing the icon.
		if (nav.querySelector('.mobile-call-menu a') || nav.querySelector('.pm-phone-circle')) {
			return;
		}

		var link = document.createElement('a');
		link.className = 'pm-phone-circle';
		link.href = 'tel:' + tel;
		link.setAttribute('aria-label', settings.label || 'Call the clinic');
		link.innerHTML =
			'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">' +
			'<path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.2.4 2.4.6 3.7.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.7.1.3 0 .7-.2 1l-2.3 2.1z"/>' +
			'</svg>';

		var holder = document.createElement('div');
		holder.className = 'mobile-call-menu';
		holder.appendChild(link);
		toggle.parentNode.insertBefore(holder, toggle);
	});
})();
