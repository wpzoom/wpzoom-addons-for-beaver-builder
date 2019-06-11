<?php

/**
 * @class WPZABBFoodMenuModule
 */
class WPZABBFoodMenuModule extends FLBuilderModule {
	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'          	=> __( 'Food Menu', 'wpzabb' ),
			'description'   	=> __( 'A menu of food items.', 'wpzabb' ),
			'category'      	=> WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/'. WPZABB_PREFIX .'food-menu/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/'. WPZABB_PREFIX .'food-menu/',
            'partial_refresh'	=> true,
			'icon' 				=> 'hamburger-menu.svg'
		) );
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'WPZABBFoodMenuModule', array(
	'general'    => array( // Tab
		'title'    => __( 'General', 'wpzabb' ), // Tab title
		'sections' => array( // Tab Sections
			'general'                => array( // Section
				'title'     => '', // Section Title
				'fields'    => array( // Section Fields
					'menu_title'             => array(
						'type'          => 'text',
						'label'         => __( 'Menu Title', 'wpzabb' ),
						'placeholder'   => __( 'The title of the menu...', 'wpzabb' ),
						'default'       => 'Our Menu'
					),
					'menu_button'            => array(
						'type'          => 'select',
						'label'         => __( 'Show Menu Button', 'wpzabb' ),
						'default'       => 'no',
						'options'       => array(
							'yes'             => __( 'Yes', 'wpzabb' ),
							'no'              => __( 'No', 'wpzabb' )
						),
						'toggle'        => array(
							'yes'             => array(
								'sections' => array( 'style_button' ),
								'fields'   => array( 'menu_button_label', 'menu_button_url' )
							),
							'no'              => array()
						)
					),
					'menu_button_label'      => array(
						'type'          => 'text',
						'label'         => __( 'Menu Button Label', 'wpzabb' ),
						'default'       => __( 'View Full Menu', 'wpzabb' )
					),
					'menu_button_url'        => array(
						'type'          => 'link',
						'label'         => __( 'Menu Button URL', 'wpzabb' ),
						'preview'       => array( 'type' => 'none' ),
						'connections'   => array( 'url' ),
						'show_target'   => true,
						'show_nofollow'	=> true
					)
				)
			)
		)
	),
	'menu_items' => array( // Tab
		'title'    => __( 'Menu Items', 'wpzabb' ), // Tab title
		'sections' => array( // Tab Sections
			'items'                  => array( // Section
				'title'     => '', // Section Title
				'fields'    => array( // Section Fields
					'menu_items'             => array(
						'type'          => 'form',
						'label'         => __( 'Menu Item', 'wpzabb' ),
						'form'          => 'food_menu_form', // ID from registered form below
						'multiple'      => true,
					)
				)
			)
		)
	),
	'style'      => array( // Tab
		'title'    => __( 'Style', 'wpzabb' ), // Tab title
		'sections' => array( // Tab Sections
			'style_background'       => array( // Section
				'title'     => __( 'Menu Background', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'background_color'       => array(
						'type'          => 'color',
						'label'         => __( 'Background Color', 'wpzabb' ),
						'default'       => 'ffffff',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap',
							'property'        => 'background-color'
						)
					),
					'outline_color'          => array(
						'type'          => 'color',
						'label'         => __( 'Outline Color', 'wpzabb' ),
						'default'       => 'e9e4e2',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap::before',
							'property'        => 'border-color'
						)
					)
				)
			),
			'style_title'            => array( // Section
				'title'     => __( 'Menu Title', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'title_font'             => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => 'Playfair Display',
							'font_weight'     => 400,
							'font_size'       => array(
								'length'     => '28',
								'unit'       => 'px'
							),
							'line_height'     => array(
								'length'     => '1.3',
								'unit'       => 'em'
							),
							'text_align'      => 'center',
							'letter_spacing'  => array(
								'length'     => '0',
								'unit'       => 'px'
							),
							'text_transform'  => 'none',
							'text_decoration' => 'none',
							'font_style'      => 'italic',
							'font_variant'    => 'normal',
							'text_shadow'     => array(
								'color'      => '',
								'horizontal' => 0,
								'vertical'   => 0,
								'blur'       => 0
							)
						),
						'responsive'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-title'
						)
					),
					'title_color'            => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => '333333',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-title',
							'property'        => 'color'
						)
					)
				)
			),
			'style_item_name'        => array( // Section
				'title'     => __( 'Menu Item Name', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'item_name_font'         => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => 'Playfair Display',
							'font_weight'     => 700,
							'font_size'       => array(
								'length'     => '20',
								'unit'       => 'px'
							),
							'line_height'     => array(
								'length'     => '1.1',
								'unit'       => ''
							),
							'text_align'      => 'left',
							'letter_spacing'  => array(
								'length'     => '0',
								'unit'       => 'px'
							),
							'text_transform'  => 'uppercase',
							'text_decoration' => 'none',
							'font_style'      => 'normal',
							'font_variant'    => 'normal',
							'text_shadow'     => array(
								'color'      => '',
								'horizontal' => 0,
								'vertical'   => 0,
								'blur'       => 0
							)
						),
						'responsive'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-name'
						)
					),
					'item_name_color'        => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => '222222',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-name, .wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-name a',
							'property'        => 'color'
						)
					),
					'item_name_hover_color'  => array(
						'type'          => 'color',
						'label'         => __( 'Hover Color', 'wpzabb' ),
						'default'       => 'c16f2d',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-name a:hover',
							'property'        => 'color'
						)
					)
				)
			),
			'style_item_price'       => array( // Section
				'title'     => __( 'Menu Item Price', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'item_price_font'        => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => 'PT Serif',
							'font_weight'     => 400,
							'font_size'       => array(
								'length'     => '18',
								'unit'       => 'px'
							),
							'line_height'     => array(
								'length'     => '1.1',
								'unit'       => ''
							),
							'text_align'      => 'right',
							'letter_spacing'  => array(
								'length'     => '0',
								'unit'       => 'px'
							),
							'text_transform'  => 'none',
							'text_decoration' => 'none',
							'font_style'      => 'normal',
							'font_variant'    => 'normal',
							'text_shadow'     => array(
								'color'      => '',
								'horizontal' => 0,
								'vertical'   => 0,
								'blur'       => 0
							)
						),
						'responsive'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-price'
						)
					),
					'item_price_color'       => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => '222222',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-price',
							'property'        => 'color'
						)
					)
				)
			),
			'style_item_description' => array( // Section
				'title'     => __( 'Menu Item Description', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'item_description_font'  => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => 'PT Serif',
							'font_weight'     => 400,
							'font_size'       => array(
								'length'     => '16',
								'unit'       => 'px'
							),
							'line_height'     => array(
								'length'     => '1.6',
								'unit'       => ''
							),
							'text_align'      => 'left',
							'letter_spacing'  => array(
								'length'     => '0',
								'unit'       => 'px'
							),
							'text_transform'  => 'none',
							'text_decoration' => 'none',
							'font_style'      => 'normal',
							'font_variant'    => 'normal',
							'text_shadow'     => array(
								'color'      => '',
								'horizontal' => 0,
								'vertical'   => 0,
								'blur'       => 0
							)
						),
						'responsive'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-description'
						)
					),
					'item_description_color' => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => 'a5908d',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-description',
							'property'        => 'color'
						)
					)
				)
			),
			'style_item_separator'   => array( // Section
				'title'     => __( 'Menu Item Separator', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'item_separator_style'   => array(
						'type'          => 'select',
						'label'         => __( 'Style', 'wpzabb' ),
						'default'       => 'dashed',
						'options'       => array(
							'none'            => __( 'None', 'wpzabb' ),
							'solid'           => __( 'Solid', 'wpzabb' ),
							'dashed'          => __( 'Dashed', 'wpzabb' ),
							'dotted'          => __( 'Dotted', 'wpzabb' ),
							'double'          => __( 'Double', 'wpzabb' )
						),
						'preview'       => array(
							'type'            => 'css',
							'rules'           => array(
								array(
									'selector' => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item, .wpzabb-food-menu-wrap.with-button .wpzabb-food-menu-items .wpzabb-food-menu-item:last-child',
									'property' => 'border-bottom-style'
								),
								array(
									'selector' => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item:first-child',
									'property' => 'border-top-style'
								)    
							)
						)
					),
					'item_separator_size'    => array(
						'type'          => 'unit',
						'label'         => __( 'Size', 'wpzabb' ),
						'default'       => '1',
						'units'         => array( 'px', 'vw', '%' ),
						'default_unit'  => 'px',
						'slider' => array(
							'px'              => array(
								'min'        => 0,
								'max'        => 1000,
								'step'       => 1
							),
							'vw'              => array(
								'min'        => 0,
								'max'        => 100,
								'step'       => 1
							),
							'%'               => array(
								'min'        => 0,
								'max'        => 100,
								'step'       => 1
							)
						),
						'responsive'    => true,
						'preview'       => array(
							'type'            => 'css',
							'rules'           => array(
								array(
									'selector' => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item, .wpzabb-food-menu-wrap.with-button .wpzabb-food-menu-items .wpzabb-food-menu-item:last-child',
									'property' => 'border-bottom-width'
								),
								array(
									'selector' => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item:first-child',
									'property' => 'border-top-width'
								)    
							)
						)
					),
					'item_separator_color'   => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => 'ecd4c0',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'rules'           => array(
								array(
									'selector' => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item, .wpzabb-food-menu-wrap.with-button .wpzabb-food-menu-items .wpzabb-food-menu-item:last-child',
									'property' => 'border-bottom-color'
								),
								array(
									'selector' => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item:first-child',
									'property' => 'border-top-color'
								)    
							)
						)
					)
				)
			),
			'style_button'           => array( // Section
				'title'     => __( 'Menu Button', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'button_background'      => array(
						'type'          => 'color',
						'label'         => __( 'Background Color', 'wpzabb' ),
						'default'       => 'ffffff',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-button a',
							'property'        => 'background-color'
						)
					),
					'button_font'            => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => 'Playfair Display',
							'font_weight'     => 700,
							'font_size'       => array(
								'length'     => '16',
								'unit'       => 'px'
							),
							'line_height'     => array(
								'length'     => '1',
								'unit'       => ''
							),
							'text_align'      => 'center',
							'letter_spacing'  => array(
								'length'     => '0',
								'unit'       => 'px'
							),
							'text_transform'  => 'uppercase',
							'text_decoration' => 'none',
							'font_style'      => 'normal',
							'font_variant'    => 'normal',
							'text_shadow'     => array(
								'color'      => '',
								'horizontal' => 0,
								'vertical'   => 0,
								'blur'       => 0
							)
						),
						'responsive'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-button a'
						)
					),
					'button_color'           => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => 'c16f2d',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-button a',
							'property'        => 'color'
						)
					),
					'button_hover_color'     => array(
						'type'          => 'color',
						'label'         => __( 'Hover Color', 'wpzabb' ),
						'default'       => '000000',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-button a:hover',
							'property'        => 'color'
						)
					),
					'button_border'          => array(
						'type'          => 'border',
						'label'         => __( 'Border', 'wpzabb' ),
						'default'       => array(
							'style'           => 'solid',
							'color'           => 'c16f2d',
							'width'           => array(
								'top'          => 2,
								'left'         => 2,
								'right'        => 2,
								'bottom'       => 2
							),
							'radius'          => array(
								'top_left'     => 0,
								'top_right'    => 0,
								'bottom_left'  => 0,
								'bottom_right' => 0
							),
							'shadow'          => array(
								'color'        => '',
								'horizontal'   => 0,
								'vertical'     => 0,
								'blur'         => 0,
								'spread'       => 0
							)
						),
						'responsive'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-button a'
						)
					)
				)
			)
		)
	)
) );


/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form( 'food_menu_form', array(
	'title' => __( 'Add Menu Item', 'wpzabb' ),
	'tabs'  => array(
		'general' => array( // Tab
			'title'    => __( 'General', 'wpzabb' ), // Tab title
			'sections' => array( // Tab Sections
				'general' => array( // Section
					'title'  => '', // Section Title
					'fields' => array( // Section Fields
						'name'        => array(
							'type'          => 'text',
							'label'         => __( 'Name', 'wpzabb' ),
							'placeholder'   => __( 'Menu item name...', 'wpzabb' ),
							'default'       => '',
							'connections'   => array( 'string', 'html' )
						),
						'link'        => array(
							'type'          => 'link',
							'label'         => __( 'Link', 'wpzabb' ),
							'preview'       => array( 'type' => 'none' ),
							'connections'   => array( 'url' ),
							'show_target'   => true,
							'show_nofollow'	=> true
						),
						'price'       => array(
							'type'          => 'unit',
							'label'         => __( 'Price', 'wpzabb' ),
							'placeholder'   => __( '0.00', 'wpzabb' ),
							'default'       => '',
							'units'         => array( '$', '¢', '£', '€', '¥' ),
							'default_unit'  => '$',
							'connections'   => array( 'number' )
						),
						'description' => array(
							'type'          => 'textarea',
							'label'         => __( 'Description', 'wpzabb' ),
							'placeholder'   => __( 'Menu item description...', 'wpzabb' ),
							'default'       => '',
							'rows'          => 4,
							'connections'   => array( 'string', 'html' )
						)
					)
				)
			)
		)
	)
) );