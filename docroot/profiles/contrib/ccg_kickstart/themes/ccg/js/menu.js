(function ($, Drupal) {

  'use strict';

  function hideOrShowMenu(context, settings, toggle) {
    var menu = $('.block-menu.menu--main:not(.in-this-section) div > ul.menu, .block-menu.menu--main:not(.in-this-section) > ul.menu');
    if (menu.length) {
      var mobileScreen = window.matchMedia('(max-width: 760px)');
      if (mobileScreen.matches) {
        // Make sure the menu is hidden if mobile menu toggle doesn't
        // have the active class.
        if (!toggle) {
          toggle = $('.mobile-menu-icon');
        }
        if (toggle.hasClass('active')) {
          // Ensure the menu isn't hidden when the toggle is active.
          menu.removeClass('hidden');
        } else {
          menu.addClass('hidden');
        }
      } else {
        // Where the screen is wider then the maximum breakpoint make sure
        // menu menu shows.
        menu.removeClass('hidden');
      }
    }
  }

  /**
   * Initialise the menu JS.
   */
  Drupal.behaviors.menu = {
    attach: function (context, settings) {
      hideOrShowMenu(context, settings);
      $(window).resize(function () {
        hideOrShowMenu(context, settings);
      });
      $('.mobile-menu-icon').unbind().click(function () {
        if (!$(this).hasClass('active')) {
          $(this).addClass('active');
        } else {
          $(this).removeClass('active');
        }
        hideOrShowMenu(context, settings);
      });
    }
  };

  // Ensure the home menu link li has active trail on document ready as on drupal behaviour attachment
  // it takes too long for this to kick in.
  $(function() {
    // Ensure that main menu home link li element gets menu-item--active-trail class.
    // TODO: check for core patch or fix for this issue.
    $('nav.menu--main:not(.in-this-section) ul.menu > li.menu-item').each(function() {
      if ($(this).find('a.is-active').length > 0) {
        $(this).addClass('menu-item--active-trail');
      }
    });
  });
})(jQuery, Drupal);
