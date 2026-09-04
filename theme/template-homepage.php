<?php
/**
 * Template Name: Homepage - Pallara Redesign
 *
 * Conversion-focused homepage. All copy comes from the ACF field group
 * registered in inc/homepage/acf-fields.php; every field falls back to
 * pallara_hp_defaults() so the page is never half-empty.
 *
 * The global header and footer are untouched - this template only owns what
 * sits between them.
 *
 * @package Pallara_Medical
 */

defined( 'ABSPATH' ) || exit;

get_header();

$pm_book_url   = pallara_hp_get( 'hp_booking_url' );
$pm_phone      = pallara_hp_phone();
$pm_hero_image = pallara_hp_image_url( pallara_hp_get( 'hp_hero_image' ), 'full' );
$pm_hero_style = $pm_hero_image ? sprintf( '--pm-hero-image:url(%s)', esc_url( $pm_hero_image ) ) : '';
?>

<div class="pm-home">
	<?php pallara_hp_icon_sprite(); ?>

	<?php // ============================ HERO ============================ ?>
	<section class="pm-hero"<?php echo $pm_hero_style ? ' style="' . esc_attr( $pm_hero_style ) . '"' : ''; ?>>
		<div class="pm-container">
			<div class="pm-hero-copy">
				<?php if ( pallara_hp_get( 'hp_hero_eyebrow' ) ) : ?>
					<p class="pm-eyebrow" style="color:var(--pm-blue-soft)"><?php echo esc_html( pallara_hp_get( 'hp_hero_eyebrow' ) ); ?></p>
				<?php endif; ?>

				<h1>
					<?php echo esc_html( pallara_hp_get( 'hp_hero_heading' ) ); ?>
					<?php if ( pallara_hp_get( 'hp_hero_heading_accent' ) ) : ?>
						<em><?php echo esc_html( pallara_hp_get( 'hp_hero_heading_accent' ) ); ?></em>
					<?php endif; ?>
				</h1>

				<?php if ( pallara_hp_get( 'hp_hero_text' ) ) : ?>
					<p><?php echo esc_html( pallara_hp_get( 'hp_hero_text' ) ); ?></p>
				<?php endif; ?>

				<?php $pm_badges = pallara_hp_rows( 'hp_hero_badges' ); ?>
				<?php if ( $pm_badges ) : ?>
					<ul class="pm-hero-badges">
						<?php foreach ( $pm_badges as $pm_badge ) : ?>
							<li><?php pallara_hp_icon( 'check' ); ?> <?php echo esc_html( pallara_hp_sub( $pm_badge, 'hp_badge_text' ) ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<div class="pm-hero-actions">
					<?php
					pallara_hp_button( pallara_hp_get( 'hp_hero_cta_primary' ), 'pm-btn pm-btn--primary pm-btn--lg', 'calendar', 'Book Now' );
					pallara_hp_button( pallara_hp_get( 'hp_hero_cta_secondary' ), 'pm-btn pm-btn--ghost pm-btn--lg', 'phone', $pm_phone['display'] );
					?>
				</div>

				<?php if ( pallara_hp_get( 'hp_hero_note' ) ) : ?>
					<p class="pm-hero-note">
						<?php pallara_hp_icon( 'pin' ); ?>
						<span><?php echo wp_kses_post( pallara_hp_get( 'hp_hero_note' ) ); ?></span>
					</p>
				<?php endif; ?>
			</div>

			<?php // -------------------- Hero booking form -------------------- ?>
			<div class="pm-booking-card">
				<h2><?php echo esc_html( pallara_hp_get( 'hp_form_title' ) ); ?></h2>

				<?php if ( pallara_hp_get( 'hp_form_intro' ) ) : ?>
					<p class="pm-form-sub">
						<?php echo esc_html( pallara_hp_get( 'hp_form_intro' ) ); ?>
						<a href="<?php echo esc_url( $pm_book_url ); ?>" target="_blank" rel="noopener"><strong><?php echo esc_html( pallara_hp_get( 'hp_form_intro_link_label' ) ); ?></strong></a>
					</p>
				<?php endif; ?>

				<?php
				$pm_form_shortcode = pallara_hp_get( 'hp_form_shortcode' );

				if ( $pm_form_shortcode ) {
					echo do_shortcode( $pm_form_shortcode );
				} else {
					pallara_hp_render_form(
						array(
							'id_prefix'    => 'pm-hero',
							'submit_label' => pallara_hp_get( 'hp_form_submit_label' ),
							'note'         => pallara_hp_get( 'hp_form_note' ),
							'note_icon'    => 'shield',
						)
					);
				}
				?>
			</div>
		</div>
	</section>

	<?php // ======================== QUICK INFO CARDS ======================== ?>
	<?php $pm_cards = pallara_hp_rows( 'hp_quick_cards' ); ?>
	<?php if ( $pm_cards ) : ?>
		<section class="pm-quickbar">
			<div class="pm-container">
				<div class="pm-quickbar-grid">
					<?php foreach ( $pm_cards as $pm_card ) : ?>
						<?php $pm_card_link = pallara_hp_link( pallara_hp_sub( $pm_card, 'hp_qc_link' ) ); ?>
						<div class="pm-quick-card pm-reveal">
							<span class="pm-qi"><?php pallara_hp_icon( pallara_hp_sub( $pm_card, 'hp_qc_icon', 'clock' ) ); ?></span>
							<div>
								<h3><?php echo esc_html( pallara_hp_sub( $pm_card, 'hp_qc_title' ) ); ?></h3>

								<?php if ( pallara_hp_sub( $pm_card, 'hp_qc_text' ) ) : ?>
									<p><?php echo esc_html( pallara_hp_sub( $pm_card, 'hp_qc_text' ) ); ?></p>
								<?php endif; ?>

								<?php foreach ( (array) pallara_hp_sub( $pm_card, 'hp_qc_rows', array() ) as $pm_row ) : ?>
									<div class="pm-hours-row">
										<span><?php echo esc_html( pallara_hp_sub( $pm_row, 'hp_qc_row_label' ) ); ?></span>
										<strong><?php echo esc_html( pallara_hp_sub( $pm_row, 'hp_qc_row_value' ) ); ?></strong>
									</div>
								<?php endforeach; ?>

								<?php if ( $pm_card_link ) : ?>
									<a class="pm-qlink" href="<?php echo esc_url( $pm_card_link['url'] ); ?>"<?php echo '_blank' === $pm_card_link['target'] ? ' target="_blank" rel="noopener"' : ''; ?>>
										<?php echo esc_html( $pm_card_link['title'] ); ?> <?php pallara_hp_icon( 'arrow' ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php // ============================ SERVICES ============================ ?>
	<?php $pm_services = pallara_hp_rows( 'hp_services' ); ?>
	<section class="pm-section" id="services">
		<div class="pm-container">
			<div class="pm-section-head pm-section-head--center">
				<?php if ( pallara_hp_get( 'hp_services_eyebrow' ) ) : ?>
					<p class="pm-eyebrow pm-eyebrow--center"><?php echo esc_html( pallara_hp_get( 'hp_services_eyebrow' ) ); ?></p>
				<?php endif; ?>
				<h2><?php echo esc_html( pallara_hp_get( 'hp_services_heading' ) ); ?></h2>
				<?php if ( pallara_hp_get( 'hp_services_intro' ) ) : ?>
					<p class="pm-lead"><?php echo esc_html( pallara_hp_get( 'hp_services_intro' ) ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $pm_services ) : ?>
				<div class="pm-services-grid">
					<?php foreach ( $pm_services as $pm_service ) : ?>
						<article class="pm-service-card pm-reveal">
							<span class="pm-si"><?php pallara_hp_icon( pallara_hp_sub( $pm_service, 'hp_service_icon', 'stetho' ) ); ?></span>
							<h3><?php echo esc_html( pallara_hp_sub( $pm_service, 'hp_service_title' ) ); ?></h3>
							<p><?php echo esc_html( pallara_hp_sub( $pm_service, 'hp_service_text' ) ); ?></p>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="pm-services-cta">
				<?php
				pallara_hp_button( pallara_hp_get( 'hp_services_cta_primary' ), 'pm-btn pm-btn--primary pm-btn--lg', 'calendar', 'Book Now' );
				pallara_hp_button( pallara_hp_get( 'hp_services_cta_secondary' ), 'pm-btn pm-btn--outline pm-btn--lg', '', 'View all services' );
				?>
			</div>
		</div>
	</section>

	<?php // ======================= CONTENT SECTIONS ======================= ?>
	<?php $pm_sections = pallara_hp_rows( 'hp_sections' ); ?>
	<?php if ( $pm_sections ) : ?>
		<section class="pm-section pm-section--tint">
			<div class="pm-container">
				<?php foreach ( $pm_sections as $pm_index => $pm_sec ) : ?>
					<?php $pm_reversed = 'image-right' === pallara_hp_sub( $pm_sec, 'hp_sec_layout', 'image-left' ); ?>
					<div class="pm-split<?php echo $pm_reversed ? ' pm-split--rev' : ''; ?> pm-reveal">
						<div class="pm-split-media">
							<?php
							pallara_hp_image(
								pallara_hp_sub( $pm_sec, 'hp_sec_image_main' ),
								pallara_hp_sub( $pm_sec, 'hp_sec_heading' ),
								array(
									'class'   => 'pm-img-main',
									'size'    => 'large',
									'loading' => 0 === $pm_index ? 'eager' : 'lazy',
								)
							);

							pallara_hp_image(
								pallara_hp_sub( $pm_sec, 'hp_sec_image_sub' ),
								'',
								array(
									'class' => 'pm-img-sub',
									'size'  => 'medium_large',
								)
							);
							?>
						</div>

						<div class="pm-split-body">
							<?php if ( pallara_hp_sub( $pm_sec, 'hp_sec_eyebrow' ) ) : ?>
								<p class="pm-eyebrow"><?php echo esc_html( pallara_hp_sub( $pm_sec, 'hp_sec_eyebrow' ) ); ?></p>
							<?php endif; ?>

							<h2><?php echo esc_html( pallara_hp_sub( $pm_sec, 'hp_sec_heading' ) ); ?></h2>

							<?php echo wp_kses_post( pallara_hp_sub( $pm_sec, 'hp_sec_body' ) ); ?>

							<?php $pm_ticks = (array) pallara_hp_sub( $pm_sec, 'hp_sec_ticks', array() ); ?>
							<?php if ( $pm_ticks ) : ?>
								<ul class="pm-ticks">
									<?php foreach ( $pm_ticks as $pm_tick ) : ?>
										<li><?php pallara_hp_icon( 'check' ); ?> <?php echo esc_html( pallara_hp_sub( $pm_tick, 'hp_tick_text' ) ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php pallara_hp_button( pallara_hp_sub( $pm_sec, 'hp_sec_cta' ), 'pm-btn pm-btn--primary', '', 'Book Now' ); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php // ============================= STATS ============================= ?>
	<?php $pm_stats = pallara_hp_rows( 'hp_stats' ); ?>
	<?php if ( $pm_stats ) : ?>
		<section class="pm-section pm-stats">
			<div class="pm-container">
				<div class="pm-stats-grid">
					<?php foreach ( $pm_stats as $pm_stat ) : ?>
						<div class="pm-stat">
							<b><?php echo esc_html( pallara_hp_sub( $pm_stat, 'hp_stat_value' ) ); ?></b>
							<span><?php echo esc_html( pallara_hp_sub( $pm_stat, 'hp_stat_label' ) ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php // ============================= TEAM ============================== ?>
	<section class="pm-section pm-team">
		<div class="pm-container">
			<div>
				<?php if ( pallara_hp_get( 'hp_team_eyebrow' ) ) : ?>
					<p class="pm-eyebrow"><?php echo esc_html( pallara_hp_get( 'hp_team_eyebrow' ) ); ?></p>
				<?php endif; ?>
				<h2><?php echo esc_html( pallara_hp_get( 'hp_team_heading' ) ); ?></h2>
				<p><?php echo esc_html( pallara_hp_get( 'hp_team_text' ) ); ?></p>
				<?php pallara_hp_button( pallara_hp_get( 'hp_team_cta' ), 'pm-btn pm-btn--white pm-btn--lg', 'calendar', 'Book With Our Team' ); ?>
			</div>
			<div class="pm-team-media">
				<?php pallara_hp_image( pallara_hp_get( 'hp_team_image' ), 'The Pallara Medical team', array( 'size' => 'large' ) ); ?>
			</div>
		</div>
	</section>

	<?php // =========================== CTA BAND ============================ ?>
	<section class="pm-section pm-cta-band">
		<div class="pm-container">
			<h2><?php echo esc_html( pallara_hp_get( 'hp_cta_heading' ) ); ?></h2>
			<p><?php echo esc_html( pallara_hp_get( 'hp_cta_text' ) ); ?></p>
			<div class="pm-btns">
				<?php
				pallara_hp_button( pallara_hp_get( 'hp_cta_primary' ), 'pm-btn pm-btn--white pm-btn--lg', 'calendar', 'Book Now' );
				pallara_hp_button( pallara_hp_get( 'hp_cta_secondary' ), 'pm-btn pm-btn--ghost pm-btn--lg', 'phone', $pm_phone['display'] );
				?>
			</div>
		</div>
	</section>

	<?php // ==================== CONTACT + MAP + FORM ======================= ?>
	<section class="pm-section" id="contact">
		<div class="pm-container">
			<div class="pm-section-head">
				<?php if ( pallara_hp_get( 'hp_contact_eyebrow' ) ) : ?>
					<p class="pm-eyebrow"><?php echo esc_html( pallara_hp_get( 'hp_contact_eyebrow' ) ); ?></p>
				<?php endif; ?>
				<h2><?php echo esc_html( pallara_hp_get( 'hp_contact_heading' ) ); ?></h2>
				<?php if ( pallara_hp_get( 'hp_contact_intro' ) ) : ?>
					<p class="pm-lead"><?php echo esc_html( pallara_hp_get( 'hp_contact_intro' ) ); ?></p>
				<?php endif; ?>
			</div>

			<div class="pm-contact-grid">
				<div>
					<?php $pm_items = pallara_hp_rows( 'hp_contact_items' ); ?>
					<?php if ( $pm_items ) : ?>
						<ul class="pm-contact-list">
							<?php foreach ( $pm_items as $pm_item ) : ?>
								<?php $pm_item_url = pallara_hp_sub( $pm_item, 'hp_ci_url' ); ?>
								<li>
									<span class="pm-ci"><?php pallara_hp_icon( pallara_hp_sub( $pm_item, 'hp_ci_icon', 'pin' ) ); ?></span>
									<div>
										<b><?php echo esc_html( pallara_hp_sub( $pm_item, 'hp_ci_label' ) ); ?></b>
										<?php if ( $pm_item_url ) : ?>
											<a href="<?php echo esc_url( $pm_item_url ); ?>"<?php echo 0 === strpos( $pm_item_url, 'http' ) ? ' target="_blank" rel="noopener"' : ''; ?>>
												<?php echo esc_html( pallara_hp_sub( $pm_item, 'hp_ci_value' ) ); ?>
											</a>
										<?php else : ?>
											<span><?php echo esc_html( pallara_hp_sub( $pm_item, 'hp_ci_value' ) ); ?></span>
										<?php endif; ?>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( pallara_hp_get( 'hp_map_embed_url' ) ) : ?>
						<div class="pm-map-embed">
							<iframe
								title="<?php echo esc_attr( pallara_hp_get( 'hp_map_title' ) ); ?>"
								src="<?php echo esc_url( pallara_hp_get( 'hp_map_embed_url' ) ); ?>"
								allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
						</div>
					<?php endif; ?>
				</div>

				<div class="pm-contact-form">
					<h2 style="font-size:clamp(22px,1.9vw,30px)"><?php echo esc_html( pallara_hp_get( 'hp_contact_form_title' ) ); ?></h2>

					<?php if ( pallara_hp_get( 'hp_contact_form_intro' ) ) : ?>
						<p class="pm-form-sub">
							<?php echo esc_html( pallara_hp_get( 'hp_contact_form_intro' ) ); ?>
							<a href="<?php echo esc_url( $pm_book_url ); ?>" target="_blank" rel="noopener"><strong><?php echo esc_html( pallara_hp_get( 'hp_contact_form_link_label' ) ); ?></strong></a>.
						</p>
					<?php endif; ?>

					<?php
					$pm_contact_shortcode = pallara_hp_get( 'hp_contact_form_shortcode' );

					if ( $pm_contact_shortcode ) {
						echo do_shortcode( $pm_contact_shortcode );
					} else {
						pallara_hp_render_form(
							array(
								'id_prefix'    => 'pm-contact',
								'submit_label' => pallara_hp_get( 'hp_contact_submit_label' ),
								'note'         => pallara_hp_get( 'hp_contact_form_note' ),
								'note_icon'    => 'shield',
								'notes_rows'   => 4,
							)
						);
					}
					?>
				</div>
			</div>
		</div>
	</section>
</div>

<?php
get_footer();
