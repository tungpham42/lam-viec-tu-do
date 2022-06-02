/**
 * @file
 * nodeCounter functionality.
 */

(function($, Drupal, drupalSettings) {
  $(document).ready(() => {
    $.ajax({
      type: 'POST',
      cache: false,
      url: drupalSettings.nodeCounter.url,
      data: drupalSettings.nodeCounter.data,
    });
  });
})(jQuery, Drupal, drupalSettings);
