<?php

/**
 * 	FLBuilder Registered Nested Forms - Button Form Field
 */

FLBuilder::register_settings_form('button_form_field', array(
    'title' => __('Button', 'wpzabb'),
    'tabs'  => array(
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
                                'selector'        => '.wpzabb-creative-button-text'
                            )
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
                        ),
                        'link_target'   => array(
                            'type'          => 'select',
                            'label'         => __('Link Target', 'wpzabb'),
                            'default'       => '_self',
                            'options'       => array(
                                '_self'         => __('Same Window', 'wpzabb'),
                                '_blank'        => __('New Window', 'wpzabb')
                            ),
                        )
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
                            'class'         => 'creative_button_styles',
                            'options'       => array(
                                'flat'          => __('Flat', 'wpzabb'),
                                'gradient'      => __('Gradient', 'wpzabb'),
                                'transparent'   => __('Transparent', 'wpzabb'),
                                'threed'          => __('3D', 'wpzabb'),
                            ),
                            'toggle'        => array(
                                'transparent'   => array(
                                    'fields'    => array( 'border_size', 'transparent_button_options'),
                                ),
                                'threed'   => array(
                                    'fields'    => array( 'threed_button_options' )
                                ),
                                'flat'   => array(
                                    'fields'    => array( 'flat_button_options' )
                                ),
                                
                            )
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
                                'transparent-fill-center'       => __('Fill Background Vertical', 'wpzabb'),
                                'transparent-fill-diagonal'     => __('Fill Background Diagonal', 'wpzabb'),
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
                            'default'       => 'before',
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
                        'text_hover_color' => array( 
                            'type'       => 'color',
                            'label'   => __('Text Hover Color', 'wpzabb'),
                            'default' => '',
                            'show_reset' => true,
                            'preview' => array(
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
                            'label'         => __('Background Hover Color', 'wpzabb'),
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
                            'type'          => 'wpzabb-toggle-switch',
                            'label'         => __( 'Apply Hover Color To', 'wpzabb' ),
                            'default'       => 'bg',
                            'options'       => array(
                                'border'    => __( 'Border', 'wpzabb' ),
                                'bg'        => __( 'Background', 'wpzabb' ),
                            ),
                            'width' => '75px'
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
                                    'fields'        => array('align', 'mob_align', 'line_height')
                                ),
                                'full'          => array(
                                    'fields'        => array( 'line_height' )
                                ),
                                'custom'        => array(
                                    'fields'        => array('align', 'mob_align', 'custom_width', 'custom_height', 'padding_top_bottom', 'padding_left_right' )
                                )
                            )
                        ),
                        'custom_width'  => array(
                            'type'          => 'text',
                            'label'         => __('Custom Width', 'wpzabb'),
                            'default'       => '200',
                            'maxlength'     => '3',
                            'size'          => '4',
                            'description'   => 'px'
                        ),
                        'custom_height'  => array(
                            'type'          => 'text',
                            'label'         => __('Custom Height', 'wpzabb'),
                            'default'       => '45',
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
                                'selector'        => '.wpzabb-creative-button'
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
                        ),
                    )
                ),
            )
        )
    )
));
