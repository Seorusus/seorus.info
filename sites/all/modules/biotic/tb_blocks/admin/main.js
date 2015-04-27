window.BlockSettings = {
    Models: {},
    Collections: {},
    Views: {},
    Routers: {},
    init: function() {
        "use strict";
        var a = jQuery("form#block-admin-configure input[name=tb_blocks]").val() || !1,
            b = "";
        a && (b = JSON.parse(unescape(a)), this.setting.set(b));
        var c = new this.Views.Layout({
            el: jQuery("#tb-blocks-settings"),
            model: this.setting
        });
        c.render(), this.block_admin_configure()
    },
    block_admin_configure: function() {
        var a = this;
        jQuery("form").submit(function() {
            var b = escape(JSON.stringify(a.setting));
            jQuery("form input[name=tb_blocks]").val(b)
        })
    }
},
function(a) {
    a(window).ready(function() {
        "use strict";
        BlockSettings.init()
    })
}(jQuery), this.JST = this.JST || {}, this.JST["app/scripts/templates/layout.hbs"] = Handlebars.template(function(a, b, c, d, e) {
    this.compilerInfo = [4, ">= 1.0.0"], c = this.merge(c, a.helpers), e = e || {};
    var f = "";
    return f += '<div id="message"></div>\n\n<!-- Nav tabs -->\n<ul class="nav nav-tabs centered-tabs">\n  <li class="active"><a href="#grid" data-toggle="tab">Grid</a></li>\n  <li><a href="#animation" data-toggle="tab">Animation</a></li>\n  <li><a href="#extra" data-toggle="tab">Extra</a></li>\n</ul>\n\n\n<!-- Tab panes -->\n<div class="tab-content">\n\n    <div class="tab-pane active" id="grid">\n\n        <!-- Select Basic -->\n        <div class="form-group">\n          <label class="col-md-4 control-label" for="colxs">Mobile</label>\n          <div class="col-md-6">\n            <select id="colxs" name="colxs" class="form-control">\n              <option value="" >Disable</option>\n              <option value="1">1 Col</option>\n              <option value="2">2 Cols</option>\n              <option value="3">3 Cols</option>\n              <option value="4">4 Cols</option>\n              <option value="5">5 Cols</option>\n              <option value="6">6 Cols</option>\n              <option value="7">7 Cols</option>\n              <option value="8">8 Cols</option>\n              <option value="9">9 Cols</option>\n              <option value="10">10 Cols</option>\n              <option value="11">11 Cols</option>\n              <option value="12">12 Cols</option>\n            </select>\n            <p class="help-block"> <span class="fa fa-mobile"></span> Extra small devices Phones (<768px)</p>\n          </div>\n        </div>\n\n        <!-- Select Basic -->\n        <div class="form-group">\n          <label class="col-md-4 control-label" for="colsm">Tablet</label>\n          <div class="col-md-6">\n            <select id="colsm" name="colsm" class="form-control">\n              <option value="">Disable</option>\n              <option value="1">1 Col</option>\n              <option value="2">2 Cols</option>\n              <option value="3">3 Cols</option>\n              <option value="4">4 Cols</option>\n              <option value="5">5 Cols</option>\n              <option value="6">6 Cols</option>\n              <option value="7">7 Cols</option>\n              <option value="8">8 Cols</option>\n              <option value="9">9 Cols</option>\n              <option value="10">10 Cols</option>\n              <option value="11">11 Cols</option>\n              <option value="12">12 Cols</option>\n            </select>\n            <p class="help-block"> <span class="fa fa-tablet"></span> Small devices Tablets (>768px)</p>\n          </div>\n        </div>\n\n        <!-- Select Basic -->\n        <div class="form-group">\n          <label class="col-md-4 control-label" for="colmd">  Laptop</label>\n          <div class="col-md-6">\n            <select id="colmd" name="colmd" class="form-control">\n              <option value="">Disable</option>\n              <option value="1">1 Col</option>\n              <option value="2">2 Cols</option>\n              <option value="3">3 Cols</option>\n              <option value="4">4 Cols</option>\n              <option value="5">5 Cols</option>\n              <option value="6">6 Cols</option>\n              <option value="7">7 Cols</option>\n              <option value="8">8 Cols</option>\n              <option value="9">9 Cols</option>\n              <option value="10">10 Cols</option>\n              <option value="11">11 Cols</option>\n              <option value="12">12 Cols</option>\n            </select>\n            <p class="help-block"> <span class="fa fa-laptop"></span> Medium devices Desktops (>992px)</p>\n          </div>\n        </div>\n\n        <!-- Select Basic -->\n        <div class="form-group">\n          <label class="col-md-4 control-label" for="collg">Desktop</label>\n          <div class="col-md-6">\n            <select id="collg" name="collg" class="form-control">\n              <option value="">Disable</option>\n              <option value="1">1 Col</option>\n              <option value="2">2 Cols</option>\n              <option value="3">3 Cols</option>\n              <option value="4">4 Cols</option>\n              <option value="5">5 Cols</option>\n              <option value="6">6 Cols</option>\n              <option value="7">7 Cols</option>\n              <option value="8">8 Cols</option>\n              <option value="9">9 Cols</option>\n              <option value="10">10 Cols</option>\n              <option value="11">11 Cols</option>\n              <option value="12">12 Cols</option>\n            </select>\n            <p class="help-block"> <span class="fa fa-desktop"></span> Large devices Desktops (>1200px)</p>\n          </div>\n        </div>\n\n    </div> \n\n\n    <div class="tab-pane" id="animation">\n\n        <!-- Select Basic -->\n        <div class="form-group">\n          <label class="col-md-4 control-label" for="animation">Animation</label>\n          <div class="col-md-6">\n            <select id="animation" name="animation" class="form-control">\n                <option value=""></option>\n\n                <optgroup label="Attention Seekers">\n                  <option value="bounce">bounce</option>\n                  <option value="flash">flash</option>\n                  <option value="pulse">pulse</option>\n                  <option value="rubberBand">rubberBand</option>\n                  <option value="shake">shake</option>\n                  <option value="swing">swing</option>\n                  <option value="tada">tada</option>\n                  <option value="wobble">wobble</option>\n                </optgroup>\n\n                <optgroup label="Bouncing Entrances">\n                  <option value="bounceIn">bounceIn</option>\n                  <option value="bounceInDown">bounceInDown</option>\n                  <option value="bounceInLeft">bounceInLeft</option>\n                  <option value="bounceInRight">bounceInRight</option>\n                  <option value="bounceInUp">bounceInUp</option>\n                </optgroup>\n\n                <optgroup label="Fading Entrances">\n                  <option value="fadeIn" >fadeIn</option>\n                  <option value="fadeInDown" >fadeInDown</option>\n                  <option value="fadeInDownBig" >fadeInDownBig</option>\n                  <option value="fadeInLeft" >fadeInLeft</option>\n                  <option value="fadeInLeftBig" >fadeInLeftBig</option>\n                  <option value="fadeInRight" >fadeInRight</option>\n                  <option value="fadeInRightBig" >fadeInRightBig</option>\n                  <option value="fadeInUp" >fadeInUp</option>\n                  <option value="fadeInUpBig" >fadeInUpBig</option>\n                </optgroup>\n\n\n                <optgroup label="Flippers">\n                  <option value="flip" >flip</option>\n                  <option value="flipInX" >flipInX</option>\n                  <option value="flipInY" >flipInY</option>\n                </optgroup>\n\n                <optgroup label="Lightspeed">\n                  <option value="lightSpeedIn" >lightSpeedIn</option>\n                </optgroup>\n\n                <optgroup label="Rotating Entrances">\n                  <option value="rotateIn" >rotateIn</option>\n                  <option value="rotateInDownLeft" >rotateInDownLeft</option>\n                  <option value="rotateInDownRight" >rotateInDownRight</option>\n                  <option value="rotateInUpLeft" >rotateInUpLeft</option>\n                  <option value="rotateInUpRight" >rotateInUpRight</option>\n                </optgroup>\n\n                <optgroup label="Sliders">\n                  <option value="slideInDown" >slideInDown</option>\n                  <option value="slideInLeft" >slideInLeft</option>\n                  <option value="slideInRight" >slideInRight</option>\n                </optgroup>\n\n                <optgroup label="Specials">\n                  <option value="hinge" >hinge</option>\n                  <option value="rollIn" >rollIn</option>\n                </optgroup>\n\n            </select>\n          </div>\n        </div>\n\n\n        <!-- Appended Input-->\n        <div class="form-group">\n          <label class="col-md-4 control-label" for="delay">Animation delay</label>\n          <div class="col-md-6">\n            <div class="input-group">\n              <input id="delay" name="delay" class="form-control" placeholder="e.g 250" type="text" value="">\n              <span class="input-group-addon">ms</span>\n            </div>\n            <p class="help-block">The Animation delay property defines when the animation will start and  the delay value is defined in milliseconds (ms).</p>\n          </div>\n        </div>\n\n\n    </div> \n\n    <div class="tab-pane" id="extra">\n\n        <!-- Text input-->\n        <div class="form-group">\n          <label class="col-md-4 control-label" for="custom_classes">Custom classes</label>\n          <div class="col-md-6">\n          <input id="custom_classes" value="" name="custom_classes" type="text" placeholder="e.g class-1 other-class" class="form-control input-md">\n            <span class="help-block">Separate multiple classes by spaces.</span>\n\n          </div>\n        </div>\n\n        <!-- Multiple Checkboxes -->\n        <div class="form-group">\n          <label class="col-md-4 control-label" for="hidden">Hide on</label>\n          <div class="col-md-4">\n          <div class="checkbox">\n            <label for="hidden-xs">\n              <input type="checkbox" name="hidden" id="hidden-xs" value="xs" >\n              Extra small devices\n            </label>\n          </div>\n          <div class="checkbox">\n            <label for="hidden-sm">\n              <input type="checkbox" name="hidden" id="hidden-sm" value="sm">\n              Small devices\n            </label>\n          </div>\n          <div class="checkbox">\n            <label for="hidden-md">\n              <input type="checkbox" name="hidden" id="hidden-md" value="md">\n              Medium devices\n            </label>\n          </div>\n          <div class="checkbox">\n            <label for="hidden-lg">\n              <input type="checkbox" name="hidden" id="hidden-lg" value="lg">\n              Large devices\n            </label>\n          </div>\n          </div>\n        </div>\n\n\n        <!-- Multiple Checkboxes -->\n        <div class="form-group">\n          <label class="col-md-4 control-label" for="visible">Visible on</label>\n          <div class="col-md-4">\n          <div class="checkbox">\n            <label for="visible-xs">\n              <input type="checkbox" name="visible" id="visible-xs" value="xs" >\n              Extra small devices\n            </label>\n          </div>\n          <div class="checkbox">\n            <label for="visible-sm">\n              <input type="checkbox" name="visible" id="visible-sm" value="sm" >\n              Small devices\n            </label>\n          </div>\n          <div class="checkbox">\n            <label for="visible-md">\n              <input type="checkbox" name="visible" id="visible-md" value="md" >\n              Medium devices\n            </label>\n          </div>\n          <div class="checkbox">\n            <label for="visible-lg">\n              <input type="checkbox" name="visible" id="visible-lg" value="lg" >\n              Large devices\n            </label>\n          </div>\n          </div>\n        </div>\n\n\n\n    </div> \n\n\n</div> \n'
}), BlockSettings.Views = BlockSettings.Views || {},
function() {
    "use strict";
    BlockSettings.Views.Layout = Backbone.View.extend({
        template: JST["app/scripts/templates/layout.hbs"],
        tagName: "div",
        events: {},
        bindings: {
            "[name=colxs]": "colxs",
            "[name=colsm]": "colsm",
            "[name=colmd]": "colmd",
            "[name=collg]": "collg",
            "[name=animation]": "animation",
            "[name=delay]": "delay",
            "[name=visible]": "visible",
            "[name=hidden]": "hidden",
            "[name=clearfix]": "clearfix",
            "[name=custom_classes]": "custom_classes"
        },
        initialize: function() {},
        render: function() {
            return this.$el.html(this.template(this.model.toJSON())), this.stickit(), this
        }
    })
}(jQuery), BlockSettings.Models = BlockSettings.Models || {},
function() {
    "use strict";
    BlockSettings.Models.Setting = Backbone.Model.extend({
        url: "",
        defaults: {
            colxs: "12",
            colsm: "12",
            colmd: "12",
            collg: "12",
            visible: "",
            hidden: "",
            animation: "",
            delay: "",
            custom_classes: ""
        }
    }), BlockSettings.setting = new BlockSettings.Models.Setting
}(jQuery);
