<?php

/**
 * @class WPZABBTestimonialsModule
 */
class WPZABBTestimonialsModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'          	=> __( 'Testimonials', 'wpzabb' ),
			'description'   	=> __( 'An animated tesimonials area.', 'wpzabb' ),
			'category'      	=> WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/wpzabb-testimonials/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/wpzabb-testimonials/',
            'partial_refresh'	=> true,
			'icon' 				=> 'format-quote.svg',
		));

	}


	/**
 	 * @method enqueue_scripts
 	 */
	public function enqueue_scripts() {
		if ( $this->settings && 'compact' == $this->settings->layout && $this->settings->arrows ) {
			$this->add_css( 'font-awesome-5' );
		}
		$this->add_css( 'jquery-bxslider' );
		$this->add_js( 'jquery-bxslider' );
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('WPZABBTestimonialsModule', array(
	'general'      => array( // Tab
		'title'         => __( 'General', 'wpzabb' ), // Tab title
		'sections'      => array( // Tab Sections
			'general'       => array( // Section
				'title'         => '', // Section Title
				'fields'        => array( // Section Fields
					'layout'       => array(
						'type'          => 'select',
						'label'         => __( 'Layout', 'wpzabb' ),
						'default'       => 'compact',
						'options'       => array(
							'wide'             => __( 'Wide', 'wpzabb' ),
							'compact'             => __( 'Compact', 'wpzabb' ),
						),
						'toggle'        => array(
							'compact'      => array(
								'sections'      => array( 'heading', 'arrow_nav' ),
							),
							'wide'      => array(
								'sections'      => array( 'dot_nav' ),
							),
						),
						'help' => __( 'Wide is for 1 column rows, compact is for multi-column rows.', 'wpzabb' ),
					),
				),
			),
			'heading'       => array( // Section
				'title'         => __( 'Heading', 'wpzabb' ), // Section Title
				'fields'        => array( // Section Fields
					'heading'         => array(
						'type'          => 'text',
						'default'       => __( 'Testimonials', 'wpzabb' ),
						'label'         => __( 'Heading', 'wpzabb' ),
						'preview'       => array(
							'type'          => 'text',
							'selector'      => '.wpzabb-testimonials-heading',
						),
					),
					'heading_size'         => array(
						'type'          => 'text',
						'label'         => __( 'Heading Size', 'wpzabb' ),
						'default'       => '24',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px',
					),
				),
			),
			'slider'       => array( // Section
				'title'         => __( 'Slider Settings', 'wpzabb' ), // Section Title
				'fields'        => array( // Section Fields
					'auto_play'     => array(
						'type'          => 'select',
						'label'         => __( 'Auto Play', 'wpzabb' ),
						'default'       => '1',
						'options'       => array(
							'0'             => __( 'No', 'wpzabb' ),
							'1'             => __( 'Yes', 'wpzabb' ),
						),
					),
					'pause'         => array(
						'type'          => 'text',
						'label'         => __( 'Delay', 'wpzabb' ),
						'default'       => '4',
						'maxlength'     => '4',
						'size'          => '5',
						'sanitize'		=> 'absint',
						'description'   => _x( 'seconds', 'Value unit for form field of time in seconds. Such as: "5 seconds"', 'wpzabb' ),
					),
					'transition'    => array(
						'type'          => 'select',
						'label'         => __( 'Transition', 'wpzabb' ),
						'default'       => 'slide',
						'options'       => array(
							'horizontal'    => _x( 'Slide', 'Transition type.', 'wpzabb' ),
							'fade'          => __( 'Fade', 'wpzabb' ),
						),
					),
					'speed'         => array(
						'type'          => 'text',
						'label'         => __( 'Transition Speed', 'wpzabb' ),
						'default'       => '0.5',
						'maxlength'     => '4',
						'size'          => '5',
						'sanitize'		=> 'floatval',
						'description'   => _x( 'seconds', 'Value unit for form field of time in seconds. Such as: "5 seconds"', 'wpzabb' ),
					),
					'direction'   => array(
						'type'          => 'select',
						'label'         => __( 'Transition Direction', 'wpzabb' ),
						'default'       => 'next',
						'options'       => array(
							'next'    		=> __( 'Right To Left', 'wpzabb' ),
							'prev'          => __( 'Left To Right', 'wpzabb' ),
						),
					),
				),
			),
			'arrow_nav'       => array( // Section
				'title'         => __( 'Arrows', 'wpzabb' ),
				'fields'        => array( // Section Fields
					'arrows'       => array(
						'type'          => 'select',
						'label'         => __( 'Show Arrows', 'wpzabb' ),
						'default'       => '1',
						'options'       => array(
							'0'             => __( 'No', 'wpzabb' ),
							'1'             => __( 'Yes', 'wpzabb' ),
						),
						'toggle'        => array(
							'1'         => array(
								'fields'        => array( 'arrow_color' ),
							),
						),
					),
					'arrow_color'       => array(
						'type'          => 'color',
						'label'         => __( 'Arrow Color', 'wpzabb' ),
						'default'       => '999999',
						'show_reset'    => true,
						'preview'       => array(
							'type'          => 'css',
							'selector'      => '.wpzabb-testimonials-wrap .fa',
							'property'      => 'color',
						),
					),
				),
			),
			'dot_nav'       => array( // Section
				'title'         => __( 'Dots', 'wpzabb' ), // Section Title
				'fields'        => array( // Section Fields
					'dots'       => array(
						'type'          => 'select',
						'label'         => __( 'Show Dots', 'wpzabb' ),
						'default'       => '1',
						'options'       => array(
							'0'             => __( 'No', 'wpzabb' ),
							'1'             => __( 'Yes', 'wpzabb' ),
						),
						'toggle'        => array(
							'1'         => array(
								'fields'        => array( 'dot_color' ),
							),
						),
					),
					'dot_color'       => array(
						'type'          => 'color',
						'label'         => __( 'Dot Color', 'wpzabb' ),
						'default'       => '999999',
						'show_reset'    => true,
					),
				),
			),
		),
	),
	'testimonials'      => array( // Tab
		'title'         => __( 'Testimonials', 'wpzabb' ), // Tab title
		'sections'      => array( // Tab Sections
			'general'       => array( // Section
				'title'         => '', // Section Title
				'fields'        => array( // Section Fields
					'testimonials'     => array(
						'type'          => 'form',
						'label'         => __( 'Testimonial', 'wpzabb' ),
						'form'          => 'testimonials_form', // ID from registered form below
						'preview_text'  => 'testimonial', // Name of a field to use for the preview text
						'multiple'      => true,
					),
				),
			),
		),
	),
));


/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form('testimonials_form', array(
	'title' => __( 'Add Testimonial', 'wpzabb' ),
	'tabs'  => array(
		'general'      => array( // Tab
			'title'         => __( 'General', 'wpzabb' ), // Tab title
			'sections'      => array( // Tab Sections
				'general'       => array( // Section
					'title'         => '', // Section Title
					'fields'        => array( // Section Fields
						'testimonial'          => array(
							'type'          => 'editor',
							'label'         => '',
						),
					),
				),
			),
		),
	),
));
