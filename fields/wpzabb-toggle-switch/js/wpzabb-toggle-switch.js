(function($){

	jQuery(document).ready(function() {

		WPZABBToggleSwitch = {

			_init : function() {
				$('body').delegate( '.wpzabb-toggle-switch .wpzabb_toggle_switch_label', 'click', WPZABBToggleSwitch._settingsSwitchChanged);

			},

			
			_settingsSwitchChanged: function() {
				var $this 		= $(this),
					switch_wrap = $this.closest(".wpzabb-toggle-switch"),
					this_attr  	= '#'+ $this.attr('for'),
					this_value  = switch_wrap.find(this_attr).val();

					switch_wrap.find(".wpzabb_toggle_switch_select").val(this_value).change();
					switch_wrap.find(".wpzabb_toggle_switch_select").trigger('change');
			},
		};

		WPZABBToggleSwitch._init();
		
	});
})(jQuery);