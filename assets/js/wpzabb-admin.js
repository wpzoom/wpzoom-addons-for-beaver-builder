WPZABB_Admin = {

	init: function()
	{
		WPZABB_Admin._toggleGlobal();

		jQuery('.wpzabb-global-enable').on('click', 	WPZABB_Admin._toggleGlobal );
		jQuery('.wpzabb-module-all-cb').on('click', WPZABB_Admin._moduleAllCheckboxClicked);
		jQuery('.wpzabb-module-cb').on('click', WPZABB_Admin._moduleCheckboxClicked);
	},

	_toggleGlobal: function() {
		var checked = jQuery('.wpzabb-global-enable').is(':checked');
		if( checked ) {
			jQuery('.wpzabb-admin-fields').show();
		} else {
			jQuery('.wpzabb-admin-fields').hide();
		}		
	},

	_moduleAllCheckboxClicked: function() {
		if( jQuery(this).is(':checked') ) {
			jQuery('.wpzabb-module-cb').prop('checked', true);
		}else{
			jQuery('.wpzabb-module-cb').prop('checked', false);
		}
	},

	_moduleCheckboxClicked: function() {
		var allChecked = true;
				
		jQuery('.wpzabb-module-cb').each(function() {
			
			if( !jQuery(this).is(':checked') ) {
				allChecked = false;
			}
		});
		
		if( allChecked ) {
			jQuery('.wpzabb-module-all-cb').prop('checked', true);
		} else {
			jQuery('.wpzabb-module-all-cb').prop('checked', false);
		}
	},
}

jQuery(document).ready(function( $ ) {

	WPZABB_Admin.init();

	/**
	 * 	Reload WPZABB IconFonts
	 */
	jQuery('.wpzabb-reload-icons').on('click', function() {

		jQuery(this).find('i').addClass('wpzabb-reloading-iconfonts');

		var data = {
			'action': 'wpzabb_reload_icons'
		};

		//	Reloading IconFonts
		jQuery.post( wpzabb.ajax_url, data, function(response) {
			if( response == 'success' ) {
				console.log('Reloading: ');
				location.reload(true);
			}
		});

	});

	/**
	 * 	Colorpicker Initiate
	 */

	var colorpicker = $('.wpzabb-wp-colopicker');

	if( colorpicker.length )
	{
		colorpicker.each(function(index) {
	    	$(this).wpColorPicker();
		});
	}

	/**
	 * Checked all the templates
	 */
	var checked_all_the_templates = function( template ) {
		jQuery('#wpzabb-template-manager-form .all-wpzabb-' + template + '-templates').on('click', function() {
			if( jQuery( this ).prop('checked') ) {
		 		jQuery( this ).closest('.fl-settings-form-content').find('input:checkbox[class^="' + template + 's-"]').prop('checked', true);
		 	}
		});
	}
	checked_all_the_templates( 'section' );
	checked_all_the_templates( 'preset' );
	checked_all_the_templates( 'page' );

	/**
	 * Update template status
	 */
	var update_template_status = function( template_name ) {
		var template         = '#wpzabb-template-manager-form input:checkbox[class^="' + template_name + 's-"]';
		var template_checked = '#wpzabb-template-manager-form input:checkbox[class^="' + template_name + 's-"]:checked';
		if( ( jQuery( template ).length === jQuery( template_checked ).length ) ) {
	 		jQuery( '.all-wpzabb-' + template_name + '-templates').prop('checked', true );
		} else {
			jQuery( '.all-wpzabb-' + template_name + '-templates').prop('checked', false );
		}
	}
	update_template_status('section');
	update_template_status('preset');
	update_template_status('page');

	/**
	 * On Change update template status
	 */
	var onchange_template_status = function( template_name ) {
		var template = '#wpzabb-template-manager-form input:checkbox[class^="' + template_name + 's-"]';
		jQuery( template ).on('change', function() {
			update_template_status( template_name );
		});
	}
	onchange_template_status('section');
	onchange_template_status('preset');
	onchange_template_status('page');

});
