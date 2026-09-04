<?php
/**
 * ACF field group for the homepage template.
 *
 * Registered in code (not the admin UI) so the fields travel with the theme
 * and cannot be lost. Field keys are deterministic - field_<field name> -
 * which is what the seeder writes against.
 *
 * @package Pallara_Medical
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the field group.
 *
 * @return void
 */
function pallara_hp_register_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$icons = pallara_hp_icon_choices();

	$tab = static function ( $key, $label ) {
		return array(
			'key'       => 'field_hp_tab_' . $key,
			'label'     => $label,
			'type'      => 'tab',
			'placement' => 'top',
		);
	};

	$text = static function ( $name, $label, $args = array() ) {
		return array_merge(
			array(
				'key'   => 'field_' . $name,
				'name'  => $name,
				'label' => $label,
				'type'  => 'text',
			),
			$args
		);
	};

	$link = static function ( $name, $label, $args = array() ) {
		return array_merge(
			array(
				'key'           => 'field_' . $name,
				'name'          => $name,
				'label'         => $label,
				'type'          => 'link',
				'return_format' => 'array',
			),
			$args
		);
	};

	$image = static function ( $name, $label, $instructions = '' ) {
		return array(
			'key'           => 'field_' . $name,
			'name'          => $name,
			'label'         => $label,
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
			'library'       => 'all',
			'instructions'  => $instructions ? $instructions : 'Leave empty to keep the current placeholder image.',
		);
	};

	$icon_select = static function ( $name, $label, $default, $icons ) {
		return array(
			'key'           => 'field_' . $name,
			'name'          => $name,
			'label'         => $label,
			'type'          => 'select',
			'choices'       => $icons,
			'default_value' => $default,
			'ui'            => 1,
			'wrapper'       => array( 'width' => '25' ),
		);
	};

	acf_add_local_field_group(
		array(
			'key'                   => 'group_pallara_homepage',
			'title'                 => 'Homepage (Pallara Redesign)',
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'active'                => true,
			'description'           => 'Content for the "Homepage - Pallara Redesign" page template. Any field left empty falls back to the packaged default copy.',
			'location'              => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'template-homepage.php',
					),
				),
			),
			'fields'                => array(

				/* ============================ Hero ============================ */
				$tab( 'hero', 'Hero' ),
				$text( 'hp_hero_eyebrow', 'Eyebrow', array( 'wrapper' => array( 'width' => '40' ) ) ),
				$text( 'hp_hero_heading', 'Heading', array( 'wrapper' => array( 'width' => '30' ) ) ),
				$text(
					'hp_hero_heading_accent',
					'Heading (highlighted part)',
					array(
						'wrapper'      => array( 'width' => '30' ),
						'instructions' => 'Shown in light blue at the end of the H1.',
					)
				),
				array(
					'key'   => 'field_hp_hero_text',
					'name'  => 'hp_hero_text',
					'label' => 'Intro paragraph',
					'type'  => 'textarea',
					'rows'  => 3,
				),
				array(
					'key'          => 'field_hp_hero_badges',
					'name'         => 'hp_hero_badges',
					'label'        => 'Trust badges',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => 'Add badge',
					'min'          => 0,
					'max'          => 6,
					'instructions' => 'Small ticked pills under the hero paragraph.',
					'sub_fields'   => array(
						$text( 'hp_badge_text', 'Badge text' ),
					),
				),
				$link( 'hp_hero_cta_primary', 'Primary button (Book Now)', array( 'wrapper' => array( 'width' => '50' ) ) ),
				$link( 'hp_hero_cta_secondary', 'Secondary button (phone)', array( 'wrapper' => array( 'width' => '50' ) ) ),
				$text(
					'hp_hero_note',
					'Note under the buttons',
					array( 'instructions' => 'Basic HTML such as &lt;strong&gt; is allowed.' )
				),
				$image( 'hp_hero_image', 'Hero background image' ),

				/* ======================= Hero booking form ==================== */
				$tab( 'form', 'Hero form' ),
				$text( 'hp_form_title', 'Form heading', array( 'wrapper' => array( 'width' => '50' ) ) ),
				$text( 'hp_form_intro', 'Intro line', array( 'wrapper' => array( 'width' => '25' ) ) ),
				$text( 'hp_form_intro_link_label', 'Intro link label', array( 'wrapper' => array( 'width' => '25' ) ) ),
				$text(
					'hp_form_shortcode',
					'Contact Form 7 shortcode',
					array(
						'instructions' => 'Optional. Paste a shortcode such as [contact-form-7 id="2f783e2" title="Homepage Banner"] to use a real CF7 form. Leave empty to use the built-in layout below.',
					)
				),
				array(
					'key'                => 'field_hp_form_doctors',
					'name'               => 'hp_form_doctors',
					'label'              => 'Doctors in the dropdown',
					'type'               => 'repeater',
					'layout'             => 'table',
					'button_label'       => 'Add doctor',
					'conditional_logic'  => array(
						array(
							array(
								'field'    => 'field_hp_form_shortcode',
								'operator' => '==empty',
							),
						),
					),
					'sub_fields'         => array(
						$text( 'hp_doctor_name', 'Doctor name' ),
					),
				),
				array(
					'key'               => 'field_hp_form_time_slots',
					'name'              => 'hp_form_time_slots',
					'label'             => 'Appointment time slots',
					'type'              => 'repeater',
					'layout'            => 'table',
					'button_label'      => 'Add time slot',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_hp_form_shortcode',
								'operator' => '==empty',
							),
						),
					),
					'sub_fields'        => array(
						$text( 'hp_slot_label', 'Slot label' ),
					),
				),
				$text( 'hp_form_submit_label', 'Submit button label', array( 'wrapper' => array( 'width' => '50' ) ) ),
				$text( 'hp_form_note', 'Privacy note', array( 'wrapper' => array( 'width' => '50' ) ) ),

				/* ========================= Quick cards ======================== */
				$tab( 'quick', 'Quick info cards' ),
				array(
					'key'          => 'field_hp_quick_cards',
					'name'         => 'hp_quick_cards',
					'label'        => 'Quick info cards',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add card',
					'min'          => 0,
					'max'          => 4,
					'instructions' => 'The band of cards that overlaps the bottom of the hero.',
					'sub_fields'   => array(
						$icon_select( 'hp_qc_icon', 'Icon', 'clock', $icons ),
						$text( 'hp_qc_title', 'Title', array( 'wrapper' => array( 'width' => '35' ) ) ),
						array(
							'key'     => 'field_hp_qc_text',
							'name'    => 'hp_qc_text',
							'label'   => 'Text',
							'type'    => 'textarea',
							'rows'    => 2,
							'wrapper' => array( 'width' => '40' ),
						),
						array(
							'key'          => 'field_hp_qc_rows',
							'name'         => 'hp_qc_rows',
							'label'        => 'Detail rows (e.g. opening hours)',
							'type'         => 'repeater',
							'layout'       => 'table',
							'button_label' => 'Add row',
							'sub_fields'   => array(
								$text( 'hp_qc_row_label', 'Label' ),
								$text( 'hp_qc_row_value', 'Value' ),
							),
						),
						$link( 'hp_qc_link', 'Card link' ),
					),
				),

				/* =========================== Services ========================= */
				$tab( 'services', 'Services' ),
				$text( 'hp_services_eyebrow', 'Eyebrow', array( 'wrapper' => array( 'width' => '30' ) ) ),
				$text( 'hp_services_heading', 'Heading', array( 'wrapper' => array( 'width' => '70' ) ) ),
				array(
					'key'   => 'field_hp_services_intro',
					'name'  => 'hp_services_intro',
					'label' => 'Intro paragraph',
					'type'  => 'textarea',
					'rows'  => 2,
				),
				array(
					'key'          => 'field_hp_services',
					'name'         => 'hp_services',
					'label'        => 'Service cards',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add service',
					'sub_fields'   => array(
						$icon_select( 'hp_service_icon', 'Icon', 'stetho', $icons ),
						$text( 'hp_service_title', 'Title', array( 'wrapper' => array( 'width' => '35' ) ) ),
						array(
							'key'     => 'field_hp_service_text',
							'name'    => 'hp_service_text',
							'label'   => 'Description',
							'type'    => 'textarea',
							'rows'    => 2,
							'wrapper' => array( 'width' => '40' ),
						),
					),
				),
				$link( 'hp_services_cta_primary', 'Primary button', array( 'wrapper' => array( 'width' => '50' ) ) ),
				$link( 'hp_services_cta_secondary', 'Secondary button', array( 'wrapper' => array( 'width' => '50' ) ) ),

				/* ======================= Content sections ===================== */
				$tab( 'sections', 'Content sections' ),
				array(
					'key'          => 'field_hp_sections',
					'name'         => 'hp_sections',
					'label'        => 'Alternating image and text sections',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add section',
					'sub_fields'   => array(
						array(
							'key'           => 'field_hp_sec_layout',
							'name'          => 'hp_sec_layout',
							'label'         => 'Layout',
							'type'          => 'select',
							'choices'       => array(
								'image-left'  => 'Image left, text right',
								'image-right' => 'Text left, image right',
							),
							'default_value' => 'image-left',
							'wrapper'       => array( 'width' => '25' ),
						),
						$text( 'hp_sec_eyebrow', 'Eyebrow', array( 'wrapper' => array( 'width' => '35' ) ) ),
						$text( 'hp_sec_heading', 'Heading', array( 'wrapper' => array( 'width' => '40' ) ) ),
						array(
							'key'          => 'field_hp_sec_body',
							'name'         => 'hp_sec_body',
							'label'        => 'Body copy',
							'type'         => 'wysiwyg',
							'tabs'         => 'all',
							'media_upload' => 0,
							'toolbar'      => 'basic',
						),
						array(
							'key'          => 'field_hp_sec_ticks',
							'name'         => 'hp_sec_ticks',
							'label'        => 'Ticked list',
							'type'         => 'repeater',
							'layout'       => 'table',
							'button_label' => 'Add list item',
							'sub_fields'   => array(
								$text( 'hp_tick_text', 'List item' ),
							),
						),
						$image( 'hp_sec_image_main', 'Main image' ),
						$image( 'hp_sec_image_sub', 'Inset image' ),
						$link( 'hp_sec_cta', 'Button' ),
					),
				),

				/* ============================ Stats =========================== */
				$tab( 'stats', 'Stats band' ),
				array(
					'key'          => 'field_hp_stats',
					'name'         => 'hp_stats',
					'label'        => 'Stats',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => 'Add stat',
					'min'          => 0,
					'max'          => 8,
					'sub_fields'   => array(
						$text( 'hp_stat_value', 'Big text' ),
						$text( 'hp_stat_label', 'Caption' ),
					),
				),

				/* ============================ Team ============================ */
				$tab( 'team', 'Team band' ),
				$text( 'hp_team_eyebrow', 'Eyebrow', array( 'wrapper' => array( 'width' => '30' ) ) ),
				$text( 'hp_team_heading', 'Heading', array( 'wrapper' => array( 'width' => '70' ) ) ),
				array(
					'key'   => 'field_hp_team_text',
					'name'  => 'hp_team_text',
					'label' => 'Paragraph',
					'type'  => 'textarea',
					'rows'  => 3,
				),
				$link( 'hp_team_cta', 'Button', array( 'wrapper' => array( 'width' => '50' ) ) ),
				$image( 'hp_team_image', 'Team photo' ),

				/* ========================== CTA band ========================== */
				$tab( 'cta', 'CTA band' ),
				$text( 'hp_cta_heading', 'Heading' ),
				array(
					'key'   => 'field_hp_cta_text',
					'name'  => 'hp_cta_text',
					'label' => 'Paragraph',
					'type'  => 'textarea',
					'rows'  => 2,
				),
				$link( 'hp_cta_primary', 'Primary button', array( 'wrapper' => array( 'width' => '50' ) ) ),
				$link( 'hp_cta_secondary', 'Secondary button', array( 'wrapper' => array( 'width' => '50' ) ) ),

				/* ========================== Contact =========================== */
				$tab( 'contact', 'Contact, map and form' ),
				$text( 'hp_contact_eyebrow', 'Eyebrow', array( 'wrapper' => array( 'width' => '30' ) ) ),
				$text( 'hp_contact_heading', 'Heading', array( 'wrapper' => array( 'width' => '70' ) ) ),
				array(
					'key'   => 'field_hp_contact_intro',
					'name'  => 'hp_contact_intro',
					'label' => 'Intro paragraph',
					'type'  => 'textarea',
					'rows'  => 2,
				),
				array(
					'key'          => 'field_hp_contact_items',
					'name'         => 'hp_contact_items',
					'label'        => 'Contact details',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add contact detail',
					'sub_fields'   => array(
						$icon_select( 'hp_ci_icon', 'Icon', 'pin', $icons ),
						$text( 'hp_ci_label', 'Label', array( 'wrapper' => array( 'width' => '20' ) ) ),
						$text( 'hp_ci_value', 'Value', array( 'wrapper' => array( 'width' => '30' ) ) ),
						$text(
							'hp_ci_url',
							'Link',
							array(
								'wrapper'      => array( 'width' => '25' ),
								'instructions' => 'tel:, mailto: and https: are all fine. Leave empty for plain text.',
							)
						),
					),
				),
				array(
					'key'          => 'field_hp_map_embed_url',
					'name'         => 'hp_map_embed_url',
					'label'        => 'Google Map embed URL',
					'type'         => 'url',
					'instructions' => 'Google Maps: Share, Embed a map, then copy only the src="..." value.',
				),
				$text( 'hp_map_title', 'Map iframe title (accessibility)' ),
				$text( 'hp_contact_form_title', 'Form heading', array( 'wrapper' => array( 'width' => '50' ) ) ),
				$text( 'hp_contact_form_intro', 'Form intro', array( 'wrapper' => array( 'width' => '25' ) ) ),
				$text( 'hp_contact_form_link_label', 'Form intro link label', array( 'wrapper' => array( 'width' => '25' ) ) ),
				$text(
					'hp_contact_form_shortcode',
					'Contact Form 7 shortcode',
					array(
						'instructions' => 'Optional. Leave empty to use the built-in form layout.',
					)
				),
				$text( 'hp_contact_submit_label', 'Submit button label', array( 'wrapper' => array( 'width' => '50' ) ) ),
				$text( 'hp_contact_form_note', 'Note under the form', array( 'wrapper' => array( 'width' => '50' ) ) ),

				/* ======================= Phone and buttons ==================== */
				$tab( 'phone', 'Phone and floating button' ),
				$text( 'hp_phone_display', 'Phone number (displayed)', array( 'wrapper' => array( 'width' => '33' ) ) ),
				$text(
					'hp_phone_dial',
					'Phone number (dialled)',
					array(
						'wrapper'      => array( 'width' => '33' ),
						'instructions' => 'Digits only, used for tel: links.',
					)
				),
				array(
					'key'           => 'field_hp_booking_url',
					'name'          => 'hp_booking_url',
					'label'         => 'Booking system URL',
					'type'          => 'url',
					'wrapper'       => array( 'width' => '34' ),
					'instructions'  => 'Used as the fallback for any Book Now button with no link set.',
				),
				array(
					'key'           => 'field_hp_floating_call_show',
					'name'          => 'hp_floating_call_show',
					'label'         => 'Show the floating call button on mobile',
					'type'          => 'true_false',
					'ui'            => 1,
					'default_value' => 1,
					'wrapper'       => array( 'width' => '50' ),
				),
				$text( 'hp_floating_call_label', 'Floating button label', array( 'wrapper' => array( 'width' => '50' ) ) ),
			),
		)
	);
}
add_action( 'acf/init', 'pallara_hp_register_fields' );
