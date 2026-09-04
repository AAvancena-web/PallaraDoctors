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
assets/js/homepage.js            Form validation, date floor, reveal-on-scroll
assets/js/call-affordances.js    Fallback injection of the header phone button
patches/header.php.md            The one optional edit to header.php
```

## Install

1. Copy the files above into the child theme, keeping the folder structure.
2. Add one line to the child theme's `functions.php`:

   ```php
   require_once get_stylesheet_directory() . '/inc/homepage/bootstrap.php';
   ```

3. Load any admin page. The seeder runs once, creates a **draft** page called
   **Home (Redesign)** with the template assigned, and fills in every field.
   An admin notice links straight to it.
4. Preview the draft, then when you are happy: either set it as the front page
   (Settings, Reading) or switch the existing Home page to the
   "Homepage - Pallara Redesign" template.
5. Optional: apply `patches/header.php.md`.

The seeder never edits an existing published page, so nothing on the live site
changes until step 4.

## Re-running the seeder

It is guarded by the `pallara_hp_seeded` option, so it runs once. To re-run and
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
- **Booking URL.** Every "Book Now" defaults to the AutoMed booking link;
  change it in one place with the **Booking system URL** field or the
  `pallara_hp_booking_url` filter.
