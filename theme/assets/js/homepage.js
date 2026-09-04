/**
 * Pallara Medical - homepage template behaviour.
 *
 * Only runs on the "Homepage - Pallara Redesign" template. The forms fall
 * back to this lightweight validation when no Contact Form 7 shortcode has
 * been set in the ACF fields; when a shortcode IS set, CF7 owns the form and
 * these handlers never see it (they only bind to .pm-js-form).
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
		var root = document.querySelector('.pm-home');

		if (!root) {
			return;
		}

		var strings = window.pmHomepage || {};

		/* Appointment dates cannot be in the past. */
		var today = new Date().toISOString().split('T')[0];
		root.querySelectorAll('input[type="date"]').forEach(function (input) {
			input.min = today;
		});

		/* Front-end validation for the built-in (non CF7) forms. */
		root.querySelectorAll('.pm-js-form').forEach(function (form) {
			form.addEventListener('submit', function (event) {
				event.preventDefault();

				var status = form.querySelector('.pm-form-status');
				var required = Array.prototype.slice.call(form.querySelectorAll('[required]'));
				var missing = required.filter(function (field) {
					return !field.value.trim();
				});
				var email = form.querySelector('input[type="email"]');

				function fail(message, field) {
					status.textContent = message;
					status.style.background = '#fdecec';
					status.style.color = '#a12525';
					status.classList.add('pm-is-visible');

					if (field) {
						field.focus();
					}
				}

				if (missing.length) {
					fail(strings.required || 'Please complete the required fields so we can get back to you.', missing[0]);
					return;
				}

				if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
					fail(strings.email || 'That email address does not look right. Please check it.', email);
					return;
				}

				status.textContent = strings.success || 'Thanks! Your request has been received. Our reception team will confirm your appointment shortly.';
				status.style.background = '';
				status.style.color = '';
				status.classList.add('pm-is-visible');
				form.reset();
			});
		});

		/* Reveal on scroll. */
		var revealables = root.querySelectorAll('.pm-reveal');

		if (!('IntersectionObserver' in window)) {
			revealables.forEach(function (el) {
				el.classList.add('pm-in');
			});
			return;
		}

		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('pm-in');
					observer.unobserve(entry.target);
				}
			});
		}, { rootMargin: '0px 0px -60px 0px' });

		revealables.forEach(function (el) {
			observer.observe(el);
		});
	});
})();
