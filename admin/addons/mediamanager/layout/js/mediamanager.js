$('.dyn-media-add, .dyn-medialist-add').on('click', function() {
	var _this = $(this);
		_this.closest('.dyn-media, .dyn-medialist').attr('id', 'dyn-media-active');
	
	
	
	$("body").append('<div class="modal fade" id="selectMedia" tabindex="-1" role="dialog" aria-labelledby="selectMediaLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="selectMediaLabel">Media</h4></div><div class="modal-body"></div></div></div></div>');
	
	$(".modal-body").load('index.php?page=media&subpage=popup #content .row .panel-body, #load');
	
	$('#selectMedia').modal('show');
		
});

$('.dny-media-del').click(function() {	
	$(this).closest('.dyn-media').find('input, select').val('');
});

$('.dyn-medialist-del').on('click', function() {
	
	var selectForm = $(this).closest('.dyn-medialist').children('select'),
		index = selectForm[0].selectedIndex;
		
	selectForm.children('option').eq(index).remove().end().eq(index-1).attr('selected', 'selected');
	
});
	

$('.dyn-medialist-up').on('click', function() {
	
	var selectForm = $(this).closest('.dyn-medialist').children('select'),
		index = selectForm[0].selectedIndex,
		options = selectForm.children('option');
		
	if(index  < 1) {
		return $(this);	
	}	
	
	var option = options.eq(index);
	
	options.eq(index - 1).before('<option value="'+option.val()+'" selected="selected">'+option.text()+'</option>');
	option.remove();
});

$('.dyn-medialist-down').on('click', function() {
	
	var selectForm = $(this).closest('.dyn-medialist').children('select'),
		index = selectForm[0].selectedIndex,
		options = selectForm.children('option');
		
	if(index  == options.size()) {
		return $(this);	
	}		
		
	var option = options.eq(index);
	
	options.eq(index + 1).after('<option value="'+option.val()+'" selected="selected">'+option.text()+'</option>');
	option.remove();
});

$('.dyn-medialist-bottom').on('click', function() {
	
	var selectForm = $(this).closest('.dyn-medialist').children('select'),
		index = selectForm[0].selectedIndex,
		options = selectForm.children('option');
		
	if(index  == options.size()) {
		return $(this);	
	}		
		
	var option = options.eq(index);
	
	selectForm.append('<option value="'+option.val()+'" selected="selected">'+option.text()+'</option>');
	option.remove();
});

$('.dyn-medialist-top').on('click', function() {
	
	var selectForm = $(this).closest('.dyn-medialist').children('select'),
		index = selectForm[0].selectedIndex,
		options = selectForm.children('option');
		
	if(index  == 0) {
		return $(this);	
	}		
		
	var option = options.eq(index);
	
	selectForm.prepend('<option value="'+option.val()+'" selected="selected">'+option.text()+'</option>');
	option.remove();
});

$('form').on('submit', function() {
	var selectForm = $('.dyn-medialist').find('select');
	if(selectForm.length) {
		selectForm.attr('multiple', 'multiple');
		selectForm.children('option').prop('selected', true);
	}
});

$('#media-select-category').change(function() {
	$(this).closest('form').submit();  
});

$(document.body).on('change', '#media-select-category-ajax', function() {
	$('#load').load('index.php?page=media&subpage=popup&catId='+$(this).val()+' #load');
});

$(document.body).on("click", '.dyn-media-select', function() {
	
	var _this = $(this),
		name = _this.data('name'),
		id = _this.data('id')
		input_wrap = $('#dyn-media-active'),
		tr = _this.closest('tr');
	
	_this.button('loading');
	var interval = setInterval(function() {
		_this.button('reset');
		// Sich selbst auflösen, da Button mehrmals geklickt werden kann
		clearInterval(interval);
	}, 300);
	
	if(input_wrap.children('select').length == 1) {
		
		input_wrap.find('select').append('<option value="'+id+'">"'+name+'"</option>');		
		
	} else {
		
		input_wrap.find('input[type=hidden]:first').val(id);
		input_wrap.find('input[type=text]:first').val(name);
		
		$('#selectMedia').modal('hide');
	}
	
});

$(document).on('hidden.bs.modal', '#selectMedia', function () {
	$('#dyn-media-active').removeAttr('id');
	$(this).remove();	
})
