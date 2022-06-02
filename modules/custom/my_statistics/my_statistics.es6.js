/**
 * @file
 * Statistics functionality.
 */

(function($, Drupal, drupalSettings) {
  $(document).ready(() => {
    $.ajax({
      type: 'POST',
      cache: false,
      url: drupalSettings.my_statistics.url,
      data: drupalSettings.my_statistics.data,
    });
  });
})(jQuery, Drupal, drupalSettings);
