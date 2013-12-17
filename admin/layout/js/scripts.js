(function($) {
	
	var fixHelper = function(e, ui) {
        ui.children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	}
	
	$.fn.DynSorting = function(options) {
		
		var settings = $.extend({
			helper: true,
			children: 'tr',
			handle: '.fa-sort',
			prependTo : '#content'
		}, options);
		
		if(settings.helper == false)
			settings.helper = 'original';
			
		if(settings.helper === true)
			settings.helper = fixHelper;
			
		return $(this).sortable({
			handle: settings.handle,
			helper: settings.helper,
			update : function() {
				
				var child = $(this).children(settings.children);
				var results = Array();
				
				for (var i=0;i<child.length;i++){
					results[i] = $(child[i]).data('id');
				}
				
				var getString = document.location.search.substr(1,document.location.search.length);
				
				$.post('index.php?'+getString, {array: results }, function(data) {
					$(settings.prependTo).prepend(data);
				});
			}
			
		});
		
	}
	
} (jQuery));

$(document).ready(function () {
	
	$('.form-back').click(function() {
		 window.history.go(-1);
	});
	
	$("#addonMobile").click(function() {
		$(this).toggleClass('active');
		$("#tools ul").toggleClass("display");	
	});
	
	$('#trash, .news h5').tooltip();
	
	$("#mobil").click(function() {
		$("#subnavi ul.subnav").toggleClass("display");	
		$("#subnavi").toggleClass("round");	
	});
	
	$("#addon-mobil").click(function() {
		$("#tools ul").toggleClass("display");	
		$("#tools ul").toggleClass("round");	
	});
	
	$("#user-mobil").click(function () {
		$("#subnavi #user").toggleClass("display");
		$(this).toggleClass("active");
	});
	
	$('.js-sort tbody').DynSorting();
	$('#structure-content').DynSorting({children: 'li', handle: '.panel-heading'});
	
	$('.structure-addmodul-box select').change(function() {
		
		var form = $(this).closest('form');
		var li = form.closest('li');
		
		var pos = $('<input>').attr({
			type: 'hidden',
			name: 'sort',
			value: li.index()+1
		});
		
		if(!form.find('input[name=sort]').length)
			pos.appendTo(form);
		
		form.submit();
		
	});
	
	//
	// formLink, formLinkList
	//
	
	
	$('.dyn-link-add, .dyn-linklist-add').on('click', function() {
	var _this = $(this);
		_this.closest('.dyn-link, .dyn-linklist').attr('id', 'dyn-link-active');
	
	
	
	$("body").append('<div class="modal fade" id="selectLink" tabindex="-1" role="dialog" aria-labelledby="selectLinkLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="selectLinkLabel">Struktur</h4></div><div class="modal-body"></div></div></div></div>');
	
	$(".modal-body").load('index.php?page=structure&subpage=popup');
	
	$('#selectLink').modal('show');
		
});

$('.dyn-link-del').click(function() {	
	$(this).closest('.dyn-link').find('input').removeAttr('value');
});

$('.dyn-linklist-del').on('click', function() {
	
	var selectForm = $(this).closest('.dyn-linklist').children('select'),
		index = selectForm[0].selectedIndex;
		
	selectForm.children('option').eq(index).remove().end().eq(index-1).attr('selected', 'selected');
	
});
	

$('.dyn-linklist-up').on('click', function() {
	
	var selectForm = $(this).closest('.dyn-linklist').children('select'),
		index = selectForm[0].selectedIndex,
		options = selectForm.children('option');
		
	if(index  < 1) {
		return $(this);	
	}	
	
	var option = options.eq(index);
	
	options.eq(index - 1).before('<option value="'+option.val()+'" selected="selected">'+option.text()+'</option>');
	option.remove();
});

$('.dyn-linklist-down').on('click', function() {
	
	var selectForm = $(this).closest('.dyn-linklist').children('select'),
		index = selectForm[0].selectedIndex,
		options = selectForm.children('option');
		
	if(index  == options.size()) {
		return $(this);	
	}		
		
	var option = options.eq(index);
	
	options.eq(index + 1).after('<option value="'+option.val()+'" selected="selected">'+option.text()+'</option>');
	option.remove();
});

$('.dyn-linklist-bottom').on('click', function() {
	
	var selectForm = $(this).closest('.dyn-linklist').children('select'),
		index = selectForm[0].selectedIndex,
		options = selectForm.children('option');
		
	if(index  == options.size()) {
		return $(this);	
	}		
		
	var option = options.eq(index);
	
	selectForm.append('<option value="'+option.val()+'" selected="selected">'+option.text()+'</option>');
	option.remove();
});

$('.dyn-linklist-top').on('click', function() {
	
	var selectForm = $(this).closest('.dyn-linklist').children('select'),
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
	var selectForm = $('.dyn-linklist').find('select');
	if(selectForm.length) {
		selectForm.attr('multiple', 'multiple');
		selectForm.children('option').prop('selected', true);
	}
});

$(document.body).on("click", '.dyn-link-select', function() {
	
	var _this = $(this),
		name = _this.data('name'),
		id = _this.data('id')
		input_wrap = $('#dyn-link-active'),
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
		
		$('#selectLink').modal('hide');
	}
	
});

$(document).on('hidden.bs.modal', '#selectLink', function () {
	$('#dyn-link-active').removeAttr('id');
	$(this).remove();	
})


	
});
