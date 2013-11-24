var MediaWindow;

$('.dyn-media .dyn-media-add').click(function() {
	
	var _this = $(this);
	_this.closest('.dyn-media').addClass('dyn-media-active');
	
	$("body").append('<div class="modal fade" id="selectMedia" tabindex="-1" role="dialog" aria-labelledby="selectMediaLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="selectMediaLabel">Media</h4></div><div class="modal-body"></div></div></div></div>');
	
	$(".modal-body").load('index.php?page=media&subpage=popup #content .row .panel-body, #content .row #load');
	
	$('#selectMedia').modal('show');
		
});

$('.dny-media .dyn-media-del').click(function() {	
	var div = $(this).closest('.dyn-media').find('input').val('');
});

$('#media-select-category').change(function() { 
	$(this).closest('form').submit();  
});

$(document.body).on('change', '#media-select-category-ajax', function() {
	var catId = $(this).val();
	$('#load').load('index.php?page=media&subpage=popup&catId='+catId+' #load');
});

$(document.body).on("click", '.dyn-media-select', function() {
	
	var _this = $(this),
		name = _this.data('name'),
		id = _this.data('id')
		input_wrap = $('.dyn-media-active').closest('.dyn-media');
	
	input_wrap.find('input[type=hidden]:first').val(id);
	input_wrap.find('input[type=text]:first').val(name);
	input_wrap.removeClass('dyn-media-active');
	
	$('#selectMedia').modal('hide');
	
});
