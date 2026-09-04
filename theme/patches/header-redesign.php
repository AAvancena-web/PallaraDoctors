<?php
/**
 * OPTIONAL drop-in replacement for the child theme's header.php.
 *
 * This is your current header.php with the header markup rearranged into the
 * redesign layout. Every PHP call, widget area, setting and script hook is
 * unchanged - only the order and nesting of the wrapper divs differs:
 *
 *   Row 1  .pm-topbar      dark strip: contact details left, socials right
 *   Row 2  .site-header-inner   logo | navigation | phone + BOOK NOW
 *
 * The banner block and everything after </header> is byte-for-byte your
 * existing file.
 *
 * TO USE: back up header.php, then copy this file over it (renaming it to
 * header.php). To revert, restore your backup - nothing else depends on it.
 * Without it the site still gets the redesign treatment from
 * assets/css/global-header-footer.css, just with the nav on its own row.
 *
 * @license GPL 2.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-YTWBY8CBTR"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-YTWBY8CBTR');
</script>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KQ2RZ9DJ');</script>
<!-- End Google Tag Manager -->
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<link rel="profile" href="https://gmpg.org/xfn/11">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KQ2RZ9DJ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
do_action( 'siteorigin_corp_body_top' );
?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'siteorigin-corp' ); ?></a>

	<?php if ( class_exists( 'Woocommerce' ) && is_store_notice_showing() ) { ?>
		<div id="topbar">
			<?php siteorigin_corp_woocommerce_demo_store(); ?>
		</div><!-- #topbar -->
	<?php
	}
	do_action( 'siteorigin_corp_header_before' );

	if ( siteorigin_page_setting( 'header', true ) ) {
		$header_classes = '';
		if ( siteorigin_setting( 'header_layout' ) == 'centered' ) {
			$header_classes .= ' centered';
		}

		if ( siteorigin_setting( 'header_sticky' ) ) {
			$header_classes .= ' sticky';
		}

		if ( siteorigin_setting( 'navigation_mobile_menu' ) ) {
			$header_classes .= ' mobile-menu';
		}

		// Phone and booking details for the header CTA, from the homepage ACF
		// fields with the packaged defaults as a fallback.
		$pm_phone = function_exists( 'pallara_hp_phone' )
			? pallara_hp_phone()
			: array(
				'display' => '07 3100 7111',
				'dial'    => '0731007111',
			);
		$pm_book  = function_exists( 'pallara_hp_get' ) ? pallara_hp_get( 'hp_booking_url' ) : '';
		?>
		<header id="masthead" class="site-header pm-header-v2<?php echo $header_classes; ?>" <?php if ( siteorigin_setting( 'header_scales' ) ) echo 'data-scale-logo="true"'; ?> >
<div class="mobile-menu">

			<?php // Row 1: dark utility strip. ?>
			<div class="pm-topbar">
				<div class="corp-container">
					<div class="pm-topbar-inner">
						<div class="header-left-bars">
						<?php if ( is_active_sidebar( 'header-left-bar' ) ) : ?>
							<?php dynamic_sidebar( 'header-left-bar' ); ?>
						<?php endif; ?>
						</div>

						<div class="header-social-icons">
							<a href="https://www.facebook.com/share/14Z8hMAbeik/?mibextid=wwXIfr" target="_blank" rel="noopener" aria-label="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
							<a href="https://www.instagram.com/pallaramedical" target="_blank" rel="noopener" aria-label="Instagram"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
						</div>
					</div>
				</div>
			</div><!-- .pm-topbar -->

			<?php // Row 2: logo, navigation, phone and BOOK NOW. ?>
			<div class="corp-container">
				<div class="site-header-inner">

					<div class="site-branding">
						<?php siteorigin_corp_display_logo(); ?>
						<?php if ( siteorigin_setting( 'header_site_description' ) ) { ?>
							<p class="site-description"><?php bloginfo( 'description' ); ?></p>
						<?php } ?>
					</div><!-- .site-branding -->

					<div class="header-menu">
						<?php $mega_menu_active = function_exists( 'ubermenu' ) || function_exists( 'max_mega_menu_is_enabled' ) && max_mega_menu_is_enabled( 'menu-1' ); ?>

						<?php
						$nav_classes = '';
						if ( siteorigin_setting( 'navigation_menu_link_hover_underline' ) ) {
							$nav_classes .= ' link-underline ';
						}

						if ( $mega_menu_active ) {
							$nav_classes .= ' mega-menu';
						}
						?>

						<nav id="site-navigation" class="main-navigation<?php echo $nav_classes; ?>">

							<?php
							if ( siteorigin_setting( 'navigation_header_menu' ) ) {
								wp_nav_menu( array( 'theme_location' => 'menu-1', 'menu_id' => 'primary-menu' ) );
							}
							?>

							<?php
							if ( function_exists( 'is_woocommerce' ) && siteorigin_setting( 'woocommerce_mini_cart' ) && ! $mega_menu_active ) {
								siteorigin_corp_woocommerce_mini_cart();
							}
							?>

							<?php if ( siteorigin_setting( 'navigation_menu_search' ) && ! $mega_menu_active ) { ?>
								<button id="search-button" class="search-toggle" aria-label="<?php esc_attr_e( 'Open Search', 'siteorigin-corp' ); ?>">
									<span class="open"><?php siteorigin_corp_display_icon( 'search' ); ?></span>
								</button>
							<?php } ?>

							<div class="mobile-header-social">
								<a href="https://www.facebook.com/share/14Z8hMAbeik/?mibextid=wwXIfr" target="_blank" rel="noopener" aria-label="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
								<a href="https://www.instagram.com/pallaramedical" target="_blank" rel="noopener" aria-label="Instagram"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
							</div>
							<div class="mobile-call-menu">
								<?php if ( is_active_sidebar( 'call-icon-mobile' ) ) : ?>
									<?php dynamic_sidebar( 'call-icon-mobile' ); ?>
								<?php endif; ?>
							</div>

							<button class="hamburger--collapse reflex-menu-toggle" type="button">
								<span></span><span></span><span></span>
							</button>

						</nav><!-- #site-navigation -->
					</div><!-- .header-menu -->

					<div class="pm-header-actions">
						<a class="pm-header-phone" href="tel:<?php echo esc_attr( $pm_phone['dial'] ); ?>">
							<span class="pm-header-phone-icon">
								<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.2.4 2.4.6 3.7.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.7.1.3 0 .7-.2 1l-2.3 2.1z"/></svg>
							</span>
							<span>
								<small><?php esc_html_e( 'Call the clinic', 'siteorigin-corp' ); ?></small>
								<?php echo esc_html( $pm_phone['display'] ); ?>
							</span>
						</a>

						<?php if ( $pm_book ) : ?>
							<a class="pm-header-book" href="<?php echo esc_url( $pm_book ); ?>" target="_blank" rel="noopener">
								<?php esc_html_e( 'Book Now', 'siteorigin-corp' ); ?>
							</a>
						<?php endif; ?>
					</div><!-- .pm-header-actions -->

				</div><!-- .site-header-inner -->
			</div><!-- .corp-container -->

			<?php if ( siteorigin_setting( 'navigation_menu_search' ) ) { ?>
				<div id="fullscreen-search">
					<div class="corp-container">
						<span><?php esc_html_e( 'Type and press enter to search', 'siteorigin-corp' ); ?></span>
						<form id="fullscreen-search-form" method="get" action="<?php echo esc_url( home_url() ); ?>">
							<input type="search" name="s" placeholder="" aria-label="<?php esc_attr_e( 'Search for', 'siteorigin-corp' ); ?>" value="<?php echo get_search_query(); ?>" />
							<button type="submit" aria-label="<?php esc_attr_e( 'Search', 'siteorigin-corp' ); ?>">
								<?php siteorigin_corp_display_icon( 'search' ); ?>
							</button>
						</form>
					</div>
					<button id="search-close-button" class="search-close-button" aria-label="<?php esc_attr_e( 'Close search', 'siteorigin-corp' ); ?>">
						<span class="close"><?php siteorigin_corp_display_icon( 'close' ); ?></span>
					</button>
				</div><!-- #header-search -->
			<?php } ?>

</div>

		</header><!-- #masthead -->
	<?php
	}
	do_action( 'siteorigin_corp_content_before' );
	?>
