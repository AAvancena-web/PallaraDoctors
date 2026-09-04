# Pallara Medical - homepage redesign (WordPress)

A page template, an ACF field group and a one-time seeder that turn the
standalone redesign (`/index.html` in the repo root) into an editable
WordPress homepage. The global header and footer are untouched: the template
only owns what sits between `get_header()` and `get_footer()`.

## Files

Everything in this folder maps onto the **child theme root**
(`wp-content/themes/siteorigin-corp-child/`):

```
template-homepage.php            Page template: "Homepage - Pallara Redesign"
inc/homepage/bootstrap.php       Loads the module, enqueues assets, prints the floating call button
inc/homepage/defaults.php        All default copy (used by the template AND the seeder)
inc/homepage/helpers.php         Field getters with fallbacks, icon sprite, form partial
inc/homepage/acf-fields.php      ACF field group, registered in code
inc/homepage/seeder.php          One-time seeder + WP-CLI command
assets/css/homepage.css          Template styles (scoped to .pm-home)
assets/css/call-affordances.css  Header phone circle + floating call button (sitewide)
assets/css/global-header-footer.css  Restyles the theme header and footer (sitewide)
assets/js/homepage.js            Form validation, date floor, reveal-on-scroll
assets/js/call-affordances.js    Fallback injection of the header phone button
patches/header-redesign.php      Optional drop-in header.php: the exact redesign layout
patches/header.php.md            The one optional edit to your existing header.php
```

## Install

1. Copy the files above into the child theme, keeping the folder structure.
2. Add one line to the child theme's `functions.php`:

   ```php
   require_once get_stylesheet_directory() . '/inc/homepage/bootstrap.php';
   ```

3. Edit the existing Home page and set **Page Attributes, Template** to
   **Homepage - Pallara Redesign**, then update. This switches the live
   homepage over immediately; the page's existing WPBakery content is left
   untouched, so setting the template back to Default restores it exactly.
4. The seeder fills in every redesign field on that page automatically, and
   says so in an admin notice. It only writes to fields that are empty.
5. Optional: apply `patches/header.php.md`.

The seeder never creates a page and never overwrites content that is already
there. Step-by-step version: `INSTALL.md`.

## Re-running the seeder

It waits until a page is using the template, then runs once (guarded by the
`pallara_hp_seeded` option) and fills only empty fields. To re-run and
overwrite the current field values with the packaged defaults:

```bash
wp pallara seed-homepage --force
```

or, as an administrator, visit
`/wp-admin/admin-post.php?action=pallara_hp_seed` (the nonced URL is available
from `pallara_hp_seed_url()`).

## Editing the content

All content lives on the page itself, under **Homepage (Pallara Redesign)**,
split into tabs: Hero, Hero form, Quick info cards, Services, Content sections,
Stats band, Team band, CTA band, Contact, and Phone/floating button.

Repeaters (add, remove and drag to reorder):

| Field | What it repeats | Nested repeater |
| --- | --- | --- |
| `hp_hero_badges` | Ticked pills in the hero | |
| `hp_form_doctors` | Doctors in both form dropdowns | |
| `hp_form_time_slots` | Appointment time options | |
| `hp_quick_cards` | The three cards over the hero | `hp_qc_rows` (label/value rows, e.g. opening hours) |
| `hp_services` | Service cards | |
| `hp_sections` | Alternating image/text sections | `hp_sec_ticks` (ticked list items) |
| `hp_stats` | Stats band figures | |
| `hp_contact_items` | Contact detail rows | |

Every field falls back to the packaged default when it is left empty, so the
page can never render half-built.

## Images

The defaults point at the existing production upload URLs. When the seeder
runs, each one is matched against the media library with
`attachment_url_to_postid()`; where it matches, the field is set to that
attachment (so responsive `srcset` works). Where it does not, the field stays
empty and the template falls back to the URL.

To swap in new artwork, just set the image fields in the admin. To repoint all
the placeholders at once, filter `pallara_hp_image_base`.

## Forms

Both forms render a built-in layout with client-side validation only: they do
not send mail. To make one live, paste a Contact Form 7 shortcode into
**Contact Form 7 shortcode** on the Hero form or Contact tab, e.g.

```
[contact-form-7 id="2f783e2" title="Homepage Banner"]
```

CF7 then owns that form completely and the built-in layout is not rendered.

## Notes on integration

- **Widths.** `.pm-home` breaks out of `.corp-container` (which is capped at
  79.5%) so the bands run full width, and the inner `.pm-container` caps at
  **1440px**, rising to **1880px** from 1800px viewports.
- **Units.** The child theme sets a fluid root font size
  (`html{font-size:calc(10px + ...)}`), which makes `rem` unreliable, so this
  stylesheet is in `px`.
- **Scoping.** Every selector is under `.pm-home` and every class is `pm-`
  prefixed, so theme rules such as `.contact-form{display:grid}` cannot reach
  into the template and nothing here leaks out.
- **Call affordances.** `call-affordances.css` restyles the existing
  `call-icon-mobile` widget into a dark circular button to the left of the
  hamburger, and the floating **Call Now** button is printed on `wp_footer`
  sitewide. Turn the floating button off per site with the
  `hp_floating_call_show` toggle, or per request with the
  `pallara_hp_show_floating_call` filter.
- **Global header and footer.** `global-header-footer.css` restyles the
  existing header.php / footer.php markup sitewide: white nav with pill
  hovers, SVG contact icons, a dark circular phone button, a solid BOOK NOW
  button, and a lighter footer. It changes appearance only, so the theme's
  sticky-nav and drawer scripts are unaffected. Its selectors deliberately
  out-specify the Customizer's Additional CSS, which loads after any
  enqueued stylesheet. Disable with
  `add_filter( 'pallara_hp_load_header_styles', '__return_false' );`.
- **Booking URL.** Every "Book Now" defaults to the AutoMed booking link;
  change it in one place with the **Booking system URL** field or the
  `pallara_hp_booking_url` filter.
