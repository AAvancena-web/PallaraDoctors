<?php
/**
 * Default homepage content.
 *
 * This is the single source of truth for the redesign copy. It is used in
 * two places:
 *
 * 1. The template falls back to these values for any field that is empty,
 *    so the page renders correctly before (or without) seeding.
 * 2. The one-time seeder writes them into the ACF fields so the client can
 *    edit real content in the admin.
 *
 * Array keys match ACF field names exactly. Repeater values are arrays of
 * rows keyed by sub-field name. Image fields hold a URL string here; the
 * seeder swaps in the attachment ID when the file already exists in the
 * media library, and the template falls back to the URL when it does not.
 *
 * @package Pallara_Medical
 */

defined( 'ABSPATH' ) || exit;

/**
 * Base URL for the placeholder imagery pulled from the live site.
 *
 * Filterable so the images can be repointed in one place once the client
 * uploads the final artwork.
 *
 * @return string
 */
function pallara_hp_image_base() {
	return apply_filters( 'pallara_hp_image_base', 'https://www.pallaradoctors.com.au/wp-content/uploads' );
}

/**
 * Booking system URL used by every "Book Now" call to action.
 *
 * @return string
 */
function pallara_hp_booking_url() {
	return apply_filters(
		'pallara_hp_booking_url',
		'https://automedsystems.com.au/ams/clinics/12390/pallara-medical-pallara-4110/doctors'
	);
}

/**
 * The full set of default field values.
 *
 * @return array
 */
function pallara_hp_defaults() {
	static $defaults = null;

	if ( null !== $defaults ) {
		return $defaults;
	}

	$book    = pallara_hp_booking_url();
	$uploads = pallara_hp_image_base();

	$book_link = array(
		'title'  => 'Book Now',
		'url'    => $book,
		'target' => '_blank',
	);

	$defaults = array(

		/* ---------- Global ---------- */
		'hp_phone_display'      => '07 3100 7111',
		'hp_phone_dial'         => '0731007111',
		'hp_booking_url'        => $book,
		'hp_floating_call_show' => 1,
		'hp_floating_call_label' => 'Call Now',

		/* ---------- Hero ---------- */
		'hp_hero_eyebrow'          => 'GP Clinic in Pallara',
		'hp_hero_heading'          => 'See a Doctor in Pallara,',
		'hp_hero_heading_accent'   => 'Often the Same Day',
		'hp_hero_text'             => "From routine check-ups to skin checks, women's health and chronic care, our GPs look after your whole family at every stage of life. Booking online takes less than a minute.",
		'hp_hero_badges'           => array(
			array( 'hp_badge_text' => 'Same-day appointments' ),
			array( 'hp_badge_text' => 'Open 6 days a week' ),
			array( 'hp_badge_text' => 'On-site procedures' ),
			array( 'hp_badge_text' => "Family & women's health" ),
		),
		'hp_hero_cta_primary'      => $book_link,
		'hp_hero_cta_secondary'    => array(
			'title'  => '07 3100 7111',
			'url'    => 'tel:0731007111',
			'target' => '',
		),
		'hp_hero_note'             => '<strong>Pallara Shopping Village</strong> with free on-site parking at the door',
		'hp_hero_image'            => $uploads . '/2025/07/homepage-banner.png',

		/* ---------- Hero booking form ---------- */
		'hp_form_title'            => 'Request an Appointment',
		'hp_form_intro'            => 'Prefer to lock in a time right now?',
		'hp_form_intro_link_label' => 'Book online instantly',
		'hp_form_shortcode'        => '',
		'hp_form_doctors'          => array(
			array( 'hp_doctor_name' => 'Dr Navjot Brar' ),
		),
		'hp_form_time_slots'       => array(
			array( 'hp_slot_label' => '8:30 AM – 10:00 AM' ),
			array( 'hp_slot_label' => '10:00 AM – 12:00 PM' ),
			array( 'hp_slot_label' => '12:00 PM – 2:00 PM' ),
			array( 'hp_slot_label' => '2:00 PM – 4:30 PM' ),
		),
		'hp_form_submit_label'     => 'Request Appointment',
		'hp_form_note'             => 'Your details stay private and are only used to arrange your appointment.',

		/* ---------- Quick info cards ---------- */
		'hp_quick_cards' => array(
			array(
				'hp_qc_icon'  => 'clock',
				'hp_qc_title' => 'Opening Hours',
				'hp_qc_text'  => '',
				'hp_qc_rows'  => array(
					array(
						'hp_qc_row_label' => 'Monday – Friday',
						'hp_qc_row_value' => '8:30am – 4:30pm',
					),
					array(
						'hp_qc_row_label' => 'Saturday',
						'hp_qc_row_value' => 'By appointment',
					),
					array(
						'hp_qc_row_label' => 'Sunday',
						'hp_qc_row_value' => 'Closed',
					),
				),
				'hp_qc_link'  => '',
			),
			array(
				'hp_qc_icon'  => 'calendar',
				'hp_qc_title' => 'Book Your Appointment',
				'hp_qc_text'  => 'Online bookings are open for all doctors. Walk-ins are offered the next available GP.',
				'hp_qc_rows'  => array(),
				'hp_qc_link'  => array(
					'title'  => 'Book now',
					'url'    => $book,
					'target' => '_blank',
				),
			),
			array(
				'hp_qc_icon'  => 'shield',
				'hp_qc_title' => 'Vaccinations & COVID',
				'hp_qc_text'  => 'Pfizer and Moderna vaccines available, along with flu shots and travel vaccinations.',
				'hp_qc_rows'  => array(),
				'hp_qc_link'  => array(
					'title'  => 'Reserve a spot',
					'url'    => $book,
					'target' => '_blank',
				),
			),
		),

		/* ---------- Services ---------- */
		'hp_services_eyebrow' => 'Our Services',
		'hp_services_heading' => 'Comprehensive Care, Tailored For You',
		'hp_services_intro'   => "One clinic for your family's everyday health needs: check-ups, chronic care, procedures and preventative health, all under one roof in Pallara.",
		'hp_services'         => array(
			array(
				'hp_service_icon'  => 'stetho',
				'hp_service_title' => 'General Consultations',
				'hp_service_text'  => 'Everyday illnesses, health concerns, medical certificates and referrals with a GP who knows your history.',
			),
			array(
				'hp_service_icon'  => 'baby',
				'hp_service_title' => 'Child Health & Immunisations',
				'hp_service_text'  => 'Developmental checks, childhood vaccinations and gentle care for common paediatric illnesses.',
			),
			array(
				'hp_service_icon'  => 'female',
				'hp_service_title' => "Women's Health",
				'hp_service_text'  => 'Family planning, Implanon and Mirena, cervical screening, antenatal support and menopause management.',
			),
			array(
				'hp_service_icon'  => 'scan',
				'hp_service_title' => 'Skin Checks & Excisions',
				'hp_service_text'  => 'Full skin examinations, mole checks and on-site removal of suspicious lesions.',
			),
			array(
				'hp_service_icon'  => 'shield',
				'hp_service_title' => 'Chronic Disease Management',
				'hp_service_text'  => 'Structured care plans for diabetes, asthma, hypertension and heart disease, reviewed regularly.',
			),
			array(
				'hp_service_icon'  => 'scalpel',
				'hp_service_title' => 'Minor Surgery & Procedures',
				'hp_service_text'  => 'Cysts, ingrown toenails, wound suturing, dressings and iron infusions performed in-clinic.',
			),
			array(
				'hp_service_icon'  => 'calendar',
				'hp_service_title' => 'Driver & WorkCover Medicals',
				'hp_service_text'  => 'Commercial, taxi and rideshare licence assessments, WorkCover claims and pre-employment checks.',
			),
			array(
				'hp_service_icon'  => 'scan',
				'hp_service_title' => 'Referrals, X-Rays & Pathology',
				'hp_service_text'  => 'Fast referrals to trusted local imaging and labs, with results reviewed by your own GP.',
			),
		),
		'hp_services_cta_primary'   => $book_link,
		'hp_services_cta_secondary' => array(
			'title'  => 'View all services',
			'url'    => '/medical-services-pallara/',
			'target' => '',
		),

		/* ---------- Alternating content sections ---------- */
		'hp_sections' => array(
			array(
				'hp_sec_layout'    => 'image-left',
				'hp_sec_eyebrow'   => 'About Pallara Medical',
				'hp_sec_heading'   => 'A Local GP Clinic Built Around Your Family',
				'hp_sec_body'      => '<p>Quality healthcare begins with trust, genuine compassion and a strong connection to our community. From the moment you walk through our doors, your wellbeing is the priority.</p>',
				'hp_sec_ticks'     => array(
					array( 'hp_tick_text' => 'Experienced GPs and friendly nursing staff' ),
					array( 'hp_tick_text' => 'Continuity of care, so you see the same doctor each visit' ),
					array( 'hp_tick_text' => 'Modern clinic with on-site treatment rooms' ),
					array( 'hp_tick_text' => 'Free parking at Pallara Shopping Village' ),
				),
				'hp_sec_image_main' => $uploads . '/2025/06/about-img.jpg',
				'hp_sec_image_sub'  => $uploads . '/2025/06/about-one.jpg',
				'hp_sec_cta'        => $book_link,
			),
			array(
				'hp_sec_layout'    => 'image-right',
				'hp_sec_eyebrow'   => 'Every Stage of Life',
				'hp_sec_heading'   => 'Care That Grows With You',
				'hp_sec_body'      => "<p>For our youngest patients we offer immunisations, developmental checks and gentle care for common childhood illnesses. As families grow, we support adolescent health, adult screening and preventative advice.</p><p>Women's health is a core strength, from contraception and antenatal care through to cervical screening and menopause management. Men's health is equally prioritised with preventative screening and lifestyle support.</p>",
				'hp_sec_ticks'     => array(),
				'hp_sec_image_main' => $uploads . '/2025/07/supporting-every-stage-img-large.png',
				'hp_sec_image_sub'  => $uploads . '/2025/07/supporting-every-stage-img-small.png',
				'hp_sec_cta'        => $book_link,
			),
			array(
				'hp_sec_layout'    => 'image-left',
				'hp_sec_eyebrow'   => 'Chronic & Preventative Care',
				'hp_sec_heading'   => 'Lifelong Health Management',
				'hp_sec_body'      => '<p>Our GPs create structured care plans for diabetes, asthma, hypertension and heart disease, with regular monitoring, medication reviews and coordinated care alongside your specialists.</p><p>We also provide comprehensive health assessments for older adults and support for mental health and emotional wellbeing at every age.</p>',
				'hp_sec_ticks'     => array(),
				'hp_sec_image_main' => $uploads . '/2025/07/lifelong-health-img-large.png',
				'hp_sec_image_sub'  => $uploads . '/2025/07/lifelong-health-img-small.png',
				'hp_sec_cta'        => $book_link,
			),
		),

		/* ---------- Stats band ---------- */
		'hp_stats' => array(
			array(
				'hp_stat_value' => 'Same Day',
				'hp_stat_label' => 'Appointments often available',
			),
			array(
				'hp_stat_value' => '6 Days',
				'hp_stat_label' => 'Open Monday to Saturday',
			),
			array(
				'hp_stat_value' => '20+',
				'hp_stat_label' => 'Services offered on site',
			),
			array(
				'hp_stat_value' => '60 sec',
				'hp_stat_label' => 'Average online booking time',
			),
		),

		/* ---------- Team band ---------- */
		'hp_team_eyebrow' => 'Our Team',
		'hp_team_heading' => 'Dedicated Doctors, Nurses & Healthcare Practitioners',
		'hp_team_text'    => 'Pallara Medical takes pride in its roster of local healthcare practitioners. We partner with you for your best health outcomes, and our friendly staff are always ready to help.',
		'hp_team_cta'     => array(
			'title'  => 'Book With Our Team',
			'url'    => $book,
			'target' => '_blank',
		),
		'hp_team_image'   => $uploads . '/2025/06/our-team.jpg',

		/* ---------- CTA band ---------- */
		'hp_cta_heading'   => 'Ready to See a Doctor in Pallara?',
		'hp_cta_text'      => 'Choose your doctor, pick a time that suits you and confirm in under a minute, with no phone queue to wait in.',
		'hp_cta_primary'   => $book_link,
		'hp_cta_secondary' => array(
			'title'  => 'Call 07 3100 7111',
			'url'    => 'tel:0731007111',
			'target' => '',
		),

		/* ---------- Contact ---------- */
		'hp_contact_eyebrow' => 'Contact Us',
		'hp_contact_heading' => 'Find Us, Call Us or Send a Message',
		'hp_contact_intro'   => "We're in Pallara Shopping Village with free parking at the door. Send an enquiry and our reception team will be in touch.",
		'hp_contact_items'   => array(
			array(
				'hp_ci_icon'  => 'pin',
				'hp_ci_label' => 'Visit us',
				'hp_ci_value' => 'Pallara Shopping Village, Tenancy T4.1 – 201 Gooderham Rd, Pallara QLD 4110',
				'hp_ci_url'   => 'https://maps.app.goo.gl/7j12vx4kuDp2psLbA',
			),
			array(
				'hp_ci_icon'  => 'phone',
				'hp_ci_label' => 'Phone',
				'hp_ci_value' => '07 3100 7111',
				'hp_ci_url'   => 'tel:0731007111',
			),
			array(
				'hp_ci_icon'  => 'fax',
				'hp_ci_label' => 'Fax',
				'hp_ci_value' => '07 3100 7110',
				'hp_ci_url'   => 'tel:0731007110',
			),
			array(
				'hp_ci_icon'  => 'mail',
				'hp_ci_label' => 'Email',
				'hp_ci_value' => 'info@pallaradoctors.com.au',
				'hp_ci_url'   => 'mailto:info@pallaradoctors.com.au',
			),
			array(
				'hp_ci_icon'  => 'clock',
				'hp_ci_label' => 'Hours',
				'hp_ci_value' => 'Mon – Fri 8:30am – 4:30pm · Sat by appointment · Sun closed',
				'hp_ci_url'   => '',
			),
		),
		'hp_map_embed_url'         => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3535.5424851160637!2d153.00839507636888!3d-27.60771062260237!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6b914f00117c90f9%3A0xa7696e267832c141!2sPallara%20Shopping%20Village!5e0!3m2!1sen!2sau!4v1757056335106!5m2!1sen!2sau',
		'hp_map_title'             => 'Map to Pallara Medical, Pallara Shopping Village',
		'hp_contact_form_title'    => 'Make Your Appointment',
		'hp_contact_form_intro'    => "Complete the form and we'll be in touch shortly, or",
		'hp_contact_form_link_label' => 'book online instantly',
		'hp_contact_form_shortcode'  => '',
		'hp_contact_submit_label'    => 'Get Appointment',
		'hp_contact_form_note'       => 'For medical emergencies call 000. This form is not monitored after hours.',
	);

	return $defaults;
}
