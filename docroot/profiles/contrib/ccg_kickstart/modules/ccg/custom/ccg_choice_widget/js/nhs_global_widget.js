(function($, Drupal) {

    Drupal.behaviors.nhsGlobalChoicesWidget = {
        attach: function (context, settings) {
            $('#nhs-global-choices-widget', context).fsNHSWidget([1, 2, 3, 4]);
        }
    };
})(jQuery, Drupal);
