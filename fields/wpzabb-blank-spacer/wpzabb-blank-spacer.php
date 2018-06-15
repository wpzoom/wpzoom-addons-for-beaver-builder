<?php
/*
 *	Switch Param
 */

if(!class_exists('WPZABB_Blank_Spacer'))
{
	class WPZABB_Blank_Spacer
	{
		function __construct()
		{	
			add_action( 'fl_builder_control_wpzabb-blank-spacer', array($this, 'wpzabb_blank_spacer'), 1, 4 );
			//add_action( 'wp_enqueue_scripts', array( $this, 'blank_spacer_scripts' ) );
		}

		/*function blank_spacer_scripts() {
      		if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {
        		wp_enqueue_style( 'blank_spacer-styles', plugins_url( 'css/wpzabb-blank-spacer.css', __FILE__ ) );
      		}		
		}*/
		
		function wpzabb_blank_spacer($name, $value, $field) {

      
      		$spacer_height = isset( $field['height'] ) ? $field['height'] : '50px' ;
      
			$output = "<div class='wpzabb-blank-spacer'>";
      
      		/* Dynamic Style */
      		$output .= '<style>';
      		$output .= '.wpzabb-blank-spacer .wpzabb-spacer-'.$name.'{';
      		$output .= 'height: '.$spacer_height.';';
      		$output .= '}';
      		$output .= '</style>';
    		
    		$output .= '<div class="wpzabb-spacer-' . $name . '"></div>';
    		$output .= '</div>';
		
      		echo $output;
    	}
	}

	new WPZABB_Blank_Spacer();
}
