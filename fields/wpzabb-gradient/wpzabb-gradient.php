<?php 
/*
	Declaration of array
	
	Ex.	'YOUR_VARIABLE_NAME'     => array(
                        'type'          => 'wpzabb-gradient',
                        'label'         => __( 'Gradient', 'wpzabb' ),
                        'default'       => array			//Required NULL or Default value
                            'color_one'		=> '',
                            'color_two'     => '',
                            'angle'      	=> '0',
                        )
                    )

Note : Default value is required here. Either pass it NULL or enter your own value.
	
    How to access variables

        .fl-node-<?php echo $id; ?> .YOUR-CLASS{
	    	<?php WPZABB_Helper::wpzabb_gradient_css( $settings->YOUR_VARIABLE_NAME ); ?>
	   }
*/

if(!class_exists('WPZABB_Gradient'))
{
	class WPZABB_Gradient
	{
		function __construct()
		{	
			add_action( 'fl_builder_control_wpzabb-gradient', array($this, 'wpzabb_gradient'), 1, 4 );
			add_action( 'fl_builder_custom_fields', array( $this, 'ui_fields' ), 10, 1 );
		}

		function ui_fields( $fields ) {
			$fields['wpzabb-gradient'] = BB_WPZOOM_ADDON_DIR . 'fields/wpzabb-gradient/ui-field-wpzabb-gradient.php';

			return $fields;
	    }
		
		function wpzabb_gradient($name, $value, $field, $settings) {

			$name_new = 'wpzabb_'.$name;
			$value = ( array ) $value;
			$preview = json_encode( array( 'type' => 'refresh' ) );

			$default = ( isset( $field['default'] ) && $field['default'] != '' ) ? $field['default'] : '';
			$direction = array(
				'left_right' => 'Left to Right',
				'right_left' => 'Right to Left',
				'top_bottom' => 'Top to Bottom',
				'bottom_top' => 'Bottom to Top',
				'custom'     => 'Custom'
			);

			$colorpick_class = 'bb-colorpick';
			$html = '<div class="wpzabb-gradient-wrapper '.$colorpick_class.'">';

			/* Color One */
			$color_one_class = ( empty( $value['color_one'] ) ) ? ' fl-color-picker-empty' : '';
		    $html .= '<div class="wpzabb-gradient-item bb-color wpzabb-gradient-color-one fl-field" data-type="color" data-preview=\'' . $preview . '\'>';
			$html .= '<label for="wpzabb-gradient-color-one" class="wpzabb-gradient-label">Color 1</label>';
			$html .= '<div class="fl-color-picker">';
			$html .= '<div class="fl-color-picker-color'. $color_one_class .'"></div>';
			$html .= '<div class="fl-color-picker-clear"><div class="fl-color-picker-icon-remove"></div></div>';
			$html .= '<input name="'. $name . '[][color_one]'.'" type="hidden" value="'. $value['color_one'] .'" class="fl-color-picker-value" />';
			$html .= '</div>';
			$html .= '</div>';

			/* Color Two */
			$color_two_class = ( empty( $value['color_two'] ) ) ? ' fl-color-picker-empty' : '';
		    $html .= '<div class="wpzabb-gradient-item bb-color wpzabb-gradient-color-two fl-field" data-type="color" data-preview=\'' . $preview . '\'>';
			$html .= '<label for="wpzabb-gradient-color-two" class="wpzabb-gradient-label">Color 2</label>';
			$html .= '<div class="fl-color-picker">';
			$html .= '<div class="fl-color-picker-color'. $color_two_class .'"></div>';
			$html .= '<div class="fl-color-picker-clear"><div class="fl-color-picker-icon-remove"></div></div>';
			$html .= '<input name="'. $name . '[][color_two]'.'" type="hidden" value="'. $value['color_two'] .'" class="fl-color-picker-value" />';
			$html .= '</div>';
			$html .= '</div>';

			/* Direction */
			$html .= '<div class="wpzabb-gradient-item wpzabb-gradient-direction fl-field" data-type="select" data-preview=\'' . $preview . '\'>';
			$html .= '<label for="wpzabb-gradient-direction" class="wpzabb-gradient-label">Direction</label>';
			$html .= '<select name="'. $name . '[][direction]' .'" class="wpzabb-gradient-direction-select">';
				foreach ($direction as $direction_key => $direction_value) {
					$selected = '';
					if ( $value['direction'] == $direction_key  ) {
						$selected = 'selected="selected"';
					}
					$html .= '<option value="'.$direction_key.'" '. $selected . '>'.$direction_value.'</option>';
				}
			$html .= '</select>';
			$html .= '</div>';

			/* Angle */
			$angle = ( isset( $value['angle'] ) ) ? $value['angle'] : '';
			$html .= '<div class="wpzabb-gradient-item wpzabb-gradient-angle fl-field" data-type="text" data-preview=\'' . $preview . '\' >';
		    $html .= '<label for="wpzabb-gradient-angle" class="wpzabb-gradient-label">Angle</label>';
			$html .= '<input type="text" class="wpzabb-gradient-angle-input" name="'. $name . '[][angle]' .'" maxlength="3" size="6" value="'. $angle .'" />';
			$html .= '<span class="fl-field-description">deg</span>';
			$html .= '</div>';
			$html .= '</div>';
		
			echo $html;
		}
	}
	new WPZABB_Gradient();
}