jQuery(document).ready(function($) {
$(window).scroll(function (){
var iCurScrollPos = $(this).scrollTop();
if ((iCurScrollPos > 100) ){
$('header').css('background','#000');
}else{
$('header').css('background','transparent');	
}
});



$(window).load(function() {
	$('.banner__down').on('click',function(){
	$('html,body').animate({
        scrollTop: $(".content-area").offset().top - $('header').height()},
        'slow');
});
	var topval = $('h1 img').offset();
$('.overlay').removeClass('start');
$('.overlay').css('padding-top', topval.top);
$('.overlay').css('padding-left', topval.left);
setTimeout(function(){
	$('.overlay').fadeOut();
},2100)

  $('.flexslider').flexslider({
    animation: "slide"
  });



$('.works').isotope({
  itemSelector: '.work',
  percentPosition: true,
  masonry: {
    horizontalOrder: true,
    gutter: 15
  }
});

});
});