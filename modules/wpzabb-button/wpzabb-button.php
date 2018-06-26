<?php

/**
 * @class WPZABBButtonModule
 */
class WPZABBButtonModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          	=> __('Button', 'wpzabb'),
			'description'   	=> __('A simple call to action button.', 'wpzabb'),
			'category'      	=> WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/'. WPZABB_PREFIX .'button/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/'. WPZABB_PREFIX .'button/',
            'partial_refresh'	=> true,
            'icon'          	=> 'button.svg'
		));

		add_filter( 'fl_builder_layout_data', array( $this , 'render_new_data' ), 10, 3 );
	}

    function render_new_data( $data ) {

        foreach ( $data as &$node ) {
            
            if ( isset( $node->settings->type ) && 'wpzabb-button' === $node->settings->type ) {

                if ( isset( $node->settings->font_size['small']) && !isset( $node->settings->font_size_unit_responsive ) ) {
                    $node->settings->font_size_unit_responsive = $node->settings->font_size['small'];
                }
                if( isset( $node->settings->font_size['medium']) && !isset( $node->settings->font_size_unit_medium ) ) {
                    $node->settings->font_size_unit_medium = $node->settings->font_size['medium'];
                }
                if( isset( $node->settings->font_size['desktop']) && !isset( $node->settings->font_size_unit ) ) {
                    $node->settings->font_size_unit = $node->settings->font_size['desktop'];
                }

                if ( isset( $node->settings->line_height['small']) && isset( $node->settings->font_size['small']) && $node->settings->font_size['small'] != 0 && !isset( $node->settings->line_height_unit_responsive ) ) {
                	if( is_numeric( $node->settings->line_height['small']) && is_numeric( $node->settings->font_size['small']) )
                    $node->settings->line_height_unit_responsive = round( $node->settings->line_height['small'] / $node->settings->font_size['small'], 2 );
                }
                if( isset( $node->settings->line_height['medium']) && isset( $node->settings->font_size['medium']) && $node->settings->font_size['medium'] != 0 && !isset( $node->settings->line_height_unit_medium ) ) {
                	if( is_numeric( $node->settings->line_height['medium']) && is_numeric( $node->settings->font_size['medium']) )
                    $node->settings->line_height_unit_medium = round( $node->settings->line_height['medium'] / $node->settings->font_size['medium'], 2 );
                }
                if( isset( $node->settings->line_height['desktop']) && isset( $node->settings->font_size['desktop']) && $node->settings->font_size['desktop'] != 0 && !isset( $node->settings->line_height_unit ) ) {
                	if( is_numeric( $node->settings->line_height['desktop']) && is_numeric( $node->settings->font_size['desktop']) )
                    $node->settings->line_height_unit = round( $node->settings->line_height['desktop'] / $node->settings->font_size['desktop'], 2 );
                }

            }
        }

    return $data;
    }


	/**
	 * @method update
	 */
	public function update( $settings )
	{
		// Remove the old three_d setting.
		if ( isset( $settings->three_d ) ) {
			unset( $settings->three_d );
		}

		return $settings;
	}

	/**
	 * @method get_classname
	 */
	public function get_classname()
	{
		$classname = 'wpzabb-button-wrap wpzabb-button-wrap';

		if(!empty($this->settings->width)) {
			$classname .= ' wpzabb-button-width-' . $this->settings->width;
			$classname .= ' wpzabb-button-width-' . $this->settings->width;
		}
		if(!empty($this->settings->align)) {
			$classname .= ' wpzabb-button-' . $this->settings->align;
			$classname .= ' wpzabb-button-' . $this->settings->align;
		}
		if(!empty($this->settings->mob_align)) {
			$classname .= ' wpzabb-button-reponsive-' . $this->settings->mob_align;
			$classname .= ' wpzabb-button-reponsive-' . $this->settings->mob_align;
		}
		if(!empty($this->settings->icon)) {
			$classname .= ' wpzabb-button-has-icon';
			$classname .= ' wpzabb-button-has-icon';
		}

		if( empty($this->settings->text) ) {
			$classname .= ' wpzabb-button-icon-no-text';
		}

		return $classname;
	}

	/**
	 * Returns button link rel based on settings
	 * @method get_rel
	 */
	public function get_rel() {
		$rel = array();
		if ( '_blank' == $this->settings->link_target ) {
			$rel[] = 'noopener';
		}
		if ( isset( $this->settings->link_nofollow ) && 'yes' == $this->settings->link_nofollow ) {
			$rel[] = 'nofollow';
		}
		$rel = implode( ' ', $rel );
		if ( $rel ) {
			$rel = ' rel="' . $rel . '" ';
		}
		return $rel;
	}

	/**
	 * @method get_button_style
	 */
	public function get_button_style()
	{
		$btn_style = '';

		if(!empty($this->settings->style) && $this->settings->style == "transparent" ) {
			if( isset( $this->settings->transparent_button_options ) && !empty( $this->settings->transparent_button_options ) )
			$btn_style .= ' wpzabb-' . $this->settings->transparent_button_options .'-btn';
		}

		if(!empty($this->settings->style) && $this->settings->style == "threed" ) {
			if( isset( $this->settings->threed_button_options ) && !empty( $this->settings->threed_button_options ) )
			$btn_style .= ' wpzabb-' . $this->settings->threed_button_options .'-btn';
		}

		if(!empty($this->settings->style) && $this->settings->style == "flat" ) {
			if( isset( $this->settings->flat_button_options ) && !empty( $this->settings->flat_button_options ) )
			$btn_style .= ' wpzabb-' . $this->settings->flat_button_options .'-btn';
		}

		return $btn_style;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('WPZABBButtonModule', array(
	'general'       => array(
		'title'         => __('General', 'wpzabb'),
		'sections'      => array(
			'general'       => array(
				'title'         => '',
				'fields'        => array(
					'text'          => array(
						'type'          => 'text',
						'label'         => __('Text', 'wpzabb'),
						'default'       => __('Click Here', 'wpzabb'),
						'preview'         => array(
							'type'            => 'text',
							'selector'        => '.wpzabb-button-text'
						),
						'connections'	=> array( 'string', 'html' )
					),

				)
			),
			'link'          => array(
				'title'         => __('Link', 'wpzabb'),
				'fields'        => array(
					'link'          => array(
						'type'          => 'link',
						'label'         => __('Link', 'wpzabb'),
						'placeholder'   => 'http://www.example.com',
						'default'		=> '#',
						'preview'       => array(
							'type'          => 'none'
						),
						'connections'	=> array( 'url' )
					),
					'link_target'   => array(
						'type'          => 'select',
						'label'         => __( 'Link Target', 'wpzabb' ),
						'default'       => '_self',
						'options'       => array(
							'_self'         => __( 'Same Window', 'wpzabb' ),
							'_blank'        => __( 'New Window', 'wpzabb' ),
						),
						'preview'       => array(
							'type'          => 'none',
						),
					),
					'link_nofollow'          => array(
						'type'          => 'select',
						'label'         => __( 'Link No Follow', 'wpzabb' ),
						'default'       => 'no',
						'options' 		=> array(
							'yes' 			=> __( 'Yes', 'wpzabb' ),
							'no' 			=> __( 'No', 'wpzabb' ),
						),
						'preview'       => array(
							'type'          => 'none',
						),
					),
				)
			)
		)
	),
	'style'         => array(
		'title'         => __('Style', 'wpzabb'),
		'sections'      => array(
			'style'         => array(
				'title'         => __('Style', 'wpzabb'),
				'fields'        => array(
					'style'         => array(
						'type'          => 'select',
						'label'         => __('Style', 'wpzabb'),
						'default'       => 'flat',
						'class'			=> 'creative_button_styles',
						'options'       => array(
							'flat'          => __('Flat', 'wpzabb'),
							'gradient'      => __('Gradient', 'wpzabb'),
							'transparent'   => __('Transparent', 'wpzabb'),
							'threed'          => __('3D', 'wpzabb'),
						),
					),
					'border_size'   => array(
						'type'          => 'text',
						'label'         => __('Border Size', 'wpzabb'),
						'description'   => 'px',
						'maxlength'     => '3',
						'size'          => '5',
						'placeholder'   => '2'
					),
					'transparent_button_options'         => array(
						'type'          => 'select',
						'label'         => __('Hover Styles', 'wpzabb'),
						'default'       => 'transparent-fade',
						'options'       => array(
							'none'          => __('None', 'wpzabb'),
							'transparent-fade'          => __('Fade Background', 'wpzabb'),
							'transparent-fill-top'      => __('Fill Background From Top', 'wpzabb'),
							'transparent-fill-bottom'      => __('Fill Background From Bottom', 'wpzabb'),
							'transparent-fill-left'     => __('Fill Background From Left', 'wpzabb'),
							'transparent-fill-right'     => __('Fill Background From Right', 'wpzabb'),
							'transparent-fill-center'   	=> __('Fill Background Vertical', 'wpzabb'),
							'transparent-fill-diagonal'   	=> __('Fill Background Diagonal', 'wpzabb'),
							'transparent-fill-horizontal'  => __('Fill Background Horizontal', 'wpzabb'),
						),
					),
					'threed_button_options'         => array(
						'type'          => 'select',
						'label'         => __('Hover Styles', 'wpzabb'),
						'default'       => 'threed_down',
						'options'       => array(
							'threed_down'          => __('Move Down', 'wpzabb'),
							'threed_up'      => __('Move Up', 'wpzabb'),
							'threed_left'      => __('Move Left', 'wpzabb'),
							'threed_right'     => __('Move Right', 'wpzabb'),
							'animate_top'     => __('Animate Top', 'wpzabb'),
							'animate_bottom'     => __('Animate Bottom', 'wpzabb'),
							/*'animate_left'     => __('Animate Left', 'wpzabb'),
							'animate_right'     => __('Animate Right', 'wpzabb'),*/
						),
					),
					'flat_button_options'         => array(
						'type'          => 'select',
						'label'         => __('Hover Styles', 'wpzabb'),
						'default'       => 'none',
						'options'       => array(
							'none'          => __('None', 'wpzabb'),
							'animate_to_left'      => __('Appear Icon From Right', 'wpzabb'),
							'animate_to_right'          => __('Appear Icon From Left', 'wpzabb'),
							'animate_from_top'      => __('Appear Icon From Top', 'wpzabb'),
							'animate_from_bottom'     => __('Appear Icon From Bottom', 'wpzabb'),
						),
					),
				)
			),
			'icon'    => array(
				'title'         => __('Icons', 'wpzabb'),
				'fields'        => array(
					'icon'          => array(
						'type'          => 'icon',
						'label'         => __('Icon', 'wpzabb'),
						'show_remove'   => true
					),
					'icon_position' => array(
						'type'          => 'select',
						'label'         => __('Icon Position', 'wpzabb'),
						'default'       => 'after',
						'options'       => array(
							'before'        => __('Before Text', 'wpzabb'),
							'after'         => __('After Text', 'wpzabb')
						)
					)
				)
			),
			'colors'        => array(
				'title'         => __('Colors', 'wpzabb'),
				'fields'        => array(
					'text_color'        => array( 
						'type'       => 'color',
                        'label'         => __('Text Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
					),
					'text_hover_color'   => array( 
						'type'       => 'color',
                        'label'         => __('Text Hover Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
                        'preview'       => array(
							'type'          => 'none'
						)
					),
					'bg_color'        => array( 
						'type'       => 'color',
                        'label'         => __('Background Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
					),
                    'bg_color_opc'    => array( 
						'type'        => 'text',
						'label'       => __('Opacity', 'wpzabb'),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
					'bg_hover_color'        => array( 
						'type'       => 'color',
                        'label'      => __('Background Hover Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
                        'preview'       => array(
							'type'          => 'none'
						)
					),
                    'bg_hover_color_opc'    => array( 
						'type'        => 'text',
						'label'       => __('Opacity', 'wpzabb'),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
                    'hover_attribute' => array(
                    	'type'          => 'select',
                        'label'         => __( 'Apply Hover Color To', 'wpzabb' ),
                        'default'       => 'bg',
                        'options'       => array(
                            'border'    => __( 'Border', 'wpzabb' ),
                            'bg'        => __( 'Background', 'wpzabb' ),
                        ),
                        'width'	=> '75px'
                    ),

				)
			),
			'formatting'    => array(
				'title'         => __('Structure', 'wpzabb'),
				'fields'        => array(
					'width'         => array(
						'type'          => 'select',
						'label'         => __('Width', 'wpzabb'),
						'default'       => 'auto',
						'options'       => array(
							'auto'          => _x( 'Auto', 'Width.', 'wpzabb' ),
							'full'          => __('Full Width', 'wpzabb'),
							'custom'        => __('Custom', 'wpzabb')
						),
						'toggle'        => array(
							'auto'          => array(
								'fields'        => array('align', 'mob_align' )
							),
							'full'          => array(
								'fields'		=> array()
							),
							'custom'        => array(
								'fields'        => array('align', 'mob_align', 'custom_width', 'custom_height', 'padding_top_bottom', 'padding_left_right' )
							)
						)
					),
					'custom_width'  => array(
						'type'          => 'text',
						'label'         => __('Custom Width', 'wpzabb'),
						'default'   	=> '200',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px'
					),
					'custom_height'  => array(
						'type'          => 'text',
						'label'         => __('Custom Height', 'wpzabb'),
						'default'   	=> '45',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px'
					),
					'padding_top_bottom'       => array(
						'type'          => 'text',
						'label'         => __('Padding Top/Bottom', 'wpzabb'),
						'placeholder'   => '0',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px'
					),
					'padding_left_right'       => array(
						'type'          => 'text',
						'label'         => __('Padding Left/Right', 'wpzabb'),
						'placeholder'   => '0',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px'
					),
					'border_radius' => array(
						'type'          => 'text',
						'label'         => __('Round Corners', 'wpzabb'),
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px'
					),
					'align'         => array(
						'type'          => 'select',
						'label'         => __('Alignment', 'wpzabb'),
						'default'       => 'center',
						'options'       => array(
							'center'        => __('Center', 'wpzabb'),
							'left'          => __('Left', 'wpzabb'),
							'right'         => __('Right', 'wpzabb')
						)
					),
					'mob_align'         => array(
						'type'          => 'select',
						'label'         => __('Mobile Alignment', 'wpzabb'),
						'default'       => 'center',
						'options'       => array(
							'center'        => __('Center', 'wpzabb'),
							'left'          => __('Left', 'wpzabb'),
							'right'         => __('Right', 'wpzabb')
						)
					),
				)
			)
		)
	),
	'creative_typography'         => array(
		'title'         => __('Typography', 'wpzabb'),
		'sections'      => array(
			'typography'    =>  array(
                'title'     => __('Button Settings', 'wpzabb' ) ,
		        'fields'    => array(
		            'font_family'       => array(
		                'type'          => 'font',
		                'label'         => __('Font Family', 'wpzabb'),
		                'default'       => array(
		                    'family'        => 'Default',
		                    'weight'        => 'Default'
		                ),
                        'preview'         => array(
                            'type'            => 'font',
                            'selector'        => '.wpzabb-button'
                        )
		            ),
		            'font_size_unit'     => array(
		                'type'          => 'unit',
		                'label'         => __( 'Font Size', 'wpzabb' ),
		                'description'   => 'px',
		                'responsive' => array(
                            'placeholder' => array(
                                'default' => '',
                                'medium' => '',
                                'responsive' => '',
                            ),
                        ),
		                'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.wpzabb-button',
                            'property'		=>	'font-size',
                            'unit'			=> 'px'
                        )
		            ),
		            'line_height_unit'    => array(
		                'type'          => 'unit',
		                'label'         => __( 'Line Height', 'wpzabb' ),
		                'description'   => 'em',
		                'responsive' => array(
                            'placeholder' => array(
                                'default' => '',
                                'medium' => '',
                                'responsive' => '',
                            ),
                        ),
		                'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.wpzabb-button',
                            'property'		=>	'line-height',
                            'unit'			=> 'em'
                        )
		            ),
		            'text_transform' => array(
		            	'type'          => 'select',
		            	'label'         => __( 'Text Transform', 'wpzabb' ),
		            	'default'       => 'none',
		            	'options'       => array(
		            		'none'			=> __( 'None', 'wpzabb' ),
		            		'uppercase'		=> __( 'Uppercase', 'wpzabb' ),
		            		'lowercase'		=> __( 'Lowercase', 'wpzabb' ),
		            		'capitalize'	=> __( 'Capitalize', 'wpzabb' ),
		            	),
		            	'preview'      => array(
		            		'type'         => 'css',
		            		'selector'     => '.wpzabb-button',
		            		'property'     => 'text-transform',
		            	),
		            ),
		            'letter_spacing'     => array(
		            	'type'          => 'select',
		            	'label'         => __( 'Letter Spacing', 'wpzabb' ),
		            	'default'       => 'default',
		            	'options'       => array(
		            		'default'       => __( 'Default', 'wpzabb' ),
		            		'custom'        => __( 'Custom', 'wpzabb' ),
		            	),
		            	'toggle'        => array(
		            		'custom'        => array(
		            			'fields'        => array( 'custom_letter_spacing' ),
		            		),
		            	),
		            ),
		            'custom_letter_spacing' => array(
		            	'type'          => 'unit',
		            	'label'         => __( 'Custom Letter Spacing', 'wpzabb' ),
		            	'description'   => 'px',
                        'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
		                'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.wpzabb-button',
                            'property'		=>	'letter-spacing',
                            'unit'			=> 'px'
                        )
		            ),
		        )
		    ),
		)
	)
));
