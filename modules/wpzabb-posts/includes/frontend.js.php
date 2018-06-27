(function($) {

	$(function() {

		new WPZABBPostsModule({
			id: '<?php echo $id ?>',
			layout: '<?php echo $settings->layout; ?>',
			pagination: '<?php echo $settings->pagination; ?>',
			matchHeight: <?php echo $settings->match_height; ?>,
			isRTL: <?php echo is_rtl() ? 'true' : 'false'; ?>
		});
	});

	<?php if ( 'grid' == $settings->layout ) : ?>
	$(window).on('load', function() {
		$('.fl-node-<?php echo $id; ?> .wpzabb-post-<?php echo $settings->layout; ?>').masonry('reloadItems');
	});
	<?php endif; ?>

})(jQuery);
