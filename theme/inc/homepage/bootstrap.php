<?php
/**
 * Pallara homepage redesign - bootstrap.
 *
 * Add this one line to the child theme's functions.php:
 *
 *     require_once get_stylesheet_directory() . '/inc/homepage/bootstrap.php';
 *
 * @package Pallara_Medical
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/defaults.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/acf-fields.php';
require_once __DIR__ . '/seeder.php';

/**
 * Absolute path / URL helpers for the theme assets.
 *
 * @param string $relative Path relative to the child theme root.
 * @return string
 */
function pallara_hp_asset_url( $relative ) {
	return get_stylesheet_directory_uri() . '/' . ltrim( $relative, '/' );
}

/**
 * Cache-busting version based on the file's own mtime.
 *
 * @param string $relative Path relative to the child theme root.
 * @return string
 */
function pallara_hp_asset_version( $relative ) {
	$file = get_stylesheet_directory() . '/' . ltrim( $relative, '/' );

	return file_exists( $file ) ? (string) filemtime( $file ) : PALLARA_HP_SEED_VERSION;
}

/**
 * Is the current request rendering the homepage template?
 *
 * @return bool
 */
function pallara_hp_is_template() {
	return is_page_template( PALLARA_HP_TEMPLATE );
}

/**
 * The page that holds the homepage fields (used for the sitewide phone data).
 *
 * @return int
 */
function pallara_hp_settings_page_id() {
	static $page_id = null;

	if ( null === $page_id ) {
		$page_id = pallara_hp_find_page();
	}

	return $page_id;
}

/**
 * Phone number, display and dial versions.
 *
 * @return array
 */
function pallara_hp_phone() {
	$page_id = pallara_hp_is_template() ? null : pallara_hp_settings_page_id();

	$display = pallara_hp_get( 'hp_phone_display', $page_id );
	$dial    = pallara_hp_get( 'hp_phone_dial', $page_id );

	if ( ! $dial ) {
		$dial = preg_replace( '/[^0-9+]/', '', $display );
	}

	return array(
		'display' => $display,
		'dial'    => $dial,
	);
}

/**
 * Enqueue the styles and scripts.
 *
 * @return void
 */
function pallara_hp_enqueue_assets() {
	$phone = pallara_hp_phone();

	// Sitewide: circular header phone button + floating call button.
	wp_enqueue_style(
		'pallara-call-affordances',
		pallara_hp_asset_url( 'assets/css/call-affordances.css' ),
		array(),
		pallara_hp_asset_version( 'assets/css/call-affordances.css' )
	);

	wp_enqueue_script(
		'pallara-call-affordances',
		pallara_hp_asset_url( 'assets/js/call-affordances.js' ),
		array(),
		pallara_hp_asset_version( 'assets/js/call-affordances.js' ),
		true
	);

	wp_localize_script(
		'pallara-call-affordances',
		'pmCallAffordances',
		array(
			'tel'   => $phone['dial'],
			'label' => sprintf( 'Call the clinic on %s', $phone['display'] ),
		)
	);

	if ( ! pallara_hp_is_template() ) {
		return;
	}

	wp_enqueue_style(
		'pallara-homepage',
		pallara_hp_asset_url( 'assets/css/homepage.css' ),
		array(),
		pallara_hp_asset_version( 'assets/css/homepage.css' )
	);

	wp_enqueue_script(
		'pallara-homepage',
		pallara_hp_asset_url( 'assets/js/homepage.js' ),
		array(),
		pallara_hp_asset_version( 'assets/js/homepage.js' ),
		true
	);

	wp_localize_script(
		'pallara-homepage',
		'pmHomepage',
		array(
			'required' => __( 'Please complete the required fields so we can get back to you.', 'siteorigin-corp' ),
			'email'    => __( 'That email address does not look right. Please check it.', 'siteorigin-corp' ),
			'success'  => __( 'Thanks! Your request has been received. Our reception team will confirm your appointment shortly.', 'siteorigin-corp' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'pallara_hp_enqueue_assets', 20 );

/**
 * Print the floating "Call Now" button (mobile, bottom centre).
 *
 * @return void
 */
function pallara_hp_floating_call_button() {
	$page_id = pallara_hp_is_template() ? null : pallara_hp_settings_page_id();
	$show    = pallara_hp_get( 'hp_floating_call_show', $page_id );

	/**
	 * Filter whether the floating call button is printed on this request.
	 *
	 * @param bool $show Whether to print the button.
	 */
	if ( ! apply_filters( 'pallara_hp_show_floating_call', (bool) $show ) ) {
		return;
	}

	$phone = pallara_hp_phone();

	if ( ! $phone['dial'] ) {
		return;
	}

	$label = pallara_hp_get( 'hp_floating_call_label', $page_id );
	?>
	<a class="pm-floating-call" href="tel:<?php echo esc_attr( $phone['dial'] ); ?>"
		aria-label="<?php echo esc_attr( sprintf( 'Call Pallara Medical now on %s', $phone['display'] ) ); ?>">
		<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
			<path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.2.4 2.4.6 3.7.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.7.1.3 0 .7-.2 1l-2.3 2.1z"/>
		</svg>
		<?php echo esc_html( $label ); ?>
	</a>
	<?php
}
add_action( 'wp_footer', 'pallara_hp_floating_call_button' );
