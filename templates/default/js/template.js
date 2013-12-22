$(document).ready(function() {
	
	var breakPoint = 700,
		_window = $(window),
		nav = $('header ul'),
		_windowWidth = _window.width();
		 
	_window.on('resize', function() {
		_windowWidth = _window.width();	
		
		if(_windowWidth >= breakPoint) {
			nav.removeClass('dropdown-menu').show()
		}
		
	});

	$('header .btn-group button').on('click', function() {
		
		
		
		if(nav.is(':hidden')) {
			nav.addClass('dropdown-menu').show();
		} else {
			nav.removeClass('dropdown-menu').hide();	
		}
		
	});
	
});