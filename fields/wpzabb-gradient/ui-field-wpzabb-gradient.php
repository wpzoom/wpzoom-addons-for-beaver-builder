<#
var field   = data.field,
    name    = data.name,
    name_new = 'wpzabb_' + name,
    value = data.value,
    settings = data.settings,
    preview = data.preview,
    default_val = ( 'undefined' != typeof field.default && '' != field.default )
    selected = '',
    sel = '',
    color_one_class = ( value.color_one == '' ) ? 'fl-color-picker-empty' : '',
    color_two_class = ( value.color_two == '' ) ? 'fl-color-picker-empty' : '',
    direction = {
		'left_right' : '<?php _e( 'Left to Right', 'wpzabb' ); ?>',
		'right_left' : '<?php _e( 'Right to Left', 'wpzabb' ); ?>',
		'top_bottom' : '<?php _e( 'Top to Bottom', 'wpzabb' ); ?>',
		'bottom_top' : '<?php _e( 'Bottom to Top', 'wpzabb' ); ?>',
		'custom'     : '<?php _e( 'Custom', 'wpzabb' ); ?>'
	},
    yr = parseInt( '<?php echo date( 'Y' ); ?>' );
#>

<div class="wpzabb-gradient-wrapper bb-colorpick">
	<div class="wpzabb-gradient-item bb-color wpzabb-gradient-color-one fl-field" data-type="color" data-preview="{{preview}}">
		<label for="wpzabb-gradient-color-one" class="wpzabb-gradient-label"><?php _e( 'Color 1', 'wpzabb'); ?></label>
		<div class="fl-color-picker">
			<div class="fl-color-picker-color {{color_one_class}}"></div>
			<div class="fl-color-picker-clear"><div class="fl-color-picker-icon-remove"></div></div>
			<input name="{{name}}[][color_one]" type="hidden" value="{{value.color_one}}" class="fl-color-picker-value" />
		</div>
	</div>
	<div class="wpzabb-gradient-item bb-color wpzabb-gradient-color-two fl-field" data-type="color" data-preview="{{preview}}">
		<label for="wpzabb-gradient-color-two" class="wpzabb-gradient-label"><?php _e( 'Color 2', 'wpzabb'); ?></label>
		<div class="fl-color-picker">
			<div class="fl-color-picker-color {{color_two_class}}"></div>
			<div class="fl-color-picker-clear"><div class="fl-color-picker-icon-remove"></div></div>
			<input name="{{name}}[][color_two]" type="hidden" value="{{value.color_two}}" class="fl-color-picker-value" />
		</div>
	</div>
	<div class="wpzabb-gradient-item wpzabb-gradient-direction fl-field" data-type="select" data-preview="{{preview}}">
		<label for="wpzabb-gradient-direction" class="wpzabb-gradient-label"><?php _e( 'Direction', 'wpzabb'); ?></label>
		<select name="{{name}}[][direction]" class="wpzabb-gradient-direction-select">
		<#
		for ( var direction_key in direction ) {
			selected = '';
			if ( value.direction == direction_key ) {
				selected = 'selected="selected"';
			}
			#>
			<option value="{{direction_key}}" {{selected}}>{{direction[direction_key]}}</option>
			<#
		}
		#>
		</select>
	</div>
	<div class="wpzabb-gradient-item wpzabb-gradient-angle fl-field" data-type="text" data-preview="{{preview}}">
		<label for="wpzabb-gradient-angle" class="wpzabb-gradient-label"><?php _e( 'Angle', 'wpzabb' ); ?></label>
		<input type="text" class="wpzabb-gradient-angle-input" name="{{name}}[][angle]" maxlength="3" size="6" value="{{value.angle}}" />
		<span class="fl-field-description"><?php _e( 'deg', 'wpzabb' ); ?></span>
	</div>
</div>