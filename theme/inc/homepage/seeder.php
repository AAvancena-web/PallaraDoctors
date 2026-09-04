<?php
/**
 * One-time content seeder for the homepage template.
 *
 * Runs once per site (guarded by the pallara_hp_seeded option) and writes
 * pallara_hp_defaults() into the ACF fields so the client starts with real,
 * editable content instead of empty boxes.
 *
 * Safety: the seeder never touches an existing published page. If no page is
 * using the template yet it creates a NEW DRAFT ("Home (Redesign)") and seeds
 * that, so the live homepage is unchanged until someone deliberately switches
 * over.
 *
 * Re-run manually with either:
 *   wp pallara seed-homepage [--force]
 *   /wp-admin/admin-post.php?action=pallara_hp_seed  (administrators, nonced)
 *
 * @package Pallara_Medical
 */

defined( 'ABSPATH' ) || exit;

const PALLARA_HP_TEMPLATE     = 'template-homepage.php';
const PALLARA_HP_SEED_OPTION  = 'pallara_hp_seeded';
const PALLARA_HP_SEED_VERSION = '1.0.0';
const PALLARA_HP_NOTICE       = 'pallara_hp_seed_notice';

/**
 * Image sub-fields whose default is a URL and which should be resolved to a
 * media library attachment where possible.
 *
 * @return array
 */
function pallara_hp_image_fields() {
	return array( 'hp_hero_image', 'hp_team_image', 'hp_sec_image_main', 'hp_sec_image_sub' );
}

/**
 * Find the page that uses the homepage template.
 *
 * @return int Page ID, or 0 when there is none.
 */
function pallara_hp_find_page() {
	$pages = get_posts(
		array(
			'post_type'        => 'page',
			'post_status'      => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'posts_per_page'   => 1,
			'orderby'          => 'ID',
			'order'            => 'ASC',
			'fields'           => 'ids',
			'suppress_filters' => false,
			'meta_query'       => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'   => '_wp_page_template',
					'value' => PALLARA_HP_TEMPLATE,
				),
			),
		)
	);

	return empty( $pages ) ? 0 : (int) $pages[0];
}

/**
 * Get (or create) the page to seed.
 *
 * @return int Page ID, or 0 on failure.
 */
function pallara_hp_target_page() {
	$page_id = pallara_hp_find_page();

	if ( $page_id ) {
		return $page_id;
	}

	$page_id = wp_insert_post(
		array(
			'post_title'   => 'Home (Redesign)',
			'post_name'    => 'home-redesign',
			'post_type'    => 'page',
			'post_status'  => 'draft',
			'post_content' => '',
			'meta_input'   => array(
				'_wp_page_template' => PALLARA_HP_TEMPLATE,
			),
		),
		true
	);

	if ( is_wp_error( $page_id ) ) {
		return 0;
	}

	return (int) $page_id;
}

/**
 * Resolve an uploaded file URL to an attachment ID.
 *
 * Tries the URL as given, then rewritten onto this site's home URL, so the
 * packaged production URLs still match on staging and local copies.
 *
 * @param string $url Image URL.
 * @return int Attachment ID, or 0.
 */
function pallara_hp_attachment_id( $url ) {
	if ( ! is_string( $url ) || '' === $url ) {
		return 0;
	}

	$id = attachment_url_to_postid( $url );

	if ( $id ) {
		return (int) $id;
	}

	$path = wp_parse_url( $url, PHP_URL_PATH );

	if ( ! $path ) {
		return 0;
	}

	$id = attachment_url_to_postid( home_url( $path ) );

	return (int) $id;
}

/**
 * Swap image URLs for attachment IDs, recursing through repeater rows.
 *
 * A URL that is not in the media library is dropped, which leaves the field
 * empty and lets the template fall back to the packaged placeholder.
 *
 * @param string $name  Field or sub-field name.
 * @param mixed  $value Value to prepare.
 * @return mixed
 */
function pallara_hp_prepare_value( $name, $value ) {
	if ( in_array( $name, pallara_hp_image_fields(), true ) ) {
		return pallara_hp_attachment_id( $value );
	}

	if ( ! is_array( $value ) ) {
		return $value;
	}

	// Repeater rows: a list of associative arrays of sub-fields.
	foreach ( $value as $index => $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		foreach ( $row as $sub_name => $sub_value ) {
			$value[ $index ][ $sub_name ] = pallara_hp_prepare_value( $sub_name, $sub_value );
		}
	}

	return $value;
}

/**
 * Write the default content into the page's ACF fields.
 *
 * @param int  $page_id  Page to seed.
 * @param bool $overwrite Overwrite fields that already have a value.
 * @return int Number of fields written.
 */
function pallara_hp_seed_page( $page_id, $overwrite = true ) {
	if ( ! $page_id || ! function_exists( 'update_field' ) ) {
		return 0;
	}

	$written = 0;

	foreach ( pallara_hp_defaults() as $name => $value ) {
		if ( ! $overwrite ) {
			$existing = get_field( $name, $page_id );

			if ( ! pallara_hp_is_blank( $existing ) ) {
				continue;
			}
		}

		$prepared = pallara_hp_prepare_value( $name, $value );

		// An image we could not resolve stays empty so the template falls back.
		if ( in_array( $name, pallara_hp_image_fields(), true ) && ! $prepared ) {
			continue;
		}

		update_field( 'field_' . $name, $prepared, $page_id );
		$written++;
	}

	return $written;
}

/**
 * Run the seeder once.
 *
 * @param bool $force Ignore the "already seeded" flag.
 * @return array {
 *     @type bool   $seeded  Whether anything was written.
 *     @type int    $page_id Page that was seeded.
 *     @type int    $fields  Number of fields written.
 *     @type string $message Human readable result.
 * }
 */
function pallara_hp_run_seeder( $force = false ) {
	$result = array(
		'seeded'  => false,
		'page_id' => 0,
		'fields'  => 0,
		'message' => '',
	);

	if ( ! $force && PALLARA_HP_SEED_VERSION === get_option( PALLARA_HP_SEED_OPTION ) ) {
		$result['message'] = 'Homepage content has already been seeded.';

		return $result;
	}

	if ( ! function_exists( 'acf_add_local_field_group' ) || ! function_exists( 'update_field' ) ) {
		$result['message'] = 'Advanced Custom Fields is not active, so the homepage seeder was skipped.';

		return $result;
	}

	$page_id = pallara_hp_target_page();

	if ( ! $page_id ) {
		$result['message'] = 'The homepage seeder could not find or create a page to seed.';

		return $result;
	}

	$result['page_id'] = $page_id;
	$result['fields']  = pallara_hp_seed_page( $page_id, $force );
	$result['seeded']  = true;
	$result['message'] = sprintf(
		'Seeded %1$d homepage fields into "%2$s" (page %3$d).',
		$result['fields'],
		get_the_title( $page_id ),
		$page_id
	);

	update_option( PALLARA_HP_SEED_OPTION, PALLARA_HP_SEED_VERSION, false );

	return $result;
}

/**
 * Seed on the first admin request after the code lands.
 *
 * @return void
 */
function pallara_hp_maybe_seed() {
	if ( PALLARA_HP_SEED_VERSION === get_option( PALLARA_HP_SEED_OPTION ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_pages' ) ) {
		return;
	}

	$result = pallara_hp_run_seeder();

	if ( $result['message'] ) {
		set_transient( PALLARA_HP_NOTICE, $result, MINUTE_IN_SECONDS * 10 );
	}
}
add_action( 'admin_init', 'pallara_hp_maybe_seed' );

/**
 * Show the seeder result once.
 *
 * @return void
 */
function pallara_hp_seed_notice() {
	$result = get_transient( PALLARA_HP_NOTICE );

	if ( ! $result || empty( $result['message'] ) ) {
		return;
	}

	delete_transient( PALLARA_HP_NOTICE );

	$class = ! empty( $result['seeded'] ) ? 'notice-success' : 'notice-warning';
	$edit  = ! empty( $result['page_id'] ) ? get_edit_post_link( $result['page_id'] ) : '';

	printf(
		'<div class="notice %1$s is-dismissible"><p><strong>Pallara homepage:</strong> %2$s%3$s</p></div>',
		esc_attr( $class ),
		esc_html( $result['message'] ),
		$edit ? ' <a href="' . esc_url( $edit ) . '">Edit the page</a>.' : ''
	);
}
add_action( 'admin_notices', 'pallara_hp_seed_notice' );

/**
 * Manual re-run endpoint for administrators.
 *
 * @return void
 */
function pallara_hp_handle_manual_seed() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have permission to run the homepage seeder.', 403 );
	}

	check_admin_referer( 'pallara_hp_seed' );

	$result = pallara_hp_run_seeder( true );
	set_transient( PALLARA_HP_NOTICE, $result, MINUTE_IN_SECONDS * 10 );

	$redirect = ! empty( $result['page_id'] )
		? get_edit_post_link( $result['page_id'], 'raw' )
		: admin_url( 'edit.php?post_type=page' );

	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'admin_post_pallara_hp_seed', 'pallara_hp_handle_manual_seed' );

/**
 * The nonced URL that re-runs the seeder.
 *
 * @return string
 */
function pallara_hp_seed_url() {
	return wp_nonce_url( admin_url( 'admin-post.php?action=pallara_hp_seed' ), 'pallara_hp_seed' );
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'pallara seed-homepage',
		function ( $args, $assoc_args ) {
			$result = pallara_hp_run_seeder( isset( $assoc_args['force'] ) );

			if ( $result['seeded'] ) {
				WP_CLI::success( $result['message'] );
			} else {
				WP_CLI::warning( $result['message'] . ' Pass --force to re-run.' );
			}
		},
		array(
			'shortdesc' => 'Seed the Pallara homepage template with its default ACF content.',
			'synopsis'  => array(
				array(
					'type'     => 'flag',
					'name'     => 'force',
					'optional' => true,
				),
			),
		)
	);
}
