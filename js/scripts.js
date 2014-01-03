;(function($) {

  Drupal.behaviors.mobile_menu = {
    attach: function (context) {

      $(context).find('.region-navigation > .block-menu-block .expanded').each(function() {
        var $this = $(this);
        var $menu = $this.children('.menu').first();

        $menu.hide(0, function() {
          $this.addClass('closed');
          $menu.css('display', '');
          $menu.css('overflow', '');
        });

        $this.children('.expand-trigger').first().click(function(event) {
          if ($this.hasClass('closed')) {
            $menu.hide(0, function() {
              $this.removeClass('closed');
              $menu.slideToggle(300, function() {
                $menu.css('display', '');
                $menu.css('overflow', '');
              });
            });
          } else {
            $menu.slideToggle(300, function() {
              $this.addClass('closed');
              $menu.css('display', '');
              $menu.css('overflow', '');
            });
          }
          event.preventDefault();
        });
      });

      $(context).find('.region-navigation > .block-menu-block').each(function() {
        var $this = $(this);
        var $content = $this.children('.content').first();

        $content.hide(0, function() {
          $this.addClass('closed');
          $content.css('display', '');
          $content.css('overflow', '');
        });

        $this.children('.menu-title').click(function() {
          if ($this.hasClass('closed')) {
            $content.hide(0, function() {
              $this.removeClass('closed');
              $content.slideToggle(300, function() {
                $content.css('display', '');
                $content.css('overflow', '');
              });
            });
          } else {
            $content.slideToggle(300, function() {
              $this.addClass('closed');
              $content.css('display', '');
              $content.css('overflow', '');
            });
          }
        });
      });
    }
  };

})(jQuery);