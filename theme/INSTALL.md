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

Upload these 10 files, keeping the folder structure exactly. Create the
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
│   │   ├── homepage.css              NEW
│   │   └── call-affordances.css      NEW
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

## Step 3 - Let the seeder run

Load any admin page (the Dashboard is fine). Two things happen automatically:

1. A new page is created: **Home (Redesign)**, saved as a **draft**, with the
   "Homepage - Pallara Redesign" template already assigned.
2. Every ACF field on it is filled with the redesign content.

You will see a green notice: *"Pallara homepage: Seeded 40 homepage fields
into Home (Redesign)"* with an **Edit the page** link.

Nothing on the live site has changed at this point. The existing Home page is
untouched.

*If you see a warning instead of a green notice, see Troubleshooting below.*

---

## Step 4 - Preview and edit

Open **Pages, Home (Redesign)**, then click **Preview**.

Scroll down the edit screen to the **Homepage (Pallara Redesign)** panel. The
content is split into tabs: Hero, Hero form, Quick info cards, Services,
Content sections, Stats band, Team band, CTA band, Contact, and Phone and
floating button.

Anything with an **Add** button is a repeater: add, delete and drag rows to
reorder. Leave a field empty and it falls back to the packaged default, so you
cannot break the layout by clearing something.

To swap the images, use the image fields (Hero background image, the Main and
Inset image on each content section, and Team photo).

---

## Step 5 - Go live

Two ways, pick one:

**A. Replace the existing homepage (recommended)**
1. Edit **Home (Redesign)**, set the Permalink to something temporary, publish
   it, and confirm it looks right on the front end.
2. Edit the existing **Home** page and change **Page Attributes, Template** to
   **Homepage - Pallara Redesign**.
3. Copy the field content across, or just re-run the seeder against it:
   `wp pallara seed-homepage --force`
4. Delete the draft.

**B. Point the front page at the new page**
1. Publish **Home (Redesign)**.
2. **Settings, Reading, Your homepage displays, A static page**, and pick it.
3. Update the permalink to `/` behaviour by leaving the old Home page as a
   draft or deleting it.

Option A keeps the existing page ID, its permalink, and anything pointing at
it, so it is the safer of the two.

---

## Step 6 - Optional: the header.php edit

`header.php` prints the shared banner (featured image plus the CF7 banner
form) on every page. The new template supplies its own hero, so the stylesheet
already hides it and **the page looks correct without this step**. Making the
edit stops the browser downloading a banner image nobody sees.

Full instructions: `theme/patches/header.php.md`.

---

## Step 7 - Check the mobile bits

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

**The layout is squeezed into the middle of the screen.** `.pm-home` breaks
out of `.corp-container`. If a plugin adds another wrapper, tell me and I will
adjust the escape.

**I want to start the content again.** `wp pallara seed-homepage --force`
overwrites every field with the packaged defaults.

---

## Rollback

Remove the `require_once` line from `functions.php`. The template and all its
assets go dormant immediately; if a page was using the template it falls back
to the default page layout. Delete the uploaded files at your leisure. The
seeded content stays in the database as ordinary post meta and does no harm.
