var MediaWindow;

$('.dyn-media .dyn-media-add').click(function() {
	
	var _this = $(this);
	_this.addClass('dyn-media-active');
	
	MediaWindow = window.open('index.php?page=media&subpage=files&subaction=popup', '_mediapopup', 'width=800,height=600,scrolllbar=yes');
	MediaWindow.focus();
		
});

$('.dny-media .dyn-media-del').click(function() {	
	var div = $(this).closest('.dyn-media').find('input').val('');
});

$('#media-select-category').change(function() {	
	$(this).closest('form').submit();	
});

$('.dyn-media-select').click(function() {
	
	var _this = $(this);
	var name = _this.data('name');
	var id = _this.data('id');
	
	var input_wrap = window.opener.jQuery('.dyn-media-active');
	
	input_wrap.nextAll('input[type=hidden]:first').val(id);
	input_wrap.nextAll('input[type=text]:first').val(name);
	input_wrap.removeClass('dyn-media-active');
	
	window.close();
	
});