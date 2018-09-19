

(function ($, Drupal) {
  Drupal.behaviors.gifField = {
    attach: function (context, settings) {
      $defaultImage = $('.gif-preview').attr('src');
      $(document, context).on('mouseenter', '.ui-menu-item-wrapper', (event) => {
        const gifId = $(event.target).text().split(/\((...+)\)/)[1];
        $('.gif-preview').attr("src","https://media3.giphy.com/media/" + gifId + "/giphy-preview.gif");
      });

      $(document, context).on('click', '.ui-menu-item-wrapper', (event) => {
        const gifId = $(event.target).text().split(/\((...+)\)/)[1];
        $('.gif-preview').attr("src","https://media3.giphy.com/media/" + gifId + "/giphy.gif");
      })
    }
  };
})(jQuery, Drupal);