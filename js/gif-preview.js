

(function ($, Drupal) {
  Drupal.behaviors.gifField = {
    attach: function (context, settings) {
      const resultsContainer = document.getElementById('gif_field_results');

        if (resultsContainer) {
          console.log('lajdkjasd');
        }
      
      // $defaultImage = $('.gif-preview').attr('src');
      // $(document, context).on('mouseenter', '.ui-menu-item-wrapper', (event) => {
      //   const gifId = $(event.target).text().split(/\((...+)\)/)[1];
      //   $('.gif-preview').attr("src","https://media3.giphy.com/media/" + gifId + "/giphy-preview.gif");
      // });

      // $(document, context).on('click', '.ui-menu-item-wrapper', (event) => {
      //   const gifId = $(event.target).text().split(/\((...+)\)/)[1];
      //   $('.gif-preview').attr("src","https://media3.giphy.com/media/" + gifId + "/giphy.gif");
      // })

      $('input.delayed-input-submit').each(function () {
        var $self = $(this);
        var timeout = null;
        var delay = $self.data('delay') || 1000;
        var triggerEvent = $self.data('event') || "end_typing";

        $self.unbind('keyup').keyup(function () {
          clearTimeout(timeout);
          timeout = setTimeout(function () {
            $self.trigger(triggerEvent);
          }, delay);
        });
      });
      
    }
  };
})(jQuery, Drupal);