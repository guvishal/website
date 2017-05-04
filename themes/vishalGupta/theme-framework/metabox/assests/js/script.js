/**
 * Screipt for Dashboard view
 * @since breakloose 1.0
 */

var TEMPLATESELECTION = (function ($) {

  // creating an interface
  var template = {};
  template.displayCustomField = (function ($template_value) {

    switch ($template_value) {
      case 'home.php':
        $('#page_HelpCentreLink').show();
        break;
      default:
        $('#page_HelpCentreLink').hide();
        break;
    }
  })

  // returning interface
  return template;
}(jQuery))

jQuery(document).ready(function ($) {

  TEMPLATESELECTION.displayCustomField($('#page_template').val());

  $('#page_template').change(function () {
    TEMPLATESELECTION.displayCustomField($(this).val());
  });
});

jQuery(window).ready(function ($) {
//  $('.date-multiple').multiDatesPicker({
//    maxPicks: 30,
//    dateFormat: "yy-mm-dd"
//  });
  //  $('.date-picker').datepicker({
  //    dateFormat: 'd MM, yy'
  //  });

  

  if (jQuery('#postexcerpt').hasClass('postbox')) {
    jQuery('#postexcerpt').children('h3.hndle').html('<span>Things to Know tab</span>');
  }

  if (jQuery('.thumb').hasClass('column-thumb')) {
    jQuery('.thumb.column-thumb').children('img:odd').hide();
  }
});