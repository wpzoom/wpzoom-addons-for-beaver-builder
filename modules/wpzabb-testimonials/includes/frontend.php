<?php

$testimonials_class = 'wpzabb-testimonials-wrap ' . $settings->layout;

if ( '' == $settings->heading && 'compact' == $settings->layout ) {
	$testimonials_class .= ' wpzabb-testimonials-no-heading';
}

?>
<div class="<?php echo $testimonials_class; ?>">

	<?php if ( 'wide' != $settings->layout ) : ?>
		<h3 class="wpzabb-testimonials-heading"><?php echo $settings->heading; ?></h3>
	<?php endif; ?>

	<div class="wpzabb-testimonials">
		<?php

		for ( $i = 0; $i < count( $settings->testimonials ); $i++ ) :

			if ( ! is_object( $settings->testimonials[ $i ] ) ) {
				continue;
			}

			$testimonials = $settings->testimonials[ $i ];

		?>
		<div class="fl-testimonial">
			<?php echo $testimonials->testimonial; ?>
		</div>
		<?php endfor; ?>
	</div>

	<div class="fl-slider-next"></div>
	<div class="fl-slider-prev"></div>

</div>
