(function($, window, document) {
  "use strict";
  var Plugin, defaults, pluginName;
  pluginName = "tbAnimation";
  defaults = {
    debug: false,
    property: "value"
  };
  Plugin = (function() {
    function Plugin(element, options) {
      this.element = element;
      this.settings = $.extend({}, defaults, options);
      this._defaults = defaults;
      this._name = pluginName;
      this.init();
    }

    Plugin.prototype.init = function() {
      return this.play();
    };

    Plugin.prototype.log = function(msg) {
      if (this.settings.debug) {
        return typeof console !== "undefined" && console !== null ? console.log(msg) : void 0;
      }
    };

    Plugin.prototype.play = function() {
      var $animation, $delay, $el;
      $el = $(this.element);
      $.each($el, function(value, key, list) {
        return $(key).appear();
      });
      if ($el.data("animation")) {
        $animation = $el.data("animation");
        this.log("data-animation value : " + $animation);
      }
      if ($el.data("delay")) {
        $delay = $el.data("delay");
        this.log("data-delay value : " + $delay);
      }
      return $el.on("appear", function(event, $affected) {
        var timeout;
        if ($delay) {
          return timeout = setTimeout(function() {
            $el.addClass("animated " + $animation);
            $el.removeData("delay");
            return $el.off("appear");
          }, $delay);
        } else {
          $el.addClass("animated " + $animation);
          return $el.off("appear");
        }
      });
    };

    return Plugin;

  })();
  $.fn[pluginName] = function(options) {
    return this.each(function() {
      if (!$.data(this, "plugin_" + pluginName)) {
        return $.data(this, "plugin_" + pluginName, new Plugin(this, options));
      }
    });
  };
  return $(document).ready(function() {
    return $("body:not(.page-node-edit) [data-animation]").tbAnimation({
      debug: false
    });
  });
})(jQuery, window, document);
