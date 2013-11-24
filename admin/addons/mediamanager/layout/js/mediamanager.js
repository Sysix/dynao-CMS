var MediaWindow;

$('.dyn-media .dyn-media-add').click(function() {
	
	var _this = $(this);
	_this.addClass('dyn-media-active');
	
	$("body").append('<div class="modal fade" id="selectMedia" tabindex="-1" role="dialog" aria-labelledby="selectMediaLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="selectMediaLabel">Media</h4></div><div class="modal-body"></div></div></div></div>');
	
	$(".modal-body").load('index.php?page=media&subpage=popup #content .row .panel-body, #content .row #load');
	
	$('#selectMedia').modal('show');
		
});

$('.dny-media .dyn-media-del').click(function() {	
	var div = $(this).closest('.dyn-media').find('input').val('');
});

$('.dyn-media-select').click(function() {
	
	var _this = $(this);
	var name = _this.data('name');
	var id = _this.data('id');
	
	var input_wrap = window.opener.jQuery('.dyn-media-active');
	
	input_wrap.nextAll('input[type=hidden]:first').val(id);
	input_wrap.nextAll('input[type=text]:first').val(name);
	input_wrap.removeClass('dyn-media-active');
	
	$('#selectMedia').modal('hide');
	
});
