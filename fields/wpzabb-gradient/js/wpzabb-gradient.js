(function($){

    WPZABBGradient = {

        _init: function()
        {   
            $('body').delegate( '.wpzabb-gradient-wrapper .wpzabb-gradient-direction-select', 'change', WPZABBGradient._toggleAngle);
        },
       
                /*  TOGGLE CLICK */
        _toggleAngle: function()
        { 
              var   t = $(this).closest('.wpzabb-gradient-wrapper'),
                    direction_value = $(this).val(),
                    gradient_angle = t.find('.wpzabb-gradient-angle');
                  
                  if ( direction_value == 'custom' ) {
                      gradient_angle.show();
                  }else{
                      gradient_angle.hide();
                  }
        },
    };
    
    $(function(){
        WPZABBGradient._init();
    });
    
})(jQuery);