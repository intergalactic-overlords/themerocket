;(function($) {

  Drupal.behaviors.mobile_menu = {
    attach: function (context) {

      $(context).find('.region-navigation > .block-menu-block').each(function() {
        var $this = $(this);
        $this.children('h2').click(function() {
          $this.toggleClass('open');
        });
      });
    }
  };

})(jQuery);