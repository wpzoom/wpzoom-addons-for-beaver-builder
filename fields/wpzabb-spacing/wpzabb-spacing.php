<?php 
/*
	Declaration op array  For - Font Size, Line Height or Responsive Width, Height
	
	Ex.	'font_spacing'     => array(
                        'type'          => 'wpzabb-spacing',
                        'label'         => __( 'Padding', 'wpzabb' ),
                        'mode'			=> 'padding' // padding/margin : Default is padding
                        'default'       => padding: 10px 20px; // Optional
                        'preview'         => array(			//optional
                            'type'             => 'css',
                            'selector'         => '.ultit-title',
                            'property'          => 'padding',
                            'unit'              => 'px'
                        )
                    )

Note : Default value in any format can accepted. Write padding or margin as par "mode"
	ex: padding: 10px;
		padding: 10px 20px;
		padding-top: 10px; padding-bottom: 20px;

    How to access variables

        .fl-builder-content .fl-node-<?php echo $id; ?> .ultit-title{
	    	padding: <?php echo $settings->font_spacing ?>;
	   }
*/

if(!class_exists('WPZABB_Spacing'))
{
	class WPZABB_Spacing
	{
		function __construct()
		{	
			add_action( 'fl_builder_control_wpzabb-spacing', array($this, 'wpzabb_spacing'), 1, 4 );
			//add_action( 'wp_enqueue_scripts', array( $this, 'wpzabb_spacing_assets' ) );
		}
		
		function wpzabb_spacing($name, $value, $field, $settings) {

			//var_dump( $value );
			$name_def = $name;
			$name = 'wpzabb_'.$name;

			$value = str_replace("px","", $value );
			$wpzabb_default = array_filter( preg_split("/\s*;\s*/", $value) );

			/*if( end( $wpzabb_default ) == '' ){
				$wpzabb_default = array_pop( $wpzabb_default );
			}*/

			//$wpzabb_default = preg_split('/\s+/', trim( $value ) );

			//var_dump( $wpzabb_default );

			$mode = isset( $field['mode'] ) ? $field['mode'] : 'padding';
			$data_mode = " data-mode='".$mode."'";
			$selector = '';
			$simplify = 'collapse';
			$medias = array();
			$medias_all = array(
				'all'		=> isset( $value['all'] ) ? $value['all'] : '',
			);
			$medias_options = array(
				'top'		=> isset( $value['top'] ) ? $value['top'] : '',
				'right'		=> isset( $value['right'] ) ? $value['right'] : '',
				'bottom'	=> isset( $value['bottom'] ) ? $value['bottom'] : '',
				'left'		=> isset( $value['left'] ) ? $value['left'] : '',
			);

			$placeholder = array(
				'all' 		=>	isset( $field['placeholder']['all'] ) ? $field['placeholder']['all'] : 'All',
				'top' 		=>	isset( $field['placeholder']['top'] ) ? $field['placeholder']['top'] : 'Top',
				'right' 	=>	isset( $field['placeholder']['right'] ) ? $field['placeholder']['right'] : 'Right',
				'bottom' 	=>	isset( $field['placeholder']['bottom'] ) ? $field['placeholder']['bottom'] : 'Bottom',
				'left' 		=>	isset( $field['placeholder']['left'] ) ? $field['placeholder']['left'] : 'Left',
			);

			//var_dump( $wpzabb_default );
			if ( is_array( $wpzabb_default ) ) {
				foreach ($wpzabb_default as $value) {
					$wpzabb_default = array_filter( preg_split("/\s*:\s*/", $value) );
					//var_dump( $wpzabb_default[0] );
					//var_dump( $wpzabb_default[1] );
					$ch = isset( $wpzabb_default[0] ) ? $wpzabb_default[0] : 'margin' ;

					$array_value = isset( $wpzabb_default[1] ) ? $wpzabb_default[1] : '';
					switch ( $ch ) {
						case 'margin-top':
						case 'padding-top':
							$medias_options['top'] 		= $array_value ; 
							break;
						
						case 'margin-right':
						case 'padding-right':
							$medias_options['right'] 	= $array_value ;
							break;
						
						case 'margin-bottom':
						case 'padding-bottom':
							$medias_options['bottom']	= $array_value ;
							break;
						
						case 'margin-left':
						case 'padding-left':
							$medias_options['left']		= $array_value ;
							break;
						
						case 'margin':
						case 'padding':
							//$medias_options['all']		= $array_value ;
							
							if ( isset( $wpzabb_default[1] ) && $wpzabb_default[1] != '' ) {
								$wpzabb_default[1] =  preg_split('/\s+/', trim( $wpzabb_default[1] ) );
								switch ( count( $wpzabb_default[1] )) {
									case 1:
										$medias_all['all'] = $wpzabb_default[1][0]; 
										break;
									case 2:
										$medias_options['top']		= $medias_options['bottom']	= $wpzabb_default[1][0];
										$medias_options['right'] 	= $medias_options['left']	= $wpzabb_default[1][1];
										break;
									case 3:
										$medias_options['top']		= $wpzabb_default[1][0];
										$medias_options['right'] 	= $medias_options['left']	= $wpzabb_default[1][1];
										$medias_options['bottom']	= $wpzabb_default[1][2];
										break;
									case 4:
										$medias_options['top']		= $wpzabb_default[1][0];
										$medias_options['right'] 	= $wpzabb_default[1][1];
										$medias_options['bottom']	= $wpzabb_default[1][2];
										$medias_options['left']		= $wpzabb_default[1][3];
										break;
								}
							}
							break;
					}
					
				}
			}
			


			/*if ( isset( $wpzabb_default[0] ) && $wpzabb_default[0] != '' ) {
				switch ( count( $wpzabb_default )) {
					case 1:
						$medias_all['all'] = $wpzabb_default[0]; 
						break;
					case 2:
						$medias_options['top']		= $medias_options['bottom']	= $wpzabb_default[0];
						$medias_options['right'] 	= $medias_options['left']	= $wpzabb_default[1];
						break;
					case 3:
						$medias_options['top']		= $wpzabb_default[0];
						$medias_options['right'] 	= $medias_options['left']	= $wpzabb_default[1];
						$medias_options['bottom']	= $wpzabb_default[2];
						break;
					case 4:
						$medias_options['top']		= $wpzabb_default[0];
						$medias_options['right'] 	= $wpzabb_default[1];
						$medias_options['bottom']	= $wpzabb_default[2];
						$medias_options['left']		= $wpzabb_default[3];
						break;
				}
			}*/
		
			$medias =  array_merge( $medias_all, $medias_options );

			if( $medias_options['top'] != '' || $medias_options['right'] != '' || $medias_options['bottom'] != '' || $medias_options['left'] != '' ){
				$simplify = 'expand';
			}
			
			$simplify = ( isset($value['simplify']) ) ? $value['simplify'] : $simplify ;
			
			$spacing = '';
			if( $simplify == 'collapse' ) {
				$spacing .= ( $medias_all['all'] != '' ) ? $mode.': '.$medias_all['all'].'px;' : ''; 
			}else{
				$spacing .= ( $medias_options['top'] ) ? $mode.'-top: '.$medias_options['top'].'px; ' : '';
				$spacing .= ( $medias_options['right'] ) ? $mode.'-right: '.$medias_options['right'].'px; ' : '';
				$spacing .= ( $medias_options['bottom'] ) ? $mode.'-bottom: '.$medias_options['bottom'].'px; ' : '';
				$spacing .= ( $medias_options['left'] ) ? $mode.'-left: '.$medias_options['left'].'px;' : '';
			} 

			$simplify_style = ( $simplify == 'collapse' ) ? 'style="display:inline-block;"' : 'style="display:none;"';
			$simplify_option_style = ( $simplify == 'collapse' ) ? 'style="display:none;"' : 'style="display:inline-block;"';

			$html  = '<div class="wpzabb-spacing-wrapper">';
				$html .= '  <div class="wpzabb-spacing-items" >';
				$html .= "<input type='hidden' class='hidden-spacing' name='".$name_def."' value='".$spacing."'>";

				$html .= "<div class='spacing-toggle-wrap'><div class='simplify' wpzabb-toggle='".$simplify."'>
										<input type='hidden' class='simplify_toggle' name='".$name."[][simplify]' value='".$simplify."'>
										<i class='simplify-icon dashicons dashicons-no-alt wpzabb-help-tooltip'></i>
										<div class='wpzabb-tooltip simplify-options'>".__("Expand/Collapse Options","wpzabb")."</div>
									  </div></div>";
				
				foreach($medias as $key => $default_value ) {
					//$html .= $key;
					switch ($key) {
						case 'all': 
							$selector = $this->wpzabb_preview( $field, $key, $mode );
							$input_class = 'all';

							//$selector = ' data-type="text" data-preview=\'' . $preview . '\''; 
							$class = 'fl-field require';
							$data_id  = strtolower((preg_replace('/\s+/', '_', $key)));
							$dashicon = "<i class='dashicons dashicons-editor-distractionfree wpzabb-help-tooltip'></i>";
							
							$html .= "<div class='wpzabb-size-wrap' ".$simplify_style.$data_mode.">";
							$html .= $this->wpzabb_spacing_param_media($name, $class, $dashicon, $key, $default_value ,$selector, $data_id, $input_class, $placeholder['all']);
							$html .= "</div>";
							$html .= "<div class='wpzabb-spacing-size-wrap' ".$simplify_option_style.$data_mode.">";
						break;
						case 'top':
							$input_class = 'expanded';
							$selector = $this->wpzabb_preview( $field, $key, $mode );
							$class = 'fl-field optional';
							$data_id  = strtolower((preg_replace('/\s+/', '_', $key)));
							$dashicon = "<i class='dashicons dashicons-arrow-up-alt wpzabb-help-tooltip' style='transform: rotate(90deg);'></i>";
							$html .= $this->wpzabb_spacing_param_media($name, $class, $dashicon, $key, $default_value ,$selector, $data_id, $input_class, $placeholder['top']);
						break;
						case 'right':       
							$input_class = 'expanded';
							$selector = $this->wpzabb_preview( $field, $key, $mode );
							$class = 'fl-field optional';
							$data_id  = strtolower((preg_replace('/\s+/', '_', $key)));
							$dashicon = "<i class='dashicons dashicons-arrow-right-alt wpzabb-help-tooltip'></i>";
							$html .= $this->wpzabb_spacing_param_media($name, $class, $dashicon, $key, $default_value , $selector, $data_id, $input_class, $placeholder['right']);
						break;
						case 'bottom':        
							$input_class = 'expanded';
							$selector = $this->wpzabb_preview( $field, $key, $mode );
							$class = 'fl-field optional';
							$data_id  = strtolower((preg_replace('/\s+/', '_', $key)));
							$dashicon = "<i class='dashicons dashicons-arrow-down-alt wpzabb-help-tooltip' style='transform: rotate(90deg);'></i>";
							$html .= $this->wpzabb_spacing_param_media($name, $class, $dashicon, $key, $default_value , $selector, $data_id, $input_class, $placeholder['bottom']);
						break;
						case 'left':        
							$input_class = 'expanded';
							$selector = $this->wpzabb_preview( $field, $key, $mode );
							$class = 'fl-field optional';
							$data_id  = strtolower((preg_replace('/\s+/', '_', $key)));
							$dashicon = "<i class='dashicons dashicons-arrow-left-alt wpzabb-help-tooltip'></i>";
							$html .= $this->wpzabb_spacing_param_media($name, $class, $dashicon, $key, $default_value , $selector, $data_id, $input_class, $placeholder['left']);
						break;
					}
				}
				$html .= '</div>'; // Close options wrapper
			$html .= '  </div>';
			//$html .= $this->get_units($unit);
			//$html .= '  <input type="hidden" data-unit="'.$unit.'"  name="'.$settings['param_name'].'" class="wpb_vc_param_value ultimate-responsive-value '.$settings['param_name'].' '.$settings['type'].'_field" value="'.$value.'" '.$dependency.' />';
	
			$html .= '</div>';
		
			echo $html;
		}
		
		/*function wpzabb_spacing_assets() {
		    if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {
		    	wp_enqueue_style( 'wpzabb-spacing', BB_WPZOOM_ADDON_URL . 'fields/wpzabb-spacing/css/wpzabb-spacing.css', array(), '' );
		        wp_enqueue_script( 'wpzabb-spacing', BB_WPZOOM_ADDON_URL . 'fields/wpzabb-spacing/js/wpzabb-spacing.js', array(), '', true );
		    }
		}*/

		function wpzabb_preview( $field, $key, $mode ){
			$wpzabb_preview = '';
			switch ( $key ) {
				case 'all':
					$wpzabb_preview = isset($field['preview']) ? json_encode($field['preview']) : json_encode(array('type' => 'refresh'));
					break;
				
				case 'top':
				case 'right':
				case 'bottom':
				case 'left':
					if ( isset( $field['preview'] ) ) {
						$field['preview']['property'] = $mode.'-'.$key;
					}else{
						$field['preview'] = array('type' => 'refresh');
					}
					$wpzabb_preview = json_encode($field['preview']) ;
					break;
				
				default:
					$wpzabb_preview = json_encode(array('type' => 'refresh'));
					break;
			}
			return ' data-type="text" data-preview=\'' . $wpzabb_preview . '\'';
		}
		function wpzabb_spacing_param_media($name, $class, $dashicon, $key, $default_value, $selector, $data_id, $input_class, $placeholder_value) {
			$tooltipVal  = str_replace('_', ' ', $data_id);
			$html  = '<div class="wpzabb-spacing-item '.$class.' '.$data_id.'" '.$selector.'>';
				/*$html .= '<span class="wpzabb-icon">';
					$html .=          $dashicon;
        			$html .= '<div class="wpzabb-tooltip '.$data_id.'">'.ucwords($tooltipVal).'</div>';
				$html .= '</span>';*/
				$html .= '    <input type="text" placeholder="'.$placeholder_value.'"name="'.$name.'[]['.$key.']" class="wpzabb-spacing-input '.$input_class.'" maxlength="3" size="6" value="'.$default_value.'" data-field="'.$key.'"/>';
			$html .= '  </div>';
			return $html;
		}
	
	}
	new WPZABB_Spacing();
}