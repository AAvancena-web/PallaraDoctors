<?php
/**
 * Homepage template helpers.
 *
 * Every getter falls back to pallara_hp_defaults() so the template renders
 * complete content even before the seeder has run, or if an editor empties
 * a field.
 *
 * @package Pallara_Medical
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is an ACF value effectively empty? (false and 0 are real values.)
 *
 * @param mixed $value Value to test.
 * @return bool
 */
function pallara_hp_is_blank( $value ) {
	return null === $value || '' === $value || array() === $value;
}

/**
 * Get a field for the current page, falling back to the packaged default.
 *
 * @param string $name    ACF field name.
 * @param int    $post_id Optional post ID.
 * @return mixed
 */
function pallara_hp_get( $name, $post_id = null ) {
	// A 0 post ID would make ACF fall back to the current post; keep it null.
	$post_id = $post_id ? $post_id : null;
	$value   = function_exists( 'get_field' ) ? get_field( $name, $post_id ) : null;

	if ( ! pallara_hp_is_blank( $value ) ) {
		return $value;
	}

	$defaults = pallara_hp_defaults();

	return isset( $defaults[ $name ] ) ? $defaults[ $name ] : '';
}

/**
 * Get a repeater as a plain array of rows.
 *
 * @param string $name    ACF repeater field name.
 * @param int    $post_id Optional post ID.
 * @return array
 */
function pallara_hp_rows( $name, $post_id = null ) {
	$rows = pallara_hp_get( $name, $post_id );

	return is_array( $rows ) ? $rows : array();
}

/**
 * Read a sub-field from a repeater row, with an optional default.
 *
 * @param array  $row      Row array.
 * @param string $key      Sub-field name.
 * @param mixed  $fallback Value to use when the sub-field is blank.
 * @return mixed
 */
function pallara_hp_sub( $row, $key, $fallback = '' ) {
	if ( ! is_array( $row ) || ! isset( $row[ $key ] ) || pallara_hp_is_blank( $row[ $key ] ) ) {
		return $fallback;
	}

	return $row[ $key ];
}

/**
 * Read a nested repeater from a repeater row as a clean list of rows.
 *
 * ACF hands back false for an empty nested repeater, and casting that with
 * (array) produces array( false ) - one phantom row, which is how an empty
 * tick list ended up rendering a lone tick icon. Only real row arrays get
 * through here.
 *
 * @param array  $row Parent row.
 * @param string $key Nested repeater sub-field name.
 * @return array
 */
function pallara_hp_sub_rows( $row, $key ) {
	if ( ! is_array( $row ) || empty( $row[ $key ] ) || ! is_array( $row[ $key ] ) ) {
		return array();
	}

	return array_values( array_filter( $row[ $key ], 'is_array' ) );
}

/**
 * Normalise an ACF link value into url / title / target.
 *
 * Accepts the ACF link array, a bare URL string, or an empty value.
 *
 * @param mixed  $value         Link value.
 * @param string $default_title Title to use when the value has none.
 * @return array|false Array with url, title and target, or false when unusable.
 */
function pallara_hp_link( $value, $default_title = '' ) {
	if ( pallara_hp_is_blank( $value ) ) {
		return false;
	}

	if ( is_string( $value ) ) {
		$value = array( 'url' => $value );
	}

	if ( ! is_array( $value ) || empty( $value['url'] ) ) {
		return false;
	}

	$title = ! empty( $value['title'] ) ? $value['title'] : $default_title;

	return array(
		'url'    => $value['url'],
		'title'  => $title,
		'target' => ! empty( $value['target'] ) ? $value['target'] : '',
	);
}

/**
 * Render a link as a button.
 *
 * @param mixed  $value         ACF link value.
 * @param string $classes       Button classes.
 * @param string $icon          Optional icon name from the sprite.
 * @param string $default_title Fallback link text.
 * @return void
 */
function pallara_hp_button( $value, $classes = 'pm-btn pm-btn--primary', $icon = '', $default_title = '' ) {
	$link = pallara_hp_link( $value, $default_title );

	if ( ! $link ) {
		return;
	}

	printf(
		'<a class="%1$s" href="%2$s"%3$s>%4$s%5$s</a>',
		esc_attr( $classes ),
		esc_url( $link['url'] ),
		'_blank' === $link['target'] ? ' target="_blank" rel="noopener"' : '',
		$icon ? pallara_hp_icon( $icon, false ) : '',
		esc_html( $link['title'] )
	);
}

/**
 * Render an image field, falling back to a URL string.
 *
 * @param mixed  $value ACF image value (array, ID or URL string).
 * @param string $alt   Alt text used for the URL fallback.
 * @param array  $args  class, size, loading, width, height.
 * @return void
 */
function pallara_hp_image( $value, $alt = '', $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'class'   => '',
			'size'    => 'large',
			'loading' => 'lazy',
			'width'   => '',
			'height'  => '',
		)
	);

	$id = 0;

	if ( is_array( $value ) && ! empty( $value['ID'] ) ) {
		$id = (int) $value['ID'];
	} elseif ( is_numeric( $value ) ) {
		$id = (int) $value;
	}

	if ( $id ) {
		echo wp_get_attachment_image(
			$id,
			$args['size'],
			false,
			array(
				'class'   => $args['class'],
				'alt'     => $alt,
				'loading' => $args['loading'],
			)
		);

		return;
	}

	$url = '';

	if ( is_array( $value ) && ! empty( $value['url'] ) ) {
		$url = $value['url'];
	} elseif ( is_string( $value ) ) {
		$url = $value;
	}

	if ( ! $url ) {
		return;
	}

	printf(
		'<img src="%1$s" alt="%2$s" class="%3$s" loading="%4$s"%5$s%6$s>',
		esc_url( $url ),
		esc_attr( $alt ),
		esc_attr( $args['class'] ),
		esc_attr( $args['loading'] ),
		$args['width'] ? ' width="' . esc_attr( $args['width'] ) . '"' : '',
		$args['height'] ? ' height="' . esc_attr( $args['height'] ) . '"' : ''
	);
}

/**
 * Resolve an image field down to a plain URL (used for CSS backgrounds).
 *
 * @param mixed  $value ACF image value.
 * @param string $size  Image size.
 * @return string
 */
function pallara_hp_image_url( $value, $size = 'full' ) {
	if ( is_array( $value ) && ! empty( $value['ID'] ) ) {
		$src = wp_get_attachment_image_src( (int) $value['ID'], $size );

		return $src ? $src[0] : '';
	}

	if ( is_numeric( $value ) ) {
		$src = wp_get_attachment_image_src( (int) $value, $size );

		return $src ? $src[0] : '';
	}

	if ( is_array( $value ) && ! empty( $value['url'] ) ) {
		return $value['url'];
	}

	return is_string( $value ) ? $value : '';
}

/**
 * Icons available to the ACF select fields.
 *
 * @return array
 */
function pallara_hp_icon_choices() {
	return array(
		'phone'    => 'Phone',
		'mail'     => 'Email',
		'pin'      => 'Map pin',
		'fax'      => 'Fax',
		'clock'    => 'Clock',
		'check'    => 'Tick',
		'calendar' => 'Calendar',
		'shield'   => 'Shield',
		'stetho'   => 'Stethoscope',
		'baby'     => 'Child',
		'female'   => "Women's health",
		'scan'     => 'Scan',
		'scalpel'  => 'Scalpel',
		'arrow'    => 'Arrow',
	);
}

/**
 * Render one icon from the sprite.
 *
 * @param string $name  Icon name.
 * @param bool   $echo  Echo or return.
 * @return string
 */
function pallara_hp_icon( $name, $echo = true ) {
	$name = sanitize_key( $name );

	if ( ! $name || ! array_key_exists( $name, pallara_hp_icon_choices() ) ) {
		return '';
	}

	$svg = '<svg class="pm-ico" aria-hidden="true" focusable="false"><use href="#pm-i-' . esc_attr( $name ) . '"></use></svg>';

	if ( $echo ) {
		echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above.
	}

	return $svg;
}

/**
 * Print the SVG sprite. Called once, at the top of the template.
 *
 * @return void
 */
function pallara_hp_icon_sprite() {
	?>
	<svg xmlns="http://www.w3.org/2000/svg" style="display:none" aria-hidden="true" focusable="false">
		<symbol id="pm-i-phone" viewBox="0 0 24 24"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.2.4 2.4.6 3.7.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.7.1.3 0 .7-.2 1l-2.3 2.1z"/></symbol>
		<symbol id="pm-i-mail" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4.2-8 4.8-8-4.8V6l8 4.8L20 6v2.2z"/></symbol>
		<symbol id="pm-i-pin" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></symbol>
		<symbol id="pm-i-fax" viewBox="0 0 24 24"><path d="M7 2h10v4H7zM5 8a3 3 0 0 0-3 3v7a2 2 0 0 0 2 2h1V8H5zm14 0h-1v12h1a2 2 0 0 0 2-2v-7a3 3 0 0 0-3-3zM8 8v12h8V8H8zm6 8h-4v-2h4v2zm0-4h-4v-2h4v2z"/></symbol>
		<symbol id="pm-i-clock" viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 10.6-4-2.3V6h2v3.7l3 1.7-1 1.2z"/></symbol>
		<symbol id="pm-i-check" viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm-1.2 14.4-4.2-4.2 1.4-1.4 2.8 2.8 5.8-5.8 1.4 1.4-7.2 7.2z"/></symbol>
		<symbol id="pm-i-calendar" viewBox="0 0 24 24"><path d="M7 2v2H5a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V2h-2v2H9V2H7zm12 8v9H5v-9h14z"/></symbol>
		<symbol id="pm-i-shield" viewBox="0 0 24 24"><path d="M12 2 4 5v6c0 5 3.4 9.7 8 11 4.6-1.3 8-6 8-11V5l-8-3zm-1 13-3-3 1.4-1.4 1.6 1.6 4-4L16.4 9 11 15z"/></symbol>
		<symbol id="pm-i-stetho" viewBox="0 0 24 24"><path d="M6 3v6a4 4 0 0 0 3 3.9V15a4 4 0 0 0 8 0v-1.2a3 3 0 1 0-2-.1V15a2 2 0 0 1-4 0v-2.1A4 4 0 0 0 14 9V3h-2v6a2 2 0 0 1-4 0V3H6zm12 8.5a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></symbol>
		<symbol id="pm-i-baby" viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zM9 9.5a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4zm6 0a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4zm-3 8.1c-2 0-3.6-1.2-4.2-2.9h8.4c-.6 1.7-2.2 2.9-4.2 2.9z"/></symbol>
		<symbol id="pm-i-female" viewBox="0 0 24 24"><path d="M12 2a6 6 0 0 0-1 11.9V16H8v2h3v4h2v-4h3v-2h-3v-2.1A6 6 0 0 0 12 2zm0 2a4 4 0 1 1 0 8 4 4 0 0 1 0-8z"/></symbol>
		<symbol id="pm-i-scan" viewBox="0 0 24 24"><path d="M3 3h6v2H5v4H3V3zm12 0h6v6h-2V5h-4V3zM3 15h2v4h4v2H3v-6zm16 0h2v6h-6v-2h4v-4zM12 8a4 4 0 1 1 0 8 4 4 0 0 1 0-8z"/></symbol>
		<symbol id="pm-i-scalpel" viewBox="0 0 24 24"><path d="M20.8 3.2a2 2 0 0 0-2.8 0L9 12.2 11.8 15l9-9a2 2 0 0 0 0-2.8zM8 13.6 3 18.6V21h2.4l5-5L8 13.6z"/></symbol>
		<symbol id="pm-i-arrow" viewBox="0 0 24 24"><path d="M13.2 4.6 11.8 6l5.2 5H3v2h14l-5.2 5 1.4 1.4L21 12l-7.8-7.4z"/></symbol>
	</svg>
	<?php
}

/**
 * Render the built-in appointment form.
 *
 * Used when no Contact Form 7 shortcode has been set for that form. The
 * doctor and time-slot options come from their ACF repeaters. Submission is
 * handled client-side (validation + confirmation message) - wire it to CF7
 * by pasting a shortcode into the matching field when the form should
 * actually send mail.
 *
 * @param array $args id_prefix, submit_label, note, note_icon, notes_rows.
 * @return void
 */
function pallara_hp_render_form( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'id_prefix'    => 'pm-form',
			'submit_label' => 'Request Appointment',
			'note'         => '',
			'note_icon'    => 'shield',
			'notes_rows'   => 3,
		)
	);

	$prefix  = sanitize_html_class( $args['id_prefix'] );
	$doctors = pallara_hp_rows( 'hp_form_doctors' );
	$slots   = pallara_hp_rows( 'hp_form_time_slots' );
	?>
	<form class="pm-js-form" novalidate>
		<div class="pm-form-grid">
			<div class="pm-field">
				<label for="<?php echo esc_attr( $prefix ); ?>-name">Full name*</label>
				<input id="<?php echo esc_attr( $prefix ); ?>-name" name="name" type="text" placeholder="Jane Smith" required>
			</div>
			<div class="pm-field">
				<label for="<?php echo esc_attr( $prefix ); ?>-phone">Phone*</label>
				<input id="<?php echo esc_attr( $prefix ); ?>-phone" name="phone" type="tel" placeholder="0400 000 000" required>
			</div>
			<div class="pm-field">
				<label for="<?php echo esc_attr( $prefix ); ?>-email">Email*</label>
				<input id="<?php echo esc_attr( $prefix ); ?>-email" name="email" type="email" placeholder="you@email.com" required>
			</div>
			<div class="pm-field">
				<label for="<?php echo esc_attr( $prefix ); ?>-doctor">Preferred doctor</label>
				<select id="<?php echo esc_attr( $prefix ); ?>-doctor" name="doctor">
					<option value="">Next available GP</option>
					<?php foreach ( $doctors as $doctor ) : ?>
						<?php $name = pallara_hp_sub( $doctor, 'hp_doctor_name' ); ?>
						<?php if ( $name ) : ?>
							<option><?php echo esc_html( $name ); ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="pm-field">
				<label for="<?php echo esc_attr( $prefix ); ?>-date">Preferred date*</label>
				<input id="<?php echo esc_attr( $prefix ); ?>-date" name="date" type="date" required>
			</div>
			<div class="pm-field">
				<label for="<?php echo esc_attr( $prefix ); ?>-time">Preferred time*</label>
				<select id="<?php echo esc_attr( $prefix ); ?>-time" name="time" required>
					<option value="">Select a time</option>
					<?php foreach ( $slots as $slot ) : ?>
						<?php $label = pallara_hp_sub( $slot, 'hp_slot_label' ); ?>
						<?php if ( $label ) : ?>
							<option><?php echo esc_html( $label ); ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="pm-field pm-field--full">
				<label for="<?php echo esc_attr( $prefix ); ?>-notes">Reason for visit (optional)</label>
				<textarea id="<?php echo esc_attr( $prefix ); ?>-notes" name="notes" rows="<?php echo esc_attr( (int) $args['notes_rows'] ); ?>" placeholder="Tell us briefly how we can help"></textarea>
			</div>
		</div>

		<button class="pm-btn pm-btn--primary pm-btn--block pm-btn--lg" type="submit" style="margin-top:16px">
			<?php echo esc_html( $args['submit_label'] ); ?>
		</button>

		<?php if ( $args['note'] ) : ?>
			<p class="pm-form-foot">
				<?php pallara_hp_icon( $args['note_icon'] ); ?>
				<?php echo esc_html( $args['note'] ); ?>
			</p>
		<?php endif; ?>

		<p class="pm-form-status" role="status"></p>
	</form>
	<?php
}
