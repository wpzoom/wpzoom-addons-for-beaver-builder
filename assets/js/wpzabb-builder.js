(function( $ ) {

	WPZABBGlobal = {
			/**
			 * WPZABB Init Method for global setting
			 **/
			_init: function()
			{
				WPZABBGlobal._initGlobalButton();
				WPZABBGlobal._bindButtonEvents();
			},

			_initGlobalButton: function()
			{
				/* Global Setting */
				FLBuilder.addHook( 'actions-lightbox-settings', function( e, settings ){
					
					if ( 'fl-builder-tools-actions' == settings.className ) {
						settings.buttons[ 45 ] = {
							'key': 'wpzabb-global-settings',
							'label': FLBuilderStrings.wpzabbGlobalSettings
						};
					}

				} );
			},

			_bindButtonEvents: function()
			{
				$('body').delegate('.fl-builder-wpzabb-global-settings-button', 'click', WPZABBGlobal._globalSettingsClicked);
				$('body').delegate('.fl-builder-wpzabb-global-settings .fl-builder-settings-save', 'click', WPZABBGlobal._saveGlobalSettingsClicked);
				//$('body').delegate('.fl-builder-wpzabb-global-settings .fl-builder-settings-cancel', 'click', WPZABBGlobal._cancelGlobalSettingsClicked);
			},

			_globalSettingsClicked: function(){
				FLBuilder._actionsLightbox.close();
				FLBuilder._showLightbox();
				
				FLBuilder.ajax({
					action: 'render_wpzabb_global_settings'
				}, WPZABBGlobal._globalSettingsLoaded);
			},
			_globalSettingsLoaded: function(response)
			{
				var data = JSON.parse(response);
				FLBuilder._setSettingsFormContent(data.html);
			},

			_saveGlobalSettingsClicked: function()
			{
				var form     = $(this).closest('.fl-builder-settings'),
					valid    = form.validate().form(),
					data     = form.serializeArray(),
					settings = {},
					i        = 0;
					
				if(valid) {
						 
					for( ; i < data.length; i++) {
						settings[data[i].name] = data[i].value;
					}
					
					FLBuilder.showAjaxLoader();
					FLBuilder._layoutSettingsCSSCache = null;
					
					FLBuilder.ajax({
						action: 'save_wpzabb_global_settings',
						settings: settings
					}, FLBuilder._updateLayout);
						
					FLBuilder._lightbox.close();
				}
			},

			/*_cancelLayoutSettingsClicked: function()
			{
				var form = $( '.fl-builder-settings' );
			}, */
		}

	WPZABBHelp = {
			/**
			 * WPZABB Init Method for global setting
			 **/
			_init: function()
			{
				WPZABBHelp._initHelpButton();
				WPZABBHelp._bindButtonEvents();
			},

			_initHelpButton: function()
			{
				/* Global Setting */
				FLBuilder.addHook( 'actions-lightbox-settings', function( e, settings ){
					
					if ( 'fl-builder-help-actions' == settings.className ) {
									
						settings.buttons[ 45 ] = {
							'key': 'wpzabb-knowledge-base',
							'label': FLBuilderStrings.wpzabbKnowledgeBase
						};

						settings.buttons[ 46 ] = {
							'key': 'wpzabb-contact-support',
							'label': FLBuilderStrings.wpzabbContactSupport
						};
					}
				} );
			},

			_bindButtonEvents: function()
			{
				$('body').delegate('.fl-builder-wpzabb-knowledge-base-button', 'click', WPZABBHelp._viewKnowledgeBaseClicked);
				$('body').delegate('.fl-builder-wpzabb-contact-support-button', 'click', WPZABBHelp._visitContactSupportClicked);
			},

			/* Help Functions */
			_viewKnowledgeBaseClicked: function()
			{
				FLBuilder._actionsLightbox.close();
				window.open( FLBuilderStrings.wpzabbKnowledgeBaseUrl );
			},

			_visitContactSupportClicked: function()
			{
				FLBuilder._actionsLightbox.close();
				window.open( FLBuilderStrings.wpzabbContactSupportUrl );
			},

		}

	WPZABBButton = {
		rules: {
			text: {
				required: true
			},
			link: {
				required: true
			},
			border_size: {
				number: true
			}
		},

		init: function()
		{
			var form        = $('.fl-builder-settings'),
				btn_style   = form.find('select[name=btn_style]'),
				transparent_button_options = form.find('select[name=btn_transparent_button_options]'),
				hover_attribute = form.find('select[name=hover_attribute]'),
				btn_style_opt   = form.find('select[name=btn_flat_button_options]');

			// Init validation events.
			this._btn_styleChanged();
			this.imgicon_postion();
			
			// Validation events.
			btn_style.on('change',  $.proxy( this._btn_styleChanged, this ) );
			btn_style_opt.on('change',  $.proxy( this._btn_styleChanged, this ) );
			transparent_button_options.on( 'change', $.proxy( this._btn_styleChanged, this ) );
			hover_attribute.on( 'change', $.proxy( this._btn_styleChanged, this ) );
		},

		_btn_styleChanged: function()
		{
			var form        = $('.fl-builder-settings'),
				btn_style   = form.find('select[name=btn_style]').val(),
				btn_style_opt   = form.find('select[name=btn_flat_button_options]').val(),
				hover_attribute = form.find('select[name=hover_attribute]').val(),
				transparent_button_options = form.find('select[name=btn_transparent_button_options]').val(),
				icon       = form.find('input[name=btn_icon]');
				
			icon.rules('remove');
			
			if(btn_style == 'flat' && btn_style_opt != 'none' ) {
				icon.rules('add', { required: true });
			}

            if( btn_style == 'threed' ) {
            	form.find('#fl-field-btn_threed_button_options').show();
            	form.find("#fl-field-hover_attribute").hide();
            	form.find('#fl-field-btn_bg_color th label').text('Background Color');
	            form.find('#fl-field-btn_bg_hover_color th label').text('Background Hover Color');
            	form.find("#fl-field-btn_border_size").hide();
            	form.find("#fl-field-btn_transparent_button_options").hide();
            	form.find('#fl-field-btn_flat_button_options').hide();
            } else if( btn_style == 'flat' ) {
            	form.find('#fl-field-btn_flat_button_options').show();
            	form.find("#fl-field-hover_attribute").hide();
            	form.find('#fl-field-btn_bg_color th label').text('Background Color');
	            form.find('#fl-field-btn_bg_hover_color th label').text('Background Hover Color');
            	form.find('#fl-field-btn_threed_button_options').hide();
            	form.find("#fl-field-btn_border_size").hide();
            	form.find("#fl-field-btn_transparent_button_options").hide();
            } else if( btn_style == 'transparent' ) {
            	form.find("#fl-field-btn_border_size").show();
            	form.find("#fl-field-btn_transparent_button_options").show();
            	form.find('#fl-field-btn_threed_button_options').hide();
            	form.find('#fl-field-btn_flat_button_options').hide();
            	form.find('#fl-field-btn_bg_color th label').text('Border Color');
            	if( transparent_button_options == 'none' ) {
            		form.find("#fl-field-hover_attribute").show();
            		if( hover_attribute == 'bg' ) {
	            		form.find('#fl-field-btn_bg_hover_color th label').text('Background Hover Color');
	                } else {
	            		form.find('#fl-field-btn_bg_hover_color th label').text('Border Hover Color');
	                }
            	} else {
            		form.find("#fl-field-hover_attribute").hide();
	            	form.find('#fl-field-btn_bg_hover_color th label').text('Background Hover Color');
            	}
            } else {
            	form.find("#fl-field-hover_attribute").hide();
            	form.find('#fl-field-btn_bg_color th label').text('Background Color');
	            form.find('#fl-field-btn_bg_hover_color th label').text('Background Hover Color');
            	form.find("#fl-field-btn_border_size").hide();
            	form.find("#fl-field-btn_transparent_button_options").hide();
            	form.find('#fl-field-btn_threed_button_options').hide();
            	form.find('#fl-field-btn_flat_button_options').hide();
            }

			this.imgicon_postion();
		},
		imgicon_postion: function () {
            var form        = $('.fl-builder-settings'),
                creative_button_styles     = form.find('select[name=btn_style]').val(),
                transparent_button_options = form.find().val('select[name=btn_transparent_button_options]'),
                flat_button_options     = form.find('select[name=btn_flat_button_options]').val();

                if ( creative_button_styles == 'flat' && flat_button_options != 'none' ) {
                    setTimeout(function(){
                        jQuery("#fl-field-btn_icon_position").hide();
                    },100);
                }else{
                    jQuery("#fl-field-btn_icon_position").show();
                }
        },
	}

	$(document).ready(function() {

		if( typeof wpzabb_presets != 'undefined' && wpzabb_presets.show_presets == true ){
			$('html').addClass( 'wpzabb-show-presets' );
		}

		if( typeof wpzabb_global != 'undefined' && wpzabb_global.show_global_button == true ){
			WPZABBGlobal._init();
		}

		/* Live Preview Button */
		if( $('.wpzabb-live-preview-button').length ) {
			$('html').addClass('wpzabb-html-live-preview');

			var title_width = $('.fl-builder-bar-title').outerWidth();
			$(".wpzabb-live-preview-button").css({ left: title_width });


			$(".wpzabb-live-preview-button").click(function() {   
				$('html').toggleClass('wpzabb-active-live-preview');
				var live_preview 	= $(this),
					html 			= $('html');
				live_preview.toggleClass('active');

				if ( html.hasClass('wpzabb-active-live-preview') ) {
					if ( $('.fl-builder-panel').is(':visible') ) {
						$('.fl-builder-panel').stop(true, true).animate({ right : '-350px' }, 500);
					}
					$('.fl-builder-bar').stop(true, true).slideUp(500);
					html.animate({ marginTop: '0px !important' }, 1000);
					
					FLBuilder._destroyOverlayEvents();
					//live_preview.removeClass('fa-eye').addClass('fa-eye-slash');
				} else {
					$('.fl-builder-panel').stop(true, true).animate({ right : 0 }, 500);
					$('.fl-builder-bar').stop(true, true).slideDown(500);
					
					FLBuilder._bindOverlayEvents();
					//live_preview.removeClass('fa-eye-slash').addClass('fa-eye');
				}
			});
		}
		
		WPZABBHelp._init();
	});

})( jQuery );
