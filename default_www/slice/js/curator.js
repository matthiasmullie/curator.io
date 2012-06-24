$(document).ready(function()
{
   $active = $('#mobileNavigationActive');
   $inactive = $('#mobileNavigationInactive');
   $overlay = $('#overlay');

   showMenu = function()
   {
       $active.show();
       $inactive.addClass('selected');
       $overlay.show().height($(document).height() - 44).width($(document).width()); // @todo: resize fancyness
   };
   hideMenu = function()
   {
       $active.hide();
       $inactive.removeClass('selected');
       $overlay.hide();
   };

   $('#mobileNavigationInactive a').click(function(e)
   {
       e.stopPropagation();
       if($active.is(':visible')) hideMenu();
       else showMenu();
   });

   $(document).click(function(e)
   {
       if(e.pageX < $active.offset().left || e.pageX > $active.offset().left + $active.width() || e.pageY < $active.offset().top || e.pageY > $active.offset().top + $active.height())
       {
           hideMenu();
       }
   });

var $multiTab = $('.multiTab');
var $lis = $('li', $multiTab);
$lis.width($multiTab.width() / $lis.length-1); // @todo: resize fancyness

});