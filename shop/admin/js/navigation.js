$(document).ready(function() {
	
	// Menu Dropdown
	$('.main-navigation li ul').hide(); //Hide all sub menus
	$('.main-navigation li.current a').parent().find('ul').slideToggle('slow'); // Slide down the current sub menu
	$('.main-navigation li a').click(
		function () {
			$(this).parent().siblings().find('ul').slideUp('normal'); // Slide up all menus except the one clicked
			$(this).parent().find('ul').slideToggle('normal'); // Slide down the clicked sub menu
			return false;
		}
	);
	$('.main-navigation li a.no-submenu, .main-navigation li li a').click(
		function () {
			window.location.href=(this.href); // Open link instead of a sub menu
			return false;
		}
	);

	// Navigation block toggle
	$('a.nav-toggle').click(function() {
		$('a.nav-toggle span').toggleClass('icon-chevron-left icon-chevron-right');
		$('.navigation-block').animate({
			margin: 'toggle'
		}, 0);
		$('.content-block, a.nav-toggle').toggleClass('no-sidebar');
		if($('body').hasClass('fixed-layout')) {
			$('body').toggleClass('no-sidebar')
		}
	});

	
});
