/* accordion menu plugin*/
;(function($, window, document, undefined) {
  var pluginName = "specialmenu";
  var defaults = {
    speed: 200,
    showDelay: 0,
    hideDelay: 0,
    singleOpen: true,
    clickEffect: true,
    indicator: 'submenu-indicator-minus',
    subMenu: 'submenuspecial',
    event: 'click touchstart' // click, touchstart
  };

  function Plugin(element, options) {
    this.element = element;
    this.settings = $.extend({}, defaults, options);
    this._defaults = defaults;
    this._name = pluginName;
    this.init();
  }
  $.extend(Plugin.prototype, {
    init: function() {
      this.openSubmenu();
      this.submenuIndicators();
      if (defaults.clickEffect) {
        this.addClickEffect();
      }
    },
    openSubmenu: function() {
      $(this.element).children("ul").find("li").bind(defaults.event, function(e) {
        e.stopPropagation();
        e.preventDefault();
        var $subMenus = $(this).children("." + defaults.subMenu);
        var $allSubMenus = $(this).find("." + defaults.subMenu);
        if ($subMenus.length > 0) {
          if ($subMenus.css("display") == "none") {
            $subMenus.slideDown(defaults.speed).siblings("a").addClass(defaults.indicator);
            if (defaults.singleOpen) {
              $(this).siblings().find("." + defaults.subMenu).slideUp(defaults.speed)
                .end().find("a").removeClass(defaults.indicator);
            }
            return false;
          } else {
            $(this).find("." + defaults.subMenu).delay(defaults.hideDelay).slideUp(defaults.speed);
          }
          if ($allSubMenus.siblings("a").hasClass(defaults.indicator)) {
            $allSubMenus.siblings("a").removeClass(defaults.indicator);
          }
        }
        window.location.href = $(this).children("a").attr("href");
      });
    },
    submenuIndicators: function() {
      if ($(this.element).find("." + defaults.subMenu).length > 0) {
        $(this.element).find("." + defaults.subMenu).siblings("a").append("<span class='submenu-indicator'>+</span>");
      }
    },
    addClickEffect: function() {
      var ink, d, x, y;
      $(this.element).find("a").bind("click touchstart", function(e) {
        $(".ink").remove();
        if ($(this).children(".ink").length === 0) {
          $(this).prepend("<span class='ink'></span>");
        }
        ink = $(this).find(".ink");
        ink.removeClass("animate-ink");
        if (!ink.height() && !ink.width()) {
          d = Math.max($(this).outerWidth(), $(this).outerHeight());
          ink.css({
            height: d,
            width: d
          });
        }
        x = e.pageX - $(this).offset().left - ink.width() / 2;
        y = e.pageY - $(this).offset().top - ink.height() / 2;
        ink.css({
          top: y + 'px',
          left: x + 'px'
        }).addClass("animate-ink");
      });
    }
  });
  $.fn[pluginName] = function(options) {
    this.each(function() {
      if (!$.data(this, "plugin_" + pluginName)) {
        $.data(this, "plugin_" + pluginName, new Plugin(this, options));
      }
    });
    return this;
  };
})(jQuery, window, document);