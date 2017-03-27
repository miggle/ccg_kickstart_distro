(function ($, Drupal) {

  'use strict';

  /**
   * Provides accessibility functionality.
   */
  Drupal.behaviors.access = {
    attach: function (context, settings) {
      // Set the home page link access key.
      $(document).keydown(function(evt) {
        if (evt.which == 78 && evt.altKey) {
          $('nav.menu--main:not(.in-this-section) > ul > li:first-child a').focus();
        }
        if (evt.which == 83 && evt.altKey) {
          document.getElementById('main-content').scrollIntoView();
        }
      });
    }
  };

})(jQuery, Drupal);
