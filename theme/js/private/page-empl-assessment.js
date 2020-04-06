$(function() {
  //fixed menu in personal account
  if($('.personal-acc__menu').is('*'))
  {
    var posAccMenu = $('.personal-acc__menu').offset().top - 100;
    $(window).on('resize scroll', scrollAccMenu);
    scrollAccMenu();

    function scrollAccMenu() {
      (
        $(document).scrollTop() > posAccMenu
        &&
        $(window).width() < 768
      )
        ? $('.personal-acc__menu').addClass('fixed')
        : $('.personal-acc__menu').removeClass('fixed');
    }
  }
});