# Install guide: homepage redesign

Everything goes into the **child theme**:
`wp-content/themes/siteorigin-corp-child/`

Nothing outside that folder changes, apart from one line added to
`functions.php`. Roughly 15 minutes.

---

## Before you start

**1. Check you have ACF Pro.** The field group uses **repeater** fields, and
repeaters are an ACF Pro feature. In the admin go to **Plugins** and look for
"Advanced Custom Fields PRO". If it says just "Advanced Custom Fields", the
page will still render (it falls back to the packaged copy) but the repeaters
will not appear in the admin and the seeder will skip.

**2. Take a backup** of the child theme folder and the database, or work on
staging first. Nothing here overwrites an existing file, but `functions.php`
is being edited.

**3. Have SFTP/FTP or cPanel File Manager access**, or use
**Appearance, Theme File Editor** (fine for the `functions.php` line, no good
for uploading new files).

---

## Step 1 - Upload the files

Upload these 11 files, keeping the folder structure exactly. Create the
`inc/homepage/`, `assets/css/` and `assets/js/` folders if they do not exist.

| Upload this file | To this path on the server |
| --- | --- |
| `theme/template-homepage.php` | `siteorigin-corp-child/template-homepage.php` |
| `theme/inc/homepage/bootstrap.php` | `siteorigin-corp-child/inc/homepage/bootstrap.php` |
| `theme/inc/homepage/defaults.php` | `siteorigin-corp-child/inc/homepage/defaults.php` |
| `theme/inc/homepage/helpers.php` | `siteorigin-corp-child/inc/homepage/helpers.php` |
| `theme/inc/homepage/acf-fields.php` | `siteorigin-corp-child/inc/homepage/acf-fields.php` |
| `theme/inc/homepage/seeder.php` | `siteorigin-corp-child/inc/homepage/seeder.php` |
| `theme/assets/css/homepage.css` | `siteorigin-corp-child/assets/css/homepage.css` |
| `theme/assets/css/call-affordances.css` | `siteorigin-corp-child/assets/css/call-affordances.css` |
| `theme/assets/css/global-header-footer.css` | `siteorigin-corp-child/assets/css/global-header-footer.css` |
| `theme/assets/js/homepage.js` | `siteorigin-corp-child/assets/js/homepage.js` |
| `theme/assets/js/call-affordances.js` | `siteorigin-corp-child/assets/js/call-affordances.js` |

The finished structure:

```
siteorigin-corp-child/
├── functions.php              (existing - edited in step 2)
├── header.php                 (existing - optional edit in step 6)
├── style.css                  (existing - untouched)
├── template-homepage.php      NEW
├── assets/
│   ├── css/
│   │   ├── homepage.css                 NEW
│   │   ├── call-affordances.css         NEW
│   │   └── global-header-footer.css     NEW
│   └── js/
│       ├── homepage.js               NEW
│       └── call-affordances.js       NEW
└── inc/
    └── homepage/
        ├── bootstrap.php             NEW
        ├── defaults.php              NEW
        ├── helpers.php               NEW
        ├── acf-fields.php            NEW
        └── seeder.php                NEW
```

**Do not upload** `README.md`, `INSTALL.md`, `patches/` or the repo's
`index.html`. They are documentation and the original standalone preview.

---

## Step 2 - Add one line to functions.php

Open `siteorigin-corp-child/functions.php` and add this at the **end** of the
file (if the file ends with `?>`, put it before that):

```php
require_once get_stylesheet_directory() . '/inc/homepage/bootstrap.php';
```

Save. If the site white-screens after this, the paths in step 1 are wrong -
remove the line, fix the paths, add it back.

---

## Step 3 - Assign the template to the Home page

Open **Pages, Home** (the existing homepage, do not create a new one). In the
right-hand column under **Page Attributes**, change **Template** from Default
to **Homepage - Pallara Redesign**, then click **Update**.

Two things to know before you click:

- **This is live immediately.** The homepage switches to the new layout as
  soon as you update. Do it at a quiet time, or run through this on staging
  first.
- **Nothing is destroyed.** The existing WPBakery content stays in the page
  exactly as it is, the template simply does not render it. Setting the
  template back to Default restores the old homepage exactly as it was.

## Step 4 - The content seeds itself

The moment a page is using the template, the seeder fills in every redesign
field on it and shows a green notice:

> **Pallara homepage:** Seeded 40 homepage fields into "Home" (page 6).

It only fills fields that are **empty**, so it can never overwrite something
you have written. It runs once; after that the content is yours to edit.

If the fields look empty, reload the page editor once. To force a re-seed at
any time, see Troubleshooting.

## Step 5 - Edit the content

Still on the Home page edit screen, scroll to the **Homepage (Pallara
Redesign)** panel. The content is split into tabs: Hero, Hero form, Quick info
cards, Services, Content sections, Stats band, Team band, CTA band, Contact,
and Phone and floating button.

Anything with an **Add** button is a repeater: add, delete and drag rows to
reorder. Leave a field empty and it falls back to the packaged default, so you
cannot break the layout by clearing something.

To swap the images, use the image fields (Hero background image, the Main and
Inset image on each content section, and Team photo). Until then the page uses
the existing images from the media library.

## Step 6 - Optional: the header.php edit

`header.php` prints the shared banner (featured image plus the CF7 banner
form) on every page. The new template supplies its own hero, so the stylesheet
already hides it and **the page looks correct without this step**.

It is worth doing anyway on the Home page specifically: that page has a 1920px
featured image set, and without the edit the browser still downloads it on
every visit for a block nobody sees. It also stops a second Contact Form 7
form being initialised behind the hidden block.

Full instructions: `theme/patches/header.php.md`.

---

## Step 7 - The header and footer restyle

`global-header-footer.css` loads on **every page** and restyles the existing
header and footer markup to match the redesign. Nothing structural moves and
no markup changes, so the theme's sticky-nav and mobile-drawer scripts keep
working exactly as they do now.

What changes:

- **Header top row.** The pixel icons become crisp SVGs in brand blue, the
  phone becomes a dark circular icon plus the number, and BOOK NOW becomes a
  solid rounded blue button (the corner brackets go).
- **Navigation.** The solid blue bar becomes white with a hairline, ink menu
  labels, a soft blue pill on hover and for the current page, and a rounded
  card with a shadow for the dropdowns. The sticky state gains a shadow.
- **Mobile drawer.** Wider panel, larger tap targets, tidier submenu toggles.
- **Footer.** Blue top rule, uppercase widget titles, SVG contact icons, and
  lighter, more readable link colours with a white hover.

Give the site a click through after uploading, especially the dropdown menus
and the mobile drawer. If you want it off, either do not upload the file or
add this to `functions.php`:

```php
add_filter( 'pallara_hp_load_header_styles', '__return_false' );
```

## Step 8 - Check the mobile bits

On a phone (or a narrow browser window under 1024px):

- A **dark circular phone button** sits immediately left of the hamburger.
- A **Call Now** pill floats at the bottom centre of the screen.

Both read the number from the **Phone and floating button** tab. If the
circular button is missing, check **Appearance, Widgets** still has something
in the **call-icon-mobile** widget area - the stylesheet restyles that widget.
If it is empty, the JS drops in a replacement.

---

## Troubleshooting

**"Advanced Custom Fields is not active, so the homepage seeder was skipped."**
Activate ACF Pro, then re-run: visit
`/wp-admin/admin-post.php?action=pallara_hp_seed` as an administrator, or run
`wp pallara seed-homepage --force`.

**The page renders but the styling is missing.** The CSS did not load. Check
`assets/css/homepage.css` is at exactly that path and is readable (644). If
Autoptimize is caching, clear its cache.

**Two forms or two maps appear above the footer.** The sitewide
`form-section` / `google-maps` widget areas are showing through. They are
hidden by the stylesheet on this template, so this means the CSS did not load
- same fix as above.

**The header or footer looks wrong after uploading.** Turn the restyle off
with the filter above, confirm the old look returns, and send me a screenshot
of what was off. The stylesheet is written to out-specify the Customizer's
Additional CSS, but your live site may carry rules I have not seen.

**The layout is squeezed into the middle of the screen.** `.pm-home` breaks
out of `.corp-container`. If a plugin adds another wrapper, tell me and I will
adjust the escape.

**The fields are empty / I want to start the content again.**
`wp pallara seed-homepage --force` overwrites every field with the packaged
defaults. Without WP-CLI, visit
`/wp-admin/admin-post.php?action=pallara_hp_seed` as an administrator.

**Where did my old homepage content go?** Nowhere. It is still in the page,
the template just does not output it. Set Page Attributes, Template back to
Default to bring it back.

---

## Rollback

**Fastest:** edit the Home page and set **Page Attributes, Template** back to
**Default**. The old WPBakery homepage comes straight back, untouched.

**Full removal:** also delete the `require_once` line from `functions.php`.
The template and all its assets go dormant immediately, and the uploaded files
can be deleted at your leisure. The seeded content stays in the database as
ordinary post meta, does no harm, and will still be there if you re-enable
the template later.
