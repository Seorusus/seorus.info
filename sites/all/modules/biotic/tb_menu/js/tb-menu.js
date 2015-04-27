(function ($) {
  Drupal.behaviors.BioticMenu = {
    attach: function(context, settings) {

    $("nav ul.tb-menu li").each(function() {
        var $this = jQuery(this),
            $win = jQuery(window);

        if ($this.offset().left + 250 > $win.width() + $win.scrollLeft() - $this.width()) {
            $this.addClass("nav-shift");
        }

    });


    if ($.browser.msie && $.browser.version.substr(0,1)<7)
    {
      $('li').has('ul').mouseover(function(){
          $(this).children('ul').css('visibility','visible');
          }).mouseout(function(){
          $(this).children('ul').css('visibility','hidden');
          })
    }


    }
  };

})(jQuery);