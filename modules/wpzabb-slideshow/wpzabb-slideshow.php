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
		$this->add_js( 'vimeo-api', 'https://player.vimeo.com/api/player.js' );
		$this->add_js( 'touch-events',  $this->url . 'js/touchevents.polyfill.js', array( 'jquery' ), '1.0' );
		$this->add_css( 'jquery-flexslider', $this->url . 'css/jquery.flexslider.css', array( 'dashicons' ), '1.0' );
		$this->add_js( 'jquery-flexslider', $this->url . 'js/jquery.flexslider.js', array( 'jquery', 'touch-events', 'vimeo-api' ), '1.0' );

		FLBuilderAJAX::add_action( 'wpzabb_slideshow_get_thumb', array( $this, 'ajax_get_thumbnail' ), array( 'post_id', 'source', 'dat', 'element_num' ) );
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
	 * @method get_video_type
	 * @param $slide {object}
	 */
	public function get_video_type( $slide ) {
		$url = trim( $slide->video_url );

		if ( 'library' == $slide->video_source ) {
			return 'html';
		} elseif ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url ) ) {
			return 'youtube';
		} elseif ( preg_match( '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $url ) ) {
			return 'vimeo';
		} else {
			return 'unknown';
		}
	}

	/**
	 * @method get_video_url
	 * @param $slide {object}
	 */
	public function get_video_url( $slide ) {
		if ( 'library' == $slide->video_source ) {
			$url = wp_get_attachment_url( $slide->video );

			return false !== $url && !empty( $url ) ? trim( $url ) : '';
		} elseif ( 'url' == $slide->video_source ) {
			$url = trim( $slide->video_url );

			return !empty( $url ) ? $url : '';
		} else {
			return '';
		}
	}

	/**
	 * @method get_video_id
	 * @param $slide {object}
	 */
	public function get_video_id( $slide ) {
		if ( 'library' == $slide->video_source ) {
			return $slide->video;
		} elseif ( 'url' == $slide->video_source ) {
			$url = trim( $slide->video_url );

			return !empty( $url ) ? $this->_try_get_id( $url ) : -1;
		} else {
			return -1;
		}
	}

	/**
	 * @method _try_get_id
	 * @param $url {string}
	 * @protected
	 */
	protected function _try_get_id( $url ) {
		if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match ) ) {
			return $match[ 1 ];
		} elseif ( preg_match( '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $url, $match ) ) {
			return $match[ 5 ];
		} else {
			return $url;
		}
	}

	/**
	 * @method get_video_embed
	 * @param $slide {object}
	 */
	public function get_video_embed( $slide ) {
		if ( 'library' == $slide->video_source ) {
			$url = wp_get_attachment_url( $slide->video );

			return false !== $url && !empty( $url ) ? '<div class="video-embed">' . do_shortcode( '[video src="' . $url . '"]' ) . '</div>' : false;
		} elseif ( 'url' == $slide->video_source ) {
			$url = trim( $slide->video_url );

			return !empty( $url ) ? $this->_try_get_embed( $url ) : false;
		} else {
			return false;
		}
	}

	/**
	 * @method _try_get_embed
	 * @param $url {string}
	 * @protected
	 */
	protected function _try_get_embed( $url ) {
		if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url ) ||
		     preg_match( '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $url ) ) {
			return '<div class="video-embed"></div>';
		} else {
			return '<iframe src="' . esc_url( $url ) . '" class="video-embed" frameborder="0"></iframe>';
		}
	}

	/**
	 * AJAX callback to return the thumbnail of a given video or image URL.
	 *
	 * @param  string       $post_id     The ID of the post this AJAX request came from.
	 * @param  string       $source      The source of the video or image (either library, url, or image).
	 * @param  string       $dat         The URL (for videos/images hosted elsewhere) or ID (for videos/images from the local media library) of the video/image.
	 * @param  string       $element_num The element number for use on the JS side.
	 * @return string|false              The URL of the thumbnail, or false othewrwise.
	 */
	public function ajax_get_thumbnail( $post_id, $source, $dat, $element_num ) {
		if ( 'library' == $source || 'library-image' == $source ) {
			$url = wp_get_attachment_url( $dat );

			return false !== $url ? array( 'type' => $source, 'url' => $url, 'element_num' => intval( $element_num ) ) : false;
		} elseif ( 'url' == $source ) {
			$url = $this->fetch_video_thumbnail( $dat );

			return false !== $url ? array( 'type' => $source, 'url' => $url, 'element_num' => intval( $element_num ) ) : false;
		} elseif ( 'url-image' == $source ) {
			return array( 'type' => $source, 'url' => $dat, 'element_num' => intval( $element_num ) );
		} else {
			return false;
		}
	}

	/**
	 * Attempts to fetch the thumbnail URL of a given video URL using oEmbed.
	 *
	 * @param  string       $url The URL of the video to fetch the thumbnail of.
	 * @return string|false      URL for the thumbnail of the given video URL, or false otherwise.
	 */
	public function fetch_video_thumbnail( $url ) {
		$url = trim( $url );

		if ( !empty( $url ) && false !== filter_var( $url, FILTER_VALIDATE_URL ) ) {
			require_once( ABSPATH . WPINC . '/class-oembed.php' );

			$oembed = _wp_oembed_get_object();

			$provider = $oembed->get_provider( $url );

			if ( $provider ) {
				$data = $oembed->fetch( $provider, $url );

				if ( $data ) {
					return isset( $data->thumbnail_url ) && !empty( $data->thumbnail_url ) ? $data->thumbnail_url : false;
				}
			}
		}

		return false;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'WPZABBSlideshowModule', array(
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
						'multiple'      => true
					)
				)
			)
		)
	),
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
						'default'       => 10000,
						'responsive'    => array(
							'default'         => array(
								'default'    => 10000,
								'medium'     => 10000,
								'responsive' => 10000
							)
						),
						'units'         => array( 'ms' ),
						'default_unit'  => 'ms',
						'slider'        => array(
							'min'             => 0,
							'max'             => 600000,
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
					'slideshow_autoheight' => array(
						'type'          => 'button-group',
						'label'         => __( 'Automatic Height', 'wpzabb' ),
						'help'          => __( 'Whether the slideshow should have an automatic height based on the browser/viewport height.', 'wpzabb' ),
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
						),
						'toggle'        => array(
							'yes'             => array(
								'fields'   => array( 'slideshow_autoheight_size', 'slideshow_autoheight_max' )
							),
							'no'              => array()
						)
					),
					'slideshow_autoheight_size' => array(
						'type'          => 'unit',
						'label'         => __( 'Automatic Height Size', 'wpzabb' ),
						'help'          => __( 'The height (in percents) relative to the browser/viewport the slideshow should maintain.', 'wpzabb' ),
						'default'       => 100,
						'responsive'    => array(
							'default'         => array(
								'default'    => 100,
								'medium'     => 100,
								'responsive' => 100
							)
						),
						'units'         => array( '%' ),
						'default_unit'  => '%',
						'slider'        => array(
							'min'             => 0,
							'max'             => 100,
							'step'            => 1
						)
					),
					'slideshow_autoheight_max' => array(
						'type'          => 'unit',
						'label'         => __( 'Automatic Height Max', 'wpzabb' ),
						'help'          => __( 'The maximum height (in pixels) the slideshow should ever be allowed to grow to.', 'wpzabb' ),
						'default'       => 800,
						'responsive'    => array(
							'default'         => array(
								'default'    => 800,
								'medium'     => 800,
								'responsive' => 800
							)
						),
						'units'         => array( 'px' ),
						'default_unit'  => 'px',
						'slider'        => array(
							'min'             => 0,
							'max'             => 5000,
							'step'            => 1
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
	'style'      => array( // Tab
		'title'    => __( 'Style', 'wpzabb' ), // Tab title
		'sections' => array( // Tab Sections
			'style_slide_background' => array( // Section
				'title'     => __( 'Slide Background', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'slide_background_color' => array(
						'type'          => 'color',
						'label'         => '',
						'default'       => '000000',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide',
							'property'        => 'background-color'
						)
					)
				)
			),
			'style_slide_overlay' => array( // Section
				'title'     => __( 'Slide Overlay', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'slide_overlay_gradient' => array(
						'type'          => 'gradient',
						'label'         => '',
						'default'       => array(
							'type'            => 'linear',
							'colors'          => array( 'rgba(0, 0, 0, 0.9)', 'rgba(0, 0, 0, 0)' ),
							'stops'           => array( 40, 100 ),
							'angle'           => 0
						),
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-image::after, .wpzabb-slideshow .wpzabb-slideshow-slide-video::after',
							'property'        => 'background-image'
						)
					)
				)
			),
			'style_slide_title'   => array( // Section
				'title'     => __( 'Slide Title', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'slide_title_font'       => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => 'Roboto',
							'font_weight'     => 700,
							'font_size'       => array(
								'length'     => '30',
								'unit'       => 'px'
							),
							'line_height'     => array(
								'length'     => '1.5',
								'unit'       => ''
							),
							'text_align'      => 'center',
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
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title'
						)
					),
					'slide_title_color'      => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => 'ffffff',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title, .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title a',
							'property'        => 'color'
						)
					),
					'slide_title_hover_color' => array(
						'type'          => 'color',
						'label'         => __( 'Hover Color', 'wpzabb' ),
						'default'       => 'cccccc',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title a:hover',
							'property'        => 'color'
						)
					)
				)
			),
			'style_slide_content'   => array( // Section
				'title'     => __( 'Slide Content', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'slide_content_font'       => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => 'Roboto',
							'font_weight'     => 400,
							'font_size'       => array(
								'length'     => '20',
								'unit'       => 'px'
							),
							'line_height'     => array(
								'length'     => '1.5',
								'unit'       => ''
							),
							'text_align'      => 'center',
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
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-content'
						)
					),
					'slide_content_color'      => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => 'ffffff',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-content',
							'property'        => 'color'
						)
					)
				)
			),
			'style_slide_button' => array( // Section
				'title'     => __( 'Slide Button', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'slide_button_background_color' => array(
						'type'          => 'color',
						'label'         => __( 'Background Color', 'wpzabb' ),
						'default'       => 'rgba(255, 255, 255, 0.3)',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a',
							'property'        => 'background-color'
						)
					),
					'slide_button_border'    => array(
						'type'          => 'border',
						'label'         => __( 'Border', 'wpzabb' ),
						'default'       => array(
							'style'           => 'solid',
							'color'           => 'ffffff',
							'width'           => array(
								'top'          => 0,
								'left'         => 0,
								'right'        => 0,
								'bottom'       => 0
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
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a'
						)
					),
					'slide_button_font'      => array(
						'type'          => 'typography',
						'label'         => __( 'Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => 'Roboto',
							'font_weight'     => 400,
							'font_size'       => array(
								'length'     => '20',
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
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a'
						)
					),
					'slide_button_color'     => array(
						'type'          => 'color',
						'label'         => __( 'Font Color', 'wpzabb' ),
						'default'       => 'ffffff',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a',
							'property'        => 'color'
						)
					),
					'slide_button_hover_background_color' => array(
						'type'          => 'color',
						'label'         => __( 'Hover Background Color', 'wpzabb' ),
						'default'       => 'rgba(255, 255, 255, 1)',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a:hover',
							'property'        => 'background-color'
						)
					),
					'slide_button_hover_border' => array(
						'type'          => 'border',
						'label'         => __( 'Hover Border', 'wpzabb' ),
						'default'       => array(
							'style'           => 'solid',
							'color'           => 'ffffff',
							'width'           => array(
								'top'          => 0,
								'left'         => 0,
								'right'        => 0,
								'bottom'       => 0
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
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a:hover'
						)
					),
					'slide_button_hover_font' => array(
						'type'          => 'typography',
						'label'         => __( 'Hover Font', 'wpzabb' ),
						'default'       => array(
							'font_family'     => 'Roboto',
							'font_weight'     => 400,
							'font_size'       => array(
								'length'     => '20',
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
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a:hover'
						)
					),
					'slide_button_hover_color' => array(
						'type'          => 'color',
						'label'         => __( 'Hover Font Color', 'wpzabb' ),
						'default'       => '000000',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a:hover',
							'property'        => 'color'
						)
					)
				)
			),
			'style_slide_navigation'   => array( // Section
				'title'     => __( 'Slide Navigation', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'slide_navigation_color'      => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => 'rgba(255, 255, 255, 0.5)',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .flex-direction-nav a, .wpzabb-slideshow .flex-direction-nav a::before',
							'property'        => 'color'
						)
					),
					'slide_navigation_hover_color' => array(
						'type'          => 'color',
						'label'         => __( 'Hover Color', 'wpzabb' ),
						'default'       => 'ffffff',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .flex-direction-nav a:hover, .wpzabb-slideshow .flex-direction-nav a:active, .wpzabb-slideshow .flex-direction-nav a:hover::before, .wpzabb-slideshow .flex-direction-nav a:active::before',
							'property'        => 'color'
						)
					)
				)
			),
			'style_slide_video_controls' => array( // Section
				'title'     => __( 'Slide Video Controls', 'wpzabb' ), // Section Title
				'collapsed' => true,
				'fields'    => array( // Section Fields
					'slide_video_controls_color' => array(
						'type'          => 'color',
						'label'         => __( 'Color', 'wpzabb' ),
						'default'       => 'rgba(255, 255, 255, 0.5)',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-video .wpzabb-slideshow-slide-video-controls a',
							'property'        => 'color'
						)
					),
					'slide_video_controls_hover_color' => array(
						'type'          => 'color',
						'label'         => __( 'Hover Color', 'wpzabb' ),
						'default'       => 'ffffff',
						'show_alpha'    => true,
						'preview'       => array(
							'type'            => 'css',
							'selector'        => '.wpzabb-slideshow .wpzabb-slideshow-slide-video .wpzabb-slideshow-slide-video-controls a:hover',
							'property'        => 'color'
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
							),
							'preview'       => array(
								'type'           => 'none'
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