<?php
	$team_members_class = 'wpzabb-team-members-wrap ' . $settings->layout . ' content-align-'. $settings->content_align;
	$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' );
	$name_tag = in_array( $settings->tag, $allowed_tags, true ) ? $settings->tag : 'h3';
?>
<div class="<?php echo esc_attr( $team_members_class ); ?>">

	<div class="wpzabb-members">
		<?php
		for ( $i = 0; $i < count( $settings->members ); $i++ ) :

			if ( ! is_object( $settings->members[ $i ] ) ) {
				continue;
			} else {
				$member = $settings->members[ $i ];
			}

			$classes  = $module->get_classes( $member );
			$src      = $module->get_src( $member );
			$alt      = $module->get_alt( $member );
		?>
		<figure class="wpzabb-member">
			<?php if( !empty( $member->link ) ) : ?>
				<a href="<?php echo esc_url( $member->link ); ?>" title="<?php echo esc_attr( $member->name ); ?>" target="<?php echo $member->link_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $member->link_target, 0, 1 ); ?>>
			<?php endif; ?>
			<div class="wpzabb-member-avatar" itemscope itemtype="http://schema.org/ImageObject">
				<img class="<?php echo $classes; ?>" src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $alt ); ?>" itemprop="image"/>
			</div>
			<?php if( !empty( $member->link ) ) : ?>
				</a>
			<?php endif; ?>
			<figcaption class="wpzabb-member-caption">
				<<?php echo $name_tag; ?> class="wpzabb-member-name">
				<?php if( !empty( $member->link ) ) : ?>
					<a href="<?php echo esc_url( $member->link ); ?>" title="<?php echo esc_attr( $member->name ); ?>" target="<?php echo esc_attr( $member->link_target ); ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $member->link_target, 0, 1 ); ?>>
				<?php endif; ?>
				<span class="wpzabb-member-name-text"><?php echo esc_html( $member->name ); ?></span>
				<?php if( !empty( $member->link ) ) : ?>
					</a>
				<?php endif; ?>
				</<?php echo $name_tag; ?>>
				<span class="wpzabb-member-position"><?php echo esc_html( $member->position ); ?></span>
				<?php if ( !empty( $member->member_info ) ): ?>
					<div class="wpzabb-member-info"><?php echo wp_kses_post( $member->member_info ); ?></div>
				<?php endif ?>
			</figcaption>
		</figure>
		<?php endfor; ?>
	</div>

</div>
