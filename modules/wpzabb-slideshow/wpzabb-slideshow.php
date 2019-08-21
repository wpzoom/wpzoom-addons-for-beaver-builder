<?php
/**
 * @class WPZABBSlideshowModule
 */
class WPZABBSlideshowModule extends FLBuilderModule {
	/**
	 * @property $data
	 */
	public $data = null;

	/**
	 * @property $_editor
	 * @protected
	 */
	protected $_editor = null;

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'          	=> __( 'Slideshow', 'wpzabb' ),
			'description'   	=> __( 'Displays a selection of items in a slideshow format.', 'wpzabb' ),
			'category'      	=> WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/' . WPZABB_PREFIX . 'slideshow/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/' . WPZABB_PREFIX . 'slideshow/',
            'partial_refresh'	=> true,
            'icon'              => 'slides.svg'
		) );

		$this->add_css( 'dashicons' );
		$this->add_css( 'jquery-flexslider', $this->url . 'css/jquery.flexslider.css', array( 'dashicons' ), '1.0' );
		$this->add_js( 'jquery-flexslider', $this->url . 'js/jquery.flexslider-min.js', array( 'jquery' ), '1.0' );
	}

	/**
	 * @method update
	 * @param $settings {object}
	 */
	public function update( $settings ) {
		$slide = $settings->slides[0];

		// Make sure we have a image_src property.
		if ( !isset( $settings->image_src ) ) {
			$settings->image_src = '';
		}

		// Cache the attachment data.
		$data = FLBuilderPhoto::get_attachment_data( $slide->image );

		if ( $data ) {
			$settings->data = $data;
		}

		return $settings;
	}

	/**
	 * @method get_data
	 * @param $slide {object}
	 */
	public function get_data( $slide ) {
		if ( !$this->data ) {
			// Photo source is set to "url".
			if ( $slide->image_source == 'url' ) {
				$this->data = new stdClass();
				$this->data->url = $slide->image_url;
				$slide->image_src = $slide->image_url;
			} else if ( is_object( $slide->image ) ) { // Photo source is set to "library".
				$this->data = $slide->image;
			} else {
				$this->data = FLBuilderPhoto::get_attachment_data( $slide->image );
			}

			// Data object is empty, use the settings cache.
			if ( !$this->data && isset( $slide->data ) ) {
				$this->data = $slide->data;
			}
		}

		return $this->data;
	}

	/**
	 * @method get_classes
	 * @param $slide {object}
	 */
	public function get_classes( $slide ) {
		$classes = array( 'wpzabb-photo-img' );
		
		if ( $slide->image_source == 'library' ) {
			if ( ! empty( $slide->image ) ) {
				$data = self::get_data( $slide );
				
				if ( is_object( $data ) ) {
					$classes[] = 'wp-image-' . $data->id;

					if ( isset( $data->sizes ) ) {
						foreach ( $data->sizes as $key => $size ) {
							if ( $size->url == $slide->image_src ) {
								$classes[] = 'size-' . $key;
								break;
							}
						}
					}
				}
			}
		}
		
		return implode( ' ', $classes );
	}

	/**
	 * @method get_src
	 * @param $slide {object}
	 */
	public function get_src( $slide ) {
		$src = $this->_get_uncropped_url( $slide );

		return $src;
	}

	/**
	 * @method _has_source
	 * @param $slide {object}
	 * @protected
	 */
	protected function _has_source( $slide ) {
		if ( $slide->image_source == 'url' && !empty( $slide->image_url ) ) {
			return true;
		} else if ( $slide->image_source == 'library' && !empty( $slide->image_src ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @method _get_editor
	 * @protected
	 */
	protected function _get_editor() {
		foreach ( $settings->slides as $i => $slide ) {
			if ( $this->_has_source( $slide ) && $this->_editor === null ) {
				$url_path  = $this->_get_uncropped_url( $slide );
				$file_path = str_ireplace( home_url(), ABSPATH, $url_path );

				if ( file_exists( $file_path ) ) {
					$this->_editor = wp_get_image_editor( $file_path );
				} else {
					$this->_editor = wp_get_image_editor( $url_path );
				}
			}
		}

		return $this->_editor;
	}

	/**
	 * @method _get_uncropped_url
	 * @param $slide {object}
	 * @protected
	 */
	protected function _get_uncropped_url( $slide ) {
		if ( $slide->image_source == 'url' ) {
			$url = $slide->image_url;
		} else if( !empty( $slide->image_src ) ) {
			$url = $slide->image_src;
		} else {
			$url = '';
		}

		return $url;
	}

	/**
	 * @method get_video_embed
	 * @param $slide {object}
	 */
	public function get_video_embed( $slide ) {
		if ( 'library' == $slide->video_source ) {
			$url = wp_get_attachment_url( $slide->video );

			return false !== $url && !empty( $url ) ? do_shortcode( '[video src="' . $url . '"]' ) : false;
		} elseif ( 'url' == $slide->video_source ) {
			$url = trim( $slide->video_url );

			return !empty( $url ) ? $this->_try_get_embed( $url, $slide ) : false;
		} else {
			return false;
		}
	}

	/**
	 * @method _try_get_embed
	 * @param $url {string}
	 * @param $options {object}
	 * @protected
	 */
	protected function _try_get_embed( $url, $options ) {
		$autoplay = $options->autoplay == 'yes' ? 'true' : 'false';
		$loop = $options->loop == 'yes' ? 'true' : 'false';
		$startmuted = $options->startmuted == 'yes' ? 'true' : 'false';

		if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match ) ) {
			$id = $match[ 1 ];

			return '<div class="video-embed" data-type="youtube" data-id="' . $id . '" data-autoplay="' . $autoplay . '" data-loop="' . $loop . '" data-startmuted="' . $startmuted . '"></div>';
		} elseif ( preg_match( '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $url, $match ) ) {
			$id = $match[ 5 ];

			return '<div class="video-embed" data-type="vimeo" data-id="' . $id . '" data-autoplay="' . $autoplay . '" data-loop="' . $loop . '" data-startmuted="' . $startmuted . '"></div>';
		} else {
			return '<iframe src="' . esc_url( $url ) . '" frameborder="0"></iframe>';
		}
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'WPZABBSlideshowModule', array(
	'general'    => array( // Tab
		'title'    => __( 'General', 'wpzabb' ), // Tab title
		'sections' => array( // Tab Sections
			'general'                => array( // Section
				'title'     => '', // Section Title
				'fields'    => array( // Section Fields
					'slideshow_auto'         => array(
						'type'          => 'button-group',
						'label'         => __( 'Autoplay Slideshow', 'wpzabb' ),
						'help'          => __( 'Whether the slideshow should automatically rotate through each slide on an interval.', 'wpzabb' ),
						'default'       => 'yes',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'yes',
								'medium'     => 'yes',
								'responsive' => 'yes'
							)
						),
						'options'       => array(
							'yes'             => __( 'Yes', 'wpzabb' ),
							'no'              => __( 'No', 'wpzabb' )
						),
						'toggle'        => array(
							'yes'             => array(
								'fields'   => array( 'slideshow_speed' )
							),
							'no'              => array()
						)
					),
					'slideshow_speed'        => array(
						'type'          => 'unit',
						'label'         => __( 'Autoplay Interval', 'wpzabb' ),
						'help'          => __( 'The interval (in miliseconds) at which the slideshow should automatically rotate.', 'wpzabb' ),
						'default'       => 3000,
						'responsive'    => array(
							'default'         => array(
								'default'    => 3000,
								'medium'     => 3000,
								'responsive' => 3000
							)
						),
						'units'         => array( 'ms' ),
						'default_unit'  => 'ms',
						'slider'        => array(
							'min'             => 0,
							'max'             => 60000,
							'step'            => 1
						)
					),
					'slideshow_transition'   => array(
						'type'          => 'select',
						'label'         => __( 'Slide Transition', 'wpzabb' ),
						'help'          => __( 'The effect used to transition between each slide.', 'wpzabb' ),
						'default'       => 'slide-horizontal',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'slide-horizontal',
								'medium'     => 'slide-horizontal',
								'responsive' => 'slide-horizontal'
							)
						),
						'options'       => array(
							'none'             => __( 'None', 'wpzabb' ),
							'fade'             => __( 'Fade', 'wpzabb' ),
							'slide-horizontal' => __( 'Horizontal Slide', 'wpzabb' ),
							'slide-vertical'   => __( 'Vertical Slide', 'wpzabb' )
						)
					),
					'slideshow_transition_speed' => array(
						'type'          => 'unit',
						'label'         => __( 'Transition Speed', 'wpzabb' ),
						'help'          => __( 'The duration (in miliseconds) of the slideshow transitions.', 'wpzabb' ),
						'default'       => 1000,
						'responsive'    => array(
							'default'         => array(
								'default'    => 1000,
								'medium'     => 1000,
								'responsive' => 1000
							)
						),
						'units'         => array( 'ms' ),
						'default_unit'  => 'ms',
						'slider'        => array(
							'min'             => 0,
							'max'             => 60000,
							'step'            => 1
						)
					),
					'slideshow_direction'    => array(
						'type'          => 'select',
						'label'         => __( 'Slideshow Direction', 'wpzabb' ),
						'help'          => __( 'The direction the slideshow moves in.', 'wpzabb' ),
						'default'       => 'forward',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'forward',
								'medium'     => 'forward',
								'responsive' => 'forward'
							)
						),
						'options'       => array(
							'forward' => __( 'Forward', 'wpzabb' ),
							'backward'   => __( 'Backward', 'wpzabb' )
						)
					),
					'slideshow_shuffle'      => array(
						'type'          => 'button-group',
						'label'         => __( 'Shuffle', 'wpzabb' ),
						'help'          => __( 'Whether the slideshow should show all the slides in a random order.', 'wpzabb' ),
						'default'       => 'no',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'no',
								'medium'     => 'no',
								'responsive' => 'no'
							)
						),
						'options'       => array(
							'yes'             => __( 'Yes', 'wpzabb' ),
							'no'              => __( 'No', 'wpzabb' )
						)
					),
					'slideshow_loop'         => array(
						'type'          => 'button-group',
						'label'         => __( 'Infinite Loop', 'wpzabb' ),
						'help'          => __( 'Whether the slideshow should loop the slides infinitely.<br/><strong>Yes:</strong> Will rotate past the last slide back to the first one again.<br/><strong>No:</strong> Will stop on the last slide and then run back to the first one.', 'wpzabb' ),
						'default'       => 'yes',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'yes',
								'medium'     => 'yes',
								'responsive' => 'yes'
							)
						),
						'options'       => array(
							'yes'             => __( 'Yes', 'wpzabb' ),
							'no'              => __( 'No', 'wpzabb' )
						)
					),
					'slideshow_hoverpause'   => array(
						'type'          => 'button-group',
						'label'         => __( 'Pause On Hover', 'wpzabb' ),
						'help'          => __( 'Whether the slideshow should pause when a pointing device hovers over it.', 'wpzabb' ),
						'default'       => 'yes',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'yes',
								'medium'     => 'yes',
								'responsive' => 'no'
							)
						),
						'options'       => array(
							'yes'             => __( 'Yes', 'wpzabb' ),
							'no'              => __( 'No', 'wpzabb' )
						)
					),
					'slideshow_arrows'       => array(
						'type'          => 'button-group',
						'label'         => __( 'Display Navigation Arrows', 'wpzabb' ),
						'help'          => __( 'Whether the slideshow should show previous/next arrow buttons for manually rotating the slides.', 'wpzabb' ),
						'default'       => 'yes',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'yes',
								'medium'     => 'yes',
								'responsive' => 'no'
							)
						),
						'options'       => array(
							'yes'             => __( 'Yes', 'wpzabb' ),
							'no'              => __( 'No', 'wpzabb' )
						)
					),
					'slideshow_navigation'   => array(
						'type'          => 'select',
						'label'         => __( 'Slide Navigation', 'wpzabb' ),
						'help'          => __( 'The type of interface used for precise slide navigation.<br/><strong>None:</strong> No navigation will be shown.<br/><strong>Dots:</strong> Each slide will be represented by a dot which is clickable to navigate to that slide.<br/><strong>Thumbnails:</strong> Each slide will be represented by a thumbnail which is clickable to navigate to that slide.', 'wpzabb' ),
						'default'       => 'none',
						'responsive'    => array(
							'default'         => array(
								'default'    => 'none',
								'medium'     => 'none',
								'responsive' => 'none'
							)
						),
						'options'       => array(
							'none'             => __( 'None', 'wpzabb' ),
							'dots'             => __( 'Dots', 'wpzabb' ),
							'thumbs'           => __( 'Thumbnails', 'wpzabb' )
						)
					)
				)
			)
		)
	),
	'slides' => array( // Tab
		'title'    => __( 'Slides', 'wpzabb' ), // Tab title
		'sections' => array( // Tab Sections
			'items'                  => array( // Section
				'title'     => '', // Section Title
				'fields'    => array( // Section Fields
					'slides'             => array(
						'type'          => 'form',
						'label'         => __( 'Slide', 'wpzabb' ),
						'form'          => 'slides_form', // ID from registered form below
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
			'style_item_image' => array( // Section
				'title'     => __( 'Menu Item Image', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'item_image_align'       => array(
						'type'          => 'button-group',
						'label'         => __( 'Alignment', 'wpzabb' ),
						'default'       => 'left',
						'options'       => array(
							'top'             => '<img src="' . BB_WPZOOM_ADDON_URL . 'modules/' . WPZABB_PREFIX . 'food-menu/align-top.svg" height="20" width="20" /> ' . __( 'Top', 'wpzabb' ),
							'left'            => '<img src="' . BB_WPZOOM_ADDON_URL . 'modules/' . WPZABB_PREFIX . 'food-menu/align-left.svg" height="20" width="20" /> ' . __( 'Left', 'wpzabb' ),
							'right'           => '<img src="' . BB_WPZOOM_ADDON_URL . 'modules/' . WPZABB_PREFIX . 'food-menu/align-right.svg" height="20" width="20" /> ' . __( 'Right', 'wpzabb' ),
							'bottom'          => '<img src="' . BB_WPZOOM_ADDON_URL . 'modules/' . WPZABB_PREFIX . 'food-menu/align-bottom.svg" height="20" width="20" /> ' . __( 'Bottom', 'wpzabb' )
						),
						'toggle'        => array(
							'top'             => array(),
							'left'            => array(
								'fields'     => array( 'item_image_size' )
							),
							'right'           => array(
								'fields'     => array( 'item_image_size' )
							),
							'bottom'          => array()
						),
						'preview'       => array(
							'type'            => 'callback',
							'callback'        => 'setAlignmentClass'
						)
					),
					'item_image_size'        => array(
						'type'          => 'unit',
						'label'         => __( 'Size', 'wpzabb' ),
						'description'   => __( 'Percent of menu item width', 'wpzabb' ),
						'default'       => '20',
						'units'         => array( '%' ),
						'default_unit'  => '%',
						'responsive'    => true,
						'slider'        => array(
							'min'             => 0,
							'max'             => 100,
							'step'            => 1
						),
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-food-menu-wrap .wpzabb-food-menu-items .wpzabb-food-menu-item .wpzabb-food-menu-item-image',
							'property'        => 'flex-basis',
							'unit'            => '%'
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
						'slider'        => array(
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
FLBuilder::register_settings_form( 'slides_form', array(
	'title' => __( 'Add Slide', 'wpzabb' ),
	'tabs'  => array(
		'general' => array( // Tab
			'title'    => __( 'General', 'wpzabb' ), // Tab title
			'sections' => array( // Tab Sections
				'general' => array( // Section
					'title'  => '', // Section Title
					'fields' => array( // Section Fields
						'title'       => array(
							'type'          => 'text',
							'label'         => __( 'Title', 'wpzabb' ),
							'placeholder'   => __( 'Slide title...', 'wpzabb' ),
							'help'          => __( 'The title of the slide. <em>(Optional)</em>', 'wpzabb' ),
							'default'       => '',
							'connections'   => array( 'string', 'html' )
						),
						'link'        => array(
							'type'          => 'link',
							'label'         => __( 'Title Link', 'wpzabb' ),
							'placeholder'   => __( 'e.g. http://www.wpzoom.com', 'wpzabb' ),
							'help'          => __( 'The URL that the slide title links to. <em>(Optional)</em>', 'wpzabb' ),
							'preview'       => array( 'type' => 'none' ),
							'connections'   => array( 'url' ),
							'show_target'   => true,
							'show_nofollow'	=> true
						),
						'content'     => array(
							'type'          => 'editor',
							'label'         => __( 'Content', 'wpzabb' ),
							'placeholder'   => __( 'Slide content...', 'wpzabb' ),
							'help'          => __( 'The text content displayed below the slide title. <em>(Optional)</em>', 'wpzabb' ),
							'default'       => '',
							'rows'          => 4,
							'media_buttons' => false,
							'connections'   => array( 'string', 'html' ),
							'responsive'    => array(
								'default'         => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => ''
								)
							)
						),
						'button'            => array(
							'type'          => 'button-group',
							'label'         => __( 'Button', 'wpzabb' ),
							'help'          => __( 'Whether to show a clickable button on this slide.', 'wpzabb' ),
							'default'       => 'no',
							'options'       => array(
								'yes'             => __( 'Yes', 'wpzabb' ),
								'no'              => __( 'No', 'wpzabb' )
							),
							'toggle'        => array(
								'yes'             => array(
									//'sections' => array( 'style_button' ),
									'fields'   => array( 'button_label', 'button_url' )
								),
								'no'              => array()
							),
							'responsive'    => array(
								'default'         => array(
									'default'    => 'no',
									'medium'     => 'no',
									'responsive' => 'no'
								)
							)
						),
						'button_label'      => array(
							'type'          => 'text',
							'label'         => __( 'Button Label', 'wpzabb' ),
							'help'          => __( 'The label of the clickable button on this slide.', 'wpzabb' ),
							'default'       => __( 'Read More', 'wpzabb' ),
							'responsive'    => array(
								'default'         => array(
									'default'    => __( 'Read More', 'wpzabb' ),
									'medium'     => __( 'Read More', 'wpzabb' ),
									'responsive' => __( 'Read More', 'wpzabb' )
								)
							)
						),
						'button_url'        => array(
							'type'          => 'link',
							'label'         => __( 'Button URL', 'wpzabb' ),
							'help'          => __( 'The URL the clickable button on this slide points to.', 'wpzabb' ),
							'placeholder'   => __( 'e.g. http://www.wpzoom.com', 'wpzabb' ),
							'preview'       => array( 'type' => 'none' ),
							'connections'   => array( 'url' ),
							'show_target'   => true,
							'show_nofollow'	=> true
						),
						'image_source' => array(
							'type'          => 'select',
							'label'         => __( 'Image', 'wpzabb' ),
							'help'          => __( 'The image shown in the background of the slide. <em>(Optional)</em>', 'wpzabb' ),
							'default'       => 'library',
							'options'       => array(
								'none'        => __( 'None', 'wpzabb' ),
								'library'     => __( 'From Media Library', 'wpzabb' ),
								'url'         => __( 'From URL', 'wpzabb' )
							),
							'toggle'        => array(
								'library'     => array(
									'fields' => array( 'image' )
								),
								'url'         => array(
									'fields' => array( 'image_url' )
								)
							),
							'responsive'    => array(
								'default'         => array(
									'default'    => 'library',
									'medium'     => 'library',
									'responsive' => 'library'
								)
							)
						),
						'image'       => array(
							'type'          => 'photo',
							'label'         => ' ',
							'show_remove'	=> true,
							'connections'   => array( 'photo' ),
							'responsive'    => true
						),
						'image_url'   => array(
							'type'          => 'link',
							'label'         => ' ',
							'placeholder'   => 'e.g. http://www.example.com/my-image.jpg',
							'preview'       => array( 'type' => 'none' ),
							'connections'	=> array( 'url' ),
							'show_target'   => true,
							'show_nofollow'	=> true,
							'responsive'    => true
						),
						'video_source' => array(
							'type'          => 'select',
							'label'         => __( 'Video', 'wpzabb' ),
							'help'          => __( 'The video shown in the background of the slide. <em>(Optional)</em>', 'wpzabb' ),
							'default'       => 'none',
							'options'       => array(
								'none'        => __( 'None', 'wpzabb' ),
								'library'     => __( 'From Media Library', 'wpzabb' ),
								'url'         => __( 'From URL', 'wpzabb' )
							),
							'toggle'        => array(
								'none'        => array(
									'fields' => array()
								),
								'library'     => array(
									'fields' => array( 'video', 'playpause', 'muteunmute', 'startmuted', 'autoplay', 'loop' )
								),
								'url'         => array(
									'fields' => array( 'video_url', 'playpause', 'muteunmute', 'startmuted', 'autoplay', 'loop' )
								)
							),
							'responsive'    => array(
								'default'         => array(
									'default'    => 'none',
									'medium'     => 'none',
									'responsive' => 'none'
								)
							)
						),
						'video'       => array(
							'type'          => 'video',
							'label'         => ' ',
							'show_remove'	=> true,
							'connections'   => array( 'video' ),
							'responsive'    => true
						),
						'video_url'   => array(
							'type'          => 'link',
							'label'         => ' ',
							'placeholder'   => 'e.g. https://youtu.be/0Yr1ezjBKuo',
							'preview'       => array( 'type' => 'none' ),
							'connections'	=> array( 'url' ),
							'show_target'   => false,
							'show_nofollow'	=> false,
							'responsive'    => true
						),
						'playpause'   => array(
							'type'          => 'button-group',
							'label'         => __( 'Video Play/Pause Buttons', 'wpzabb' ),
							'help'          => __( 'Whether to show the Play and Pause buttons that control the video playback on this slide.', 'wpzabb' ),
							'default'       => 'yes',
							'responsive'    => array(
								'default'         => array(
									'default'    => 'yes',
									'medium'     => 'yes',
									'responsive' => 'yes'
								)
							),
							'options'       => array(
								'yes'             => __( 'Yes', 'wpzabb' ),
								'no'              => __( 'No', 'wpzabb' )
							)
						),
						'muteunmute'   => array(
							'type'          => 'button-group',
							'label'         => __( 'Video Mute/Unmute Buttons', 'wpzabb' ),
							'help'          => __( 'Whether to show the Mute and Unmute buttons that control the audio in the video on this slide.', 'wpzabb' ),
							'default'       => 'yes',
							'responsive'    => array(
								'default'         => array(
									'default'    => 'yes',
									'medium'     => 'yes',
									'responsive' => 'yes'
								)
							),
							'options'       => array(
								'yes'             => __( 'Yes', 'wpzabb' ),
								'no'              => __( 'No', 'wpzabb' )
							)
						),
						'startmuted'   => array(
							'type'          => 'button-group',
							'label'         => __( 'Start Video Muted', 'wpzabb' ),
							'help'          => __( 'Whether the video should start already muted.<br/> <em>This is recommended as some browsers may block autoplay in videos with sound enabled, especially on mobile devices.</em>', 'wpzabb' ),
							'default'       => 'yes',
							'responsive'    => array(
								'default'         => array(
									'default'    => 'yes',
									'medium'     => 'yes',
									'responsive' => 'yes'
								)
							),
							'options'       => array(
								'yes'             => __( 'Yes', 'wpzabb' ),
								'no'              => __( 'No', 'wpzabb' )
							)
						),
						'autoplay'   => array(
							'type'          => 'button-group',
							'label'         => __( 'Auto-Play Video', 'wpzabb' ),
							'help'          => __( 'Whether the video playback should start immediately when the page loads without user interaction.', 'wpzabb' ),
							'default'       => 'yes',
							'responsive'    => array(
								'default'         => array(
									'default'    => 'yes',
									'medium'     => 'yes',
									'responsive' => 'yes'
								)
							),
							'options'       => array(
								'yes'             => __( 'Yes', 'wpzabb' ),
								'no'              => __( 'No', 'wpzabb' )
							)
						),
						'loop'   => array(
							'type'          => 'button-group',
							'label'         => __( 'Loop Video', 'wpzabb' ),
							'help'          => __( 'Whether the video should loop infinitely.', 'wpzabb' ),
							'default'       => 'yes',
							'responsive'    => array(
								'default'         => array(
									'default'    => 'yes',
									'medium'     => 'yes',
									'responsive' => 'yes'
								)
							),
							'options'       => array(
								'yes'             => __( 'Yes', 'wpzabb' ),
								'no'              => __( 'No', 'wpzabb' )
							)
						)
					)
				)
			)
		)
	)
) );