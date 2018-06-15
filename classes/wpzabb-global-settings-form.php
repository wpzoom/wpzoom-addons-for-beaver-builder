<?php

FLBuilder::register_settings_form('wpzabb-global', array(
	'title' => sprintf(
						esc_attr__( '%s - Global Settings', 'wpzabb' ),
						WPZABB_PREFIX
					),
	'tabs' => array(
		'general'  => array(
			'title'         => __('Style', 'wpzabb'),
			'sections'      => array(
				'enable_disable'	=> array(
					'title'		=> __( 'Global Styling', 'wpzabb'),
					'fields'	=> array(
						'enable_global'     => array(
                            'type'          => 'wpzabb-toggle-switch',
                            'label'         => __( 'Enable Global Styling', 'wpzabb' ),
                            'default'       => 'yes',
                            'options'       => array(
                                'yes'       	=> 'Yes',
                                'no'       		=> 'No',
                            ),
                            'toggle'         => array(
                            	'yes'	=> array(
                            		'sections'	=> array( 'theme', 'button' )
                            	)
                            )
                        ),
					)
				),
				'theme'  => array(
					'title'         => __('General', 'wpzabb'),
					'fields'        => array(
						'theme_color'	=> array( 
							'type'       => 'color',
			                'label'         => __('Primary Color', 'wpzabb'),
			                'default'       => 'f7b91a',
							'show_reset' => true,
						),
			            'theme_text_color'  => array( 
							'type'       => 'color',
			                'label'         => __('Primary Text Color', 'wpzabb'),
			                'default'       => '808285',
							'show_reset' => true,
						),
					)
				),
				'button'  => array(
					'title'         => __('Button', 'wpzabb'),
					'fields'        => array(
						'btn_bg_color'        => array( 
							'type'       => 'color',
			                'label'         => __('Background Color', 'wpzabb'),
			                'default'       => 'f7b91a',
			                'show_reset' => true,
						),
			            'btn_bg_color_opc'    => array( 
							'type'        => 'text',
							'label'       => __('Opacity', 'wpzabb'),
							'default'     => '',
							'description' => '%',
							'maxlength'   => '3',
							'size'        => '5',
						),
			            'btn_bg_hover_color'	=> array( 
							'type'       => 'color',
			                'label'         => __('Background Hover Color', 'wpzabb'),
			                'default'       => '000000',
							'show_reset' => true,
			                'preview'       => array(
		                        'type'          => 'none'
		                    )
						),
						'btn_bg_hover_color_opc' => array( 
							'type'        => 'text',
							'label'       => __('Opacity', 'wpzabb'),
							'default'     => '',
							'description' => '%',
							'maxlength'   => '3',
							'size'        => '5',
						),
			            'btn_text_color'        => array( 
							'type'       => 'color',
			                'label'         => __('Text Color', 'wpzabb'),
			                'default'       => 'ffffff',
							'show_reset' => true,
						),
			            'btn_text_hover_color'	=> array( 
							'type'       => 'color',
							'label'      => __('Text Hover Color', 'wpzabb'),
			                'default'    => 'ffffff',
							'show_reset' => true,
			                'preview'    => array(
		                        'type' => 'none'
		                    ) 
						),
			            'btn_font_size'  => array(
			                'type'          => 'text',
			                'label'         => __('Font Size', 'wpzabb'),
			                'default'       => '',
			                'maxlength'     => '3',
			                'size'          => '4',
			                'description'   => 'px'
			            ),
			            'btn_line_height'  => array(
			                'type'          => 'text',
			                'label'         => __('Line Height', 'wpzabb'),
			                'default'       => '',
			                'maxlength'     => '3',
			                'size'          => '4',
			                'description'   => 'px'
			            ),
			            'btn_letter_spacing'  => array(
			                'type'          => 'text',
			                'label'         => __('Letter Spacing', 'wpzabb'),
			                'default'       => '',
			                'maxlength'     => '3',
			                'size'          => '4',
			                'description'   => 'px'
			            ),
			            'btn_text_transform'  => array(
			                'type'          => 'select',
			                'label'         => __('Text Transform', 'wpzabb'),
			                'default'       => 'none',
			                'options'       => array(
			                    'none' 			=> __( 'None', 'wpzabb' ),
								'capitalize' 	=> __( 'Capitalize', 'wpzabb' ),
								'uppercase' 	=> __( 'Uppercase', 'wpzabb' ),
								'lowercase' 	=> __( 'Lowercase', 'wpzabb' ),
								'initial' 		=> __( 'Initial', 'wpzabb' ),
								'inherit' 		=> __( 'Inherit', 'wpzabb' ),
			                ),
			            ),
			            'btn_border_radius'  => array(
			                'type'          => 'text',
			                'label'         => __('Border Radius', 'wpzabb'),
			                'default'       => '5',
			                'maxlength'     => '3',
			                'size'          => '4',
			                'description'   => 'px'
			            ),
			            'btn_vertical_padding'  => array(
			                'type'          => 'text',
			                'label'         => __('Vertical Padding', 'wpzabb'),
			                'default'       => '',
			                'maxlength'     => '3',
			                'size'          => '4',
			                'description'   => 'px'
			            ),
			            'btn_horizontal_padding'  => array(
			                'type'          => 'text',
			                'label'         => __('Horizontal Padding', 'wpzabb'),
			                'default'       => '',
			                'maxlength'     => '3',
			                'size'          => '4',
			                'description'   => 'px'
			            ),
					)
				),
			)
		),
	)
));
