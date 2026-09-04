# One small edit to `header.php`

`header.php` prints the shared featured-image banner (and, on the front page,
the Contact Form 7 banner form) for **every** page. The new homepage template
brings its own hero, form and map, so that block has to be skipped on this
template.

`assets/css/homepage.css` already hides it, so the page looks correct without
this edit. Making the edit is still worth it: it stops the browser from
downloading the banner image nobody sees, and stops a second CF7 form from
being initialised.

## The change

In `header.php`, find this block (roughly two thirds down, just after
`do_action( 'siteorigin_corp_content_before' );`):

```php
<div class="single-featured-image-header single-post-page<?php echo esc_attr($classes); ?>">
```

Wrap the whole banner block, from that `<div>` down to and including the
`<?php endif; ?>` that closes the `banner-fixed-form` conditional, in a
template check:

```php
<?php if ( ! is_page_template( 'template-homepage.php' ) ) : ?>

	<div class="single-featured-image-header single-post-page<?php echo esc_attr($classes); ?>">

		... existing banner markup, unchanged ...

	<?php if ( in_array( 'home', get_body_class(), true ) ) : ?>
		<div class="banner-fixed-form contact-form">
			...
		</div>
	<?php endif; ?>

	</div>

<?php endif; ?>
```

That is the only change; nothing inside the block moves.

## Note on `$outer_class`

While you are in there: the block reads `$classes = $outer_class;` but
`$outer_class` is never defined in `header.php`, which emits an
"Undefined variable" notice on every page load. It is unrelated to this
redesign, but a one-line fix if you want it:

```php
$classes = isset( $outer_class ) ? $outer_class : '';
```
