(function($){

    WPZABBResponsive = {

        _init: function()
        {   
            $('body').delegate( '.wpzabb-simplify-wrapper .simplify', 'click', WPZABBResponsive._toggleExapndCollapse);
            /*$('body').delegate('.wpzabb-help-tooltip', 'mouseover', WPZABBResponsive._showHelpTooltip);
            $('body').delegate('.wpzabb-help-tooltip', 'mouseout', WPZABBResponsive._hideHelpTooltip);*/
        },
       
                /*  TOGGLE CLICK */
        _toggleExapndCollapse: function()
        {
              var   t = $(this).closest('.wpzabb-simplify-wrapper'),
                    status = $(this).attr('wpzabb-toggle');
                    h_value = $(this).find('.simplify_toggle');
              switch(status) {
                case 'expand':    t.find('.simplify').attr('wpzabb-toggle', 'collapse');
                                  t.find('.wpzabb-simplify-item.optional').hide();
                                  h_value.val('collapse');
                                  break;
                case 'collapse':  t.find('.simplify').attr('wpzabb-toggle', 'expand');
                                  t.find('.wpzabb-simplify-item.optional').show();
                                  h_value.val('expand');
                                  break;
                default:          t.find('.simplify').attr('wpzabb-toggle', 'collapse');
                                  t.find('.wpzabb-simplify-item.optional').hide();
                                  h_value.val('collapse');
                                  break;
              }
        },
         
        /*_showHelpTooltip: function()
        {   
            var h = $(this).closest('.wpzabb-icon, .simplify');
            h.find('.wpzabb-tooltip').fadeIn();
        },
        
        _hideHelpTooltip: function()
        {
            var h = $(this).closest('.wpzabb-icon, .simplify');
            h.find('.wpzabb-tooltip').fadeOut();
        },*/
    };
    
    $(function(){
        WPZABBResponsive._init();
    });
    
})(jQuery);