(function ($, Drupal) {
  /*global jQuery:false */
  /*global Drupal:false */
  "use strict";

  /**
   * Provide vertical tab summaries for bootstrap settings.
   */
  Drupal.behaviors.bioticSettingSummaries = {
    attach: function (context) {
      var $context = $(context);

      // Layout Builder.
      $context.find('#edit-layout-builder').drupalSetSummary(function () {
        var summary = [];
        summary.push( Drupal.t('Section: ') + (TS.layout.length - 1) );
        summary.push( Drupal.t('Regions: ') + regions() );
        return summary.join(', ');
      });

      /* TOTAL REGION COUNT */
      function regions() {
        var total_region = 0;
        _.each(TS.layout.models, function(model) {
          total_region += model.get('regions').length;
        });
        return total_region;
      };

      // Typo Builder
      $context.find('#edit-typo-builder').drupalSetSummary(function () {
        var summary = [];
        summary.push( Drupal.t('Active Typography: ') + Typo.typography.length );
        return summary.join(', ');
      });

      $(document).ready(function(){
        $context.find('#edit-layout-builder').trigger('summaryUpdated');
        $context.find('#edit-typo-builder').trigger('summaryUpdated');
        TS.layout.on('add remove change', function(){
          $context.find('#edit-layout-builder').trigger('summaryUpdated');
        });
        Typo.typography.on('add remove change', function(){
          $context.find('#edit-typo-builder').trigger('summaryUpdated');
        });
      });


      // Components.
      $context.find('#edit-components').drupalSetSummary(function () {
        var summary = [];
        // Breadcrumbs.
        var breadcrumb = parseInt($context.find('select[name="bootstrap_breadcrumb"]').val(), 10);
        if (breadcrumb) {
          summary.push(Drupal.t('Breadcrumbs'));
        }

        // Navbar.
        // var navbar = 'Navbar: ' + $context.find('select[name="bootstrap_navbar_position"] :selected').text();
        // if ($context.find('input[name="bootstrap_navbar_inverse"]').is(':checked')) {
        //   navbar += ' (' + Drupal.t('Inverse') + ')';
        // }
        // summary.push(navbar);

        return summary.join(', ');
      });

      // Javascript.
      $context.find('#edit-javascript').drupalSetSummary(function () {
        var summary = [];
        if ($context.find('input[name="bootstrap_anchors_fix"]').is(':checked')) {
          summary.push(Drupal.t('Anchors'));
        }
        if ($context.find('input[name="bootstrap_popover_enabled"]').is(':checked')) {
          summary.push(Drupal.t('Popovers'));
        }
        if ($context.find('input[name="bootstrap_tooltip_enabled"]').is(':checked')) {
          summary.push(Drupal.t('Tooltips'));
        }
        return summary.join(', ');
      });

      // Advanced.
      $context.find('#edit-advanced').drupalSetSummary(function () {
        var summary = [];
        // bootstrapCDN.
        // var bootstrapCDN = $context.find('select[name="bootstrap_cdn"]').val();

        // if (bootstrapCDN.length) {
        //   bootstrapCDN = 'bootstrapCDN v' + bootstrapCDN;
        //   // Bootswatch.
        //   if ($context.find('select[name="bootstrap_bootswatch"]').val().length) {
        //     bootstrapCDN += ' (' + $context.find('select[name="bootstrap_bootswatch"] :selected').text() + ')';
        //   }
        //   summary.push(bootstrapCDN);
        // }

        // Rebuild registry.
        if ($context.find('input[name="bootstrap_rebuild_registry"]').is(':checked')) {
          summary.push(Drupal.t('Rebuild Registry'));
        }

        // Debug Mode.
        if ($context.find('input[name="debug_mode"]').is(':checked')) {
          summary.push(Drupal.t('Debug Mode'));
        }

        return summary.join(', ');
      });


    }
  };

  /**
   * Provide bootstrap Bootswatch preview.
   */
  Drupal.behaviors.bioticBootswatchPreview = {
    attach: function (context) {
      var $context = $(context);
      var $preview = $context.find('#bootswatch-preview');
      $preview.once('bootswatch', function () {
        $.get("http://api.bootswatch.com/3/", function (data) {
          var themes = data.themes;
          for (var i = 0, len = themes.length; i < len; i++) {
            $('<a/>').attr({
              id: themes[i].name.toLowerCase(),
              class: 'bootswatch-preview element-invisible',
              href: themes[i].preview,
              target: '_blank'
            }).html(
              $('<img/>').attr({
                src: themes[i].thumbnail,
                alt: themes[i].name
              })
            )
            .appendTo($preview);
          }
          $preview.parent().find('select[name="bootstrap_bootswatch"]').bind('change', function () {
            $preview.find('.bootswatch-preview').addClass('element-invisible');
            if ($(this).val().length) {
              $preview.find('#' + $(this).val()).removeClass('element-invisible');
            }
          }).change();
        }, "json");
      });
    }
  };

  /**
   * Provide bootstrap navbar preview.
   */
  Drupal.behaviors.bioticNavbarPreview = {
    attach: function (context) {
      var $context = $(context);
      var $preview = $context.find('#edit-navbar');
      $preview.once('navbar', function () {
        var $body = $context.find('body');
        var $navbar = $context.find('#navbar.navbar');
        $preview.find('select[name="bootstrap_navbar_position"]').bind('change', function () {
          var $position = $(this).find(':selected').val();
          $navbar.removeClass('navbar-fixed-bottom navbar-fixed-top navbar-static-top container');
          if ($position.length) {
            $navbar.addClass('navbar-'+ $position);
          }
          else {
            $navbar.addClass('container');
          }
          // Apply appropriate classes to body.
          $body.removeClass('navbar-is-fixed-top navbar-is-fixed-bottom navbar-is-static-top');
          switch ($position) {
            case 'fixed-top':
              $body.addClass('navbar-is-fixed-top');
              break;

            case 'fixed-bottom':
              $body.addClass('navbar-is-fixed-bottom');
              break;

            case 'static-top':
              $body.addClass('navbar-is-static-top');
              break;
          }
        });
        $preview.find('input[name="bootstrap_navbar_inverse"]').bind('change', function () {
          $navbar.toggleClass('navbar-inverse navbar-default');
        });
      });
    }
  };

})(jQuery, Drupal);
