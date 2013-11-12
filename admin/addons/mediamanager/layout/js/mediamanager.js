var MediaWindow;

$('.dyn-media .dyn-media-add').click(function() {
	
	var _this = $(this);
	var _id = _this.next('input[type=hidden]');
	var _input = _id.next('input[type=text]');
	
	MediaWindow = window.open('index.php?page=media&subpage=popup', '_mediapopup', 'width=800,height=600,scrolllbar=yes');
	MediaWindow.focus();
		
});

$('.dny-media .dyn-media-del').click(function() {
	
	var div = $(this).closest('.dyn-media').find('input').val('');
		
});