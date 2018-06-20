<?php
$team_members_class = 'wpzabb-team-members-wrap ' . $settings->layout;
?>
<div class="<?php echo $team_members_class; ?>">

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
			<div class="wpzabb-member-avatar" itemscope itemtype="http://schema.org/ImageObject">
				<img class="<?php echo $classes; ?>" src="<?php echo $src; ?>" alt="<?php echo $alt; ?>" itemprop="image"/>
			</div>
			<figcaption class="wpzabb-member-caption">
				<?php if( !empty( $member->link ) ) : ?>
					<a href="<?php echo $member->link; ?>" title="<?php echo $member->name; ?>" target="<?php echo $member->link_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $member->link_target, 0, 1 ); ?>>
				<?php endif; ?>
					<h3 class="wpzabb-member-name"><?php echo $member->name ?></h3>
				<?php if( !empty( $member->link ) ) : ?>
					</a>
				<?php endif; ?>
				<span class="wpzabb-member-position"><?php echo $member->position ?></span>
				<?php if ( !empty( $member->member_info ) ): ?>
					<p class="wpzabb-member-info"><?php echo $member->member_info ?></p>
				<?php endif ?>
			</figcaption>
		</figure>
		<?php endfor; ?>
	</div>

</div>
