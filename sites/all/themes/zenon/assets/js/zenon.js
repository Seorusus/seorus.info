/*! Zenon v1.1.0 - Drupal 7 Premium Theme 
* http://www.themebiotic.com/zenon
* Themebiotic; This work is licensed under a Themeforest license agreements
* Regular License & Extended License http://themeforest.net/licenses
* Copyright (c) 2014
* Modified Date 2014-12-12 */
(function(document, window, $) {
    Drupal.behaviors.PortfolioTouch = {
        attach: function(context, settings) {
            try {
                if (Modernizr.touch) {
                    $(".node-portfolio.node-teaser figure").on("click", function(e) {
                        $(this).toggleClass("cs-hover");
                        $(".node-portfolio.node-teaser figure figcaption a").removeClass("active");
                        $("a", $(this)).addClass("active");
                    });
                    $(".node-portfolio.node-teaser figure figcaption a").on("click", function(e) {
                        if (!$(this).hasClass("active")) {
                            e.preventDefault();
                            console.log("aasasd");
                        } else {
                            return true;
                        }
                    });
                }
            } catch (err) {
                console.log(err.message);
            }
        }
    };
})(document, window, jQuery);

(function($) {
    $.easing.elasout = function(x, t, b, c, d) {
        var s = 1.70158;
        var p = 0;
        var a = c;
        if (t == 0) return b;
        if ((t /= d) == 1) return b + c;
        if (!p) p = d * .3;
        if (a < Math.abs(c)) {
            a = c;
            var s = p / 4;
        } else var s = p / (2 * Math.PI) * Math.asin(c / a);
        return a * Math.pow(2, -10 * t) * Math.sin((t * d - s) * (2 * Math.PI) / p) + c + b;
    };
    Drupal.behaviors.Zenon = {
        attach: function(context, settings) {
            new WOW().init();
            Drupal.ZenonMasonryPortfolio();
            Drupal.ZenonPortfolio();
            Drupal.ZenonSmoothScroll();
            Drupal.ZenonMasonryBlog();
            Drupal.ZenonTeamBox();
        }
    };
    $(document).ready(function() {});
    $(window).load(function() {
        try {
            Grid.init();
        } catch (err) {
            console.log(err.message);
        }
    });
})(jQuery);

(function($) {
    Drupal.BootstrapCustom = function() {
        $(".alert").bind("closed", function() {
            return this.remove();
        });
        $(".alert").bind("close", function() {
            return $(this).animate({
                opacity: 0,
                marginTop: 0,
                marginBottom: 0,
                height: "toggle"
            }, "slow", function() {
                return $(this).trigger("closed");
            });
        });
    };
})(jQuery);

(function($) {
    var $event = $.event, $special, resizeTimeout;
    $special = $event.special.debouncedresize = {
        setup: function() {
            $(this).on("resize", $special.handler);
        },
        teardown: function() {
            $(this).off("resize", $special.handler);
        },
        handler: function(event, execAsap) {
            var context = this, args = arguments, dispatch = function() {
                event.type = "debouncedresize";
                $event.dispatch.apply(context, args);
            };
            if (resizeTimeout) {
                clearTimeout(resizeTimeout);
            }
            execAsap ? dispatch() : resizeTimeout = setTimeout(dispatch, $special.threshold);
        },
        threshold: 250
    };
    var BLANK = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
    $.fn.imagesLoaded = function(callback) {
        var $this = this, deferred = $.isFunction($.Deferred) ? $.Deferred() : 0, hasNotify = $.isFunction(deferred.notify), $images = $this.find("img").add($this.filter("img")), loaded = [], proper = [], broken = [];
        if ($.isPlainObject(callback)) {
            $.each(callback, function(key, value) {
                if (key === "callback") {
                    callback = value;
                } else if (deferred) {
                    deferred[key](value);
                }
            });
        }
        function doneLoading() {
            var $proper = $(proper), $broken = $(broken);
            if (deferred) {
                if (broken.length) {
                    deferred.reject($images, $proper, $broken);
                } else {
                    deferred.resolve($images);
                }
            }
            if ($.isFunction(callback)) {
                callback.call($this, $images, $proper, $broken);
            }
        }
        function imgLoaded(img, isBroken) {
            if (img.src === BLANK || $.inArray(img, loaded) !== -1) {
                return;
            }
            loaded.push(img);
            if (isBroken) {
                broken.push(img);
            } else {
                proper.push(img);
            }
            $.data(img, "imagesLoaded", {
                isBroken: isBroken,
                src: img.src
            });
            if (hasNotify) {
                deferred.notifyWith($(img), [ isBroken, $images, $(proper), $(broken) ]);
            }
            if ($images.length === loaded.length) {
                setTimeout(doneLoading);
                $images.unbind(".imagesLoaded");
            }
        }
        if (!$images.length) {
            doneLoading();
        } else {
            $images.bind("load.imagesLoaded error.imagesLoaded", function(event) {
                imgLoaded(event.target, event.type === "error");
            }).each(function(i, el) {
                var src = el.src;
                var cached = $.data(el, "imagesLoaded");
                if (cached && cached.src === src) {
                    imgLoaded(el, cached.isBroken);
                    return;
                }
                if (el.complete && el.naturalWidth !== undefined) {
                    imgLoaded(el, el.naturalWidth === 0 || el.naturalHeight === 0);
                    return;
                }
                if (el.readyState || el.complete) {
                    el.src = BLANK;
                    el.src = src;
                }
            });
        }
        return deferred ? deferred.promise($this) : $this;
    };
})(jQuery);

var Grid = function($) {
    var $selector = "#og-grid", current = -1, previewPos = -1, scrollExtra = 0, marginExpanded = 10, $window = $(window), winsize, $body = $("html, body"), transEndEventNames = {
        WebkitTransition: "webkitTransitionEnd",
        MozTransition: "transitionend",
        OTransition: "oTransitionEnd",
        msTransition: "MSTransitionEnd",
        transition: "transitionend"
    }, transEndEventName = transEndEventNames[Modernizr.prefixed("transition")], support = Modernizr.csstransitions, settings = {
        minHeight: 500,
        speed: 350,
        easing: "ease",
        showVisitButton: true
    };
    function init(config) {
        $grid = $($selector);
        $items = $grid.children("div");
        settings = $.extend(true, {}, settings, config);
        $grid.imagesLoaded(function() {
            saveItemInfo(true);
            getWinSize();
            initEvents();
        });
    }
    function addItems($newitems) {
        $items = $items.add($newitems);
        $newitems.each(function() {
            var $item = $(this);
            $item.data({
                offsetTop: $item.offset().top,
                height: $item.height()
            });
        });
        initItemsEvents($newitems);
    }
    function saveItemInfo(saveheight) {
        $items.each(function() {
            var $item = $(this);
            $item.data("offsetTop", $item.offset().top);
            if (saveheight) {
                $item.data("height", $item.height());
            }
        });
    }
    function initEvents() {
        initItemsEvents($items);
        $window.on("debouncedresize", function() {
            scrollExtra = 0;
            previewPos = -1;
            saveItemInfo();
            getWinSize();
            var preview = $.data(this, "preview");
            if (typeof preview != "undefined") {
                hidePreview();
            }
        });
    }
    function initItemsEvents($items) {
        $items.on("click", "span.og-close", function() {
            hidePreview();
            return false;
        }).children(".og-content").on("click", function(e) {
            var $item = $(this).parent();
            console.log($item);
            current === $item.index() ? hidePreview() : showPreview($item);
            return false;
        });
    }
    function getWinSize() {
        winsize = {
            width: $window.width(),
            height: $window.height()
        };
    }
    function showPreview($item) {
        var preview = $.data(this, "preview"), position = $item.data("offsetTop");
        scrollExtra = 0;
        if (typeof preview != "undefined") {
            if (previewPos !== position) {
                if (position > previewPos) {
                    scrollExtra = preview.height;
                }
                hidePreview();
            } else {
                preview.update($item);
                return false;
            }
        }
        previewPos = position;
        preview = $.data(this, "preview", new Preview($item));
        preview.open();
    }
    function hidePreview() {
        current = -1;
        var preview = $.data(this, "preview");
        preview.close();
        $.removeData(this, "preview");
    }
    function Preview($item) {
        this.$item = $item;
        this.expandedIdx = this.$item.index();
        this.create();
        this.update();
    }
    Preview.prototype = {
        create: function() {
            this.$title = $("<h3></h3>");
            this.$description = $("<p></p>");
            var detailAppends = [ this.$title, this.$description ];
            if (settings.showVisitButton === true) {
                this.$href = $('<a href="#">Details</a>');
                detailAppends.push(this.$href);
            }
            this.$details = $('<div class="og-details"></div>').append(detailAppends);
            this.$loading = $('<div class="og-loading"></div>');
            this.$fullimage = $('<div class="og-fullimg"></div>').append(this.$loading);
            this.$closePreview = $('<span class="og-close"></span>');
            this.$previewInner = $('<div class="og-expander-inner"></div>').append(this.$closePreview, this.$fullimage, this.$details);
            this.$previewEl = $('<div class="og-expander"></div>').append(this.$previewInner);
            this.$item.append(this.getEl());
            if (support) {
                this.setTransition();
            }
        },
        update: function($item) {
            if ($item) {
                this.$item = $item;
            }
            if (current !== -1) {
                var $currentItem = $items.eq(current);
                $currentItem.removeClass("og-expanded");
                this.$item.addClass("og-expanded");
                this.positionPreview();
            }
            current = this.$item.index();
            var $itemEl = this.$item.children(".og-content"), eldata = {
                href: $itemEl.attr("href"),
                largesrc: $itemEl.data("largesrc"),
                title: $itemEl.data("title"),
                description: $itemEl.data("description")
            };
            this.$title.html(eldata.title);
            this.$description.html(eldata.description);
            if (settings.showVisitButton === true) {
                this.$href.attr("href", eldata.href);
            }
            var self = this;
            if (typeof self.$largeImg != "undefined") {
                self.$largeImg.remove();
            }
            if (self.$fullimage.is(":visible")) {
                this.$loading.show();
                $("<img/>").load(function() {
                    var $img = $(this);
                    if ($img.attr("src") === self.$item.children(".og-content").data("largesrc")) {
                        self.$loading.hide();
                        self.$fullimage.find("img").remove();
                        self.$largeImg = $img.fadeIn(350);
                        self.$fullimage.append(self.$largeImg);
                    }
                }).attr("src", eldata.largesrc);
            }
        },
        open: function() {
            setTimeout($.proxy(function() {
                this.setHeights();
                this.positionPreview();
            }, this), 25);
        },
        close: function() {
            var self = this, onEndFn = function() {
                if (support) {
                    $(this).off(transEndEventName);
                }
                self.$item.removeClass("og-expanded");
                self.$previewEl.remove();
            };
            setTimeout($.proxy(function() {
                if (typeof this.$largeImg !== "undefined") {
                    this.$largeImg.fadeOut("fast");
                }
                this.$previewEl.css("height", 0);
                var $expandedItem = $items.eq(this.expandedIdx);
                $expandedItem.css("height", $expandedItem.data("height")).on(transEndEventName, onEndFn);
                if (!support) {
                    onEndFn.call();
                }
            }, this), 25);
            return false;
        },
        calcHeight: function() {
            var heightPreview = winsize.height - this.$item.data("height") - marginExpanded, itemHeight = winsize.height;
            if (heightPreview < settings.minHeight) {
                heightPreview = settings.minHeight;
                itemHeight = settings.minHeight + this.$item.data("height") + marginExpanded;
            }
            this.height = heightPreview;
            this.itemHeight = itemHeight;
        },
        setHeights: function() {
            var self = this, onEndFn = function() {
                if (support) {
                    self.$item.off(transEndEventName);
                }
                self.$item.addClass("og-expanded");
            };
            this.calcHeight();
            this.$previewEl.css("height", this.height);
            this.$item.css("height", this.itemHeight).on(transEndEventName, onEndFn);
            if (!support) {
                onEndFn.call();
            }
        },
        positionPreview: function() {
            var position = this.$item.data("offsetTop"), previewOffsetT = this.$previewEl.offset().top - scrollExtra, scrollVal = this.height + this.$item.data("height") + marginExpanded <= winsize.height ? position : this.height < winsize.height ? previewOffsetT - (winsize.height - this.height) : previewOffsetT;
            $body.animate({
                scrollTop: scrollVal
            }, settings.speed);
        },
        setTransition: function() {
            this.$previewEl.css("transition", "height " + settings.speed + "ms " + settings.easing);
            this.$item.css("transition", "height " + settings.speed + "ms " + settings.easing);
        },
        getEl: function() {
            return this.$previewEl;
        }
    };
    return {
        init: init,
        addItems: addItems
    };
}(jQuery);

(function($) {
    Drupal.behaviors.ZenonFixed = {
        attach: function(context, settings) {
            $(document).ready(function() {
                $("html").waitForImages(function() {
                    var menu = document.querySelector(".fixed-header");
                    if (!menu) {
                        return false;
                    }
                    var $menu = $(menu);
                    var origOffsetY = menu.offsetTop + 28;
                    $("body").addClass("fixed-body-margin");
                    function scroll() {
                        if ($(window).scrollTop() >= origOffsetY) {
                            $menu.addClass("header-shrink");
                        } else {
                            $menu.removeClass("header-shrink");
                        }
                    }
                    document.onscroll = scroll;
                    $().showUp(".fixed-header", {
                        upClass: "navbar-show",
                        downClass: "navbar-hide"
                    });
                });
            });
        }
    };
})(jQuery);

(function($, window, document) {
    Drupal.ZenonMasonryBlog = function() {
        try {
            var $container = $(".masonry-blog");
            if ($container.length) {
                $container.isotope({
                    layoutMode: "masonry",
                    itemSelector: ".views-row"
                });
                $(window).on("borealis-loaded", function(ev) {
                    $container.isotope("layout");
                });
            }
        } catch (err) {
            console.log(err.message);
        }
    };
})(jQuery, window, document);

(function($, window, document) {
    Drupal.random = function(range) {
        return Math.round(Math.random() * range);
    };
    Drupal.dummyData = function(instance, range) {
        var data = [];
        for (var i = 0; i < instance; i++) {
            data[i] = Drupal.random(range);
        }
        return data;
    };
    Drupal.ChartLineOptions = function() {
        "use strict";
        var options = {
            responsive: true,
            legendTemplate: '<ul class="list-inline <%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span class="badge" style="background-color:<%=datasets[i].strokeColor%>">&nbsp;</span>&nbsp; <%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>'
        };
        return options;
    };
    Drupal.ChartLine = function(selector, data, options) {
        $("#" + selector).one("inview", function(event, visible) {
            if (visible = true) {
                var width = document.getElementById(selector).parentNode.offsetWidth;
                var height = document.getElementById(selector).getAttribute("data-height");
                document.getElementById(selector).setAttribute("width", width);
                document.getElementById(selector).setAttribute("height", height);
                var ctx = document.getElementById(selector).getContext("2d");
                var myLineChart = new Chart(ctx).Line(data, options);
                document.getElementById("myChart-legend").innerHTML = myLineChart.generateLegend();
            }
        });
    };
})(jQuery, window, document);

(function($) {
    Drupal.clearSelection = function() {
        if (document.selection && document.selection.empty) {
            document.selection.empty();
        } else if (window.getSelection) {
            var sel = window.getSelection();
            sel.removeAllRanges();
        }
    };
    Drupal.behaviors.ZenonDemostration = {
        attach: function(context, settings) {
            try {
                if (settings.zenon.debug) {
                    $(".section-demostration").once("ZenonDemostration", function() {
                        $(this).on("click", function() {
                            $(this).parent(".wrapper").toggleClass("section-demostration-outline");
                            Drupal.clearSelection();
                        });
                    });
                    $(".region-demostration").once("ZenonDemostration", function() {
                        $(this).on("click", function() {
                            $(this).parent(".region").toggleClass("region-demostration-outline");
                            Drupal.clearSelection();
                        });
                    });
                }
            } catch (err) {
                console.log(err.message);
            }
        }
    };
})(jQuery);

(function($) {
    Drupal.behaviors.ZenonParallaxSection = {
        attach: function(context, settings) {
            try {
                if (!$("body").hasClass("mobile")) {
                    $(".parallax-section").once("ZenonParallaxSection", function() {
                        var xpos = $(this).data("parallax-xpos") || null;
                        var ratio = $(this).data("parallax-ratio") || null;
                        $(this).parallax(xpos, ratio);
                    });
                }
            } catch (err) {
                console.log(err.message);
            }
        }
    };
})(jQuery);

(function($, window, document) {
    Drupal.ZenonMasonryPortfolio = function() {
        try {
            var $container = $(".portfolio-masonry");
            if ($container.length) {
                $container.isotope({
                    itemSelector: "figure"
                });
                $(window).on("borealis-loaded", function(ev) {
                    $container.isotope("layout");
                });
                $("#portfolio-filter").on("click", "button", function(event) {
                    event.preventDefault();
                    var filterValue = $(this).attr("data-filter");
                    $container.isotope({
                        filter: filterValue
                    });
                    $("#portfolio-filter ul button").removeClass("active");
                    $(this).addClass("active");
                });
            }
        } catch (err) {
            console.log(err.message);
        }
        try {
            if (Modernizr.touch) {
                $(".portfolio-masonry figure").on("click", function(e) {
                    "use strict";
                    var link = $(this);
                    if (link.hasClass("cs-hover")) {
                        return true;
                    } else {
                        link.addClass("cs-hover");
                        $(".portfolio-masonry figure").not(this).removeClass("cs-hover");
                        e.preventDefault();
                        return false;
                    }
                });
            }
        } catch (err) {
            console.log(err.message);
        }
    };
    Drupal.ZenonPortfolio = function() {
        try {
            var $container = $(".view-portfolio .view-content");
            if ($container.length) {
                $container.isotope({
                    itemSelector: ".node-portfolio"
                });
                $(".borealis", $container).on("borealis-loaded", function(ev) {
                    console.log("Triggered images...");
                    $container.isotope("layout");
                });
                $("#portfolio-filter").on("click", "button", function(event) {
                    event.preventDefault();
                    var filterValue = $(this).attr("data-filter");
                    $container.isotope({
                        filter: filterValue
                    });
                    $("#portfolio-filter ul button").removeClass("active");
                    $(this).addClass("active");
                });
            }
        } catch (err) {
            console.log(err.message);
        }
    };
    Drupal.behaviors.ZenonPortfolioBlocks = {
        attach: function(context, settings) {
            var $container = $("#zenon-portfolio-blocks");
            $container.imagesLoaded(function() {
                $container.isotope({
                    itemSelector: ".views-row"
                });
            });
            if (Modernizr.touch) {
                $("#zenon-portfolio-blocks figure").on("click", function(e) {
                    "use strict";
                    var link = $(this);
                    if (link.hasClass("cs-hover")) {
                        return true;
                    } else {
                        link.addClass("cs-hover");
                        $("#zenon-portfolio-blocks figure").not(this).removeClass("hover");
                        e.preventDefault();
                        return false;
                    }
                });
            }
        }
    };
})(jQuery, window, document);

(function($) {
    Drupal.behaviors.ZenonProgress = {
        attach: function(context, settings) {
            try {
                $(".progress .progress-bar").one("inview", function(event, visible) {
                    if (visible = true) {
                        $(this).progressbar({
                            transition_delay: 250,
                            use_percentage: false,
                            update: function(current_percentage, $this) {
                                $this.closest("section").find(".output-data").html(current_percentage);
                            }
                        });
                    }
                });
            } catch (err) {
                console.log(err.message);
            }
        }
    };
})(jQuery);

(function($) {
    Drupal.ZenonSmoothScroll = function() {
        var $ss = $(".ss");
        $ss.on("click", function(event) {
            event.preventDefault();
            var $offset = "";
            if ($("body").hasClass("admin-menu")) {
                $offset = -29;
            } else if ($("body").hasClass("toolbar") && $("body").hasClass("toolbar-drawer")) {
                $offset = -64;
            } else if ($("body").hasClass("toolbar")) {
                $offset = -30;
            } else {
                $offset = 0;
            }
            $.scrollTo($(this).data("href"), 800, {
                offset: $offset,
                easing: "easeInOutQuad"
            });
        });
    };
})(jQuery);

(function($) {
    Drupal.behaviors.SuperSlides = {
        attach: function(context, settings) {
            try {
                $("#slides").superslides({
                    animation_speed: "normal",
                    animation: "fade"
                });
                if ($("body").hasClass("mobile")) {
                    $("nav.slides-navigation").hide();
                    $(".smooth-scroll").hide();
                }
            } catch (err) {
                console.log(err.message);
            }
        }
    };
})(jQuery);

(function($) {
    Drupal.behaviors.ZenonSVG = {
        attach: function(context, settings) {
            try {
                if (Modernizr.svg && settings.zenon.logo_svg_path) {
                    var logoSVGpath = settings.zenon.logo_svg_path;
                    $("#logo img").attr("src", Drupal.settings.basePath + logoSVGpath);
                }
            } catch (err) {
                console.log(err.message);
            }
        }
    };
})(jQuery);

(function($) {
    Drupal.behaviors.ZenonTaggd = {
        attach: function(context, settings) {
            var defaults = {};
            var options = $.extend({}, defaults, settings.ZenonTaggd);
            try {
                var default_handlers = {
                    click: function(e) {
                        $("#" + e.data.id).trigger("click");
                    },
                    mouseenter: "show",
                    mouseleave: "hide"
                };
                if (!$.isEmptyObject(options)) {
                    $.each(options, function(index, value) {
                        $.each(value, function(i, e) {
                            if (!e.settings.handlers) {
                                e.settings.handlers = default_handlers;
                            }
                            $element = $(e.selector);
                            $element.taggd(e.settings);
                            $element.taggd("items", e.data);
                        });
                    });
                }
            } catch (err) {
                console.log(err.message);
            }
            $(window).load(function() {
                $(window).trigger("resize");
                if ($("body").hasClass("safari")) {}
            });
        }
    };
})(jQuery);

(function($) {
    Drupal.ZenonTeamBox = function() {
        "use strict";
        try {
            if (Modernizr.touch) {
                $(".team-box figure").on("click", function(e) {
                    var box = $(this);
                    if (box.hasClass("cs-hover")) {
                        box.removeClass("cs-hover");
                    } else {
                        box.addClass("cs-hover");
                        $(".team-box figure").not(this).removeClass("cs-hover");
                        e.preventDefault();
                        return false;
                    }
                });
            }
        } catch (err) {
            console.log(err.message);
        }
    };
})(jQuery);