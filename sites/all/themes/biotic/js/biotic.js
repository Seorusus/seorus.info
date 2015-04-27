/**
 *
 *
 * http://codepen.io/senff/pen/ayGvD
 *
 * @param  {[type]} $ [description]
 * @return {[type]}   [description]
 */
(function ($) {
  Drupal.behaviors.BioticSticky = {
    attach: function (context, settings) {
      //add your js or jquery code here

        $(document).ready( function() {

          /* Sticky Header works only Desktop */
          if ( $('body').hasClass('desktop') ) {

             $('html').waitForImages(function() {
                // All descendant images have loaded, now slide up.

                    var menu = document.querySelector('.sticky-header');

                    if (!menu) {
                      return false;
                    };

                    var origOffsetY = menu.offsetTop;
                    var top_margin = menu.offsetHeight;

                    function scroll () {
                      if ($(window).scrollTop() >= origOffsetY) {
                        $('.sticky-header').addClass('sticky');
                        document.body.style.setProperty ("padding-top", top_margin+"px", "important");

                      } else {

                          $('.sticky-header').removeClass('sticky');
                          document.body.style.setProperty ("padding-top", "", "");
                      }

                    }

                document.onscroll = scroll;

            });

          }

        });

    }
  };


  /* WebfontLoader */
  Drupal.behaviors.WebfontLoader = {
    attach: function(context, settings) {
      var defaults, options;
      defaults = {
        debug: false,
        fonts: false
      };
      options = $.extend({}, defaults, settings.webfontloader);
      return $("html", context).once("WebfontLoader", function() {
        if (options.debug) {
          console.log("WebfontLoader Loaded...");
          console.log(options);
        }
        if (options.fonts) {
          WebFont.load({
            google: {
              families: options.fonts
            }
          });
          return setTimeout(function() {
            if ($(this).hasClass("wf-loading")) {
              return $(this).removeClass("wf-loading");
            }
          }, 3000);
        }
      });
    }
  };


 })(jQuery);
