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
			htmlTo : '#ajax-content'
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
				$(settings.htmlTo).fadeOut(200);
				$.post('index.php?'+getString, {array: results }, function(data) {
					$(settings.htmlTo).html(data).fadeIn(200);
				});
			}
			
		});
		
	}
	
} (jQuery));

function getAjaxLoad() {
	
	$('<div>').attr('id', 'loading-body').attr('hidden', true).appendTo('body').fadeIn(200);
	$('<div>').attr('id', 'loading-spin').addClass('fa fa-spin fa-spinner').attr('hidden', true).appendTo('body').fadeIn(200);
	
}

function removeAjaxLoad() {
	$('#loading-body').remove();
	$('#loading-spin').remove();	
}

$(document).ready(function () {
	
	$('.form-back').click(function() {
		 window.history.go(-1);
	});
	
	$('.news h5 a').tooltip();
	
	$('nav li').hover(function() {
		if($('body').width() < 1270 && $('body').width() > 600) {
			var width = $(this).children('a').children('span').width() + 20;
			$(this).children('a').css('border', '1px solid #dfdfe2').stop().animate({
				right: -width,
				backgroundColor: '#fff'
			}, 400 );
		}
	}, function() {
		$(this).children('a').stop().animate({
			right: 0,
			borderWidth: 0,
			backgroundColor: 'transparent'
		}, 200);
	});
	
	$('nav').data('expand', 'false');
	var cnt = 1;
	
	$(window).swipe({
	  swipeLeft:function(event, direction, distance, duration, fingerCount) {
		$("header").addClass('stay');
		$('nav').animate({
			opacity: 1,
			width: 201
		}, 200);
		$('section').animate({
			marginRight: 201,
			marginLeft: -201
		}, 300);
		$('nav').data('expand', 'true');
	  }
	});
	
	$('#nav-expand').click(function() {
		$("header").addClass('stay');
		$('nav').animate({
			opacity: 1,
			width: 201
		}, 200);
		$('section').animate({
			marginRight: 201,
			marginLeft: -201
		}, 300);
		$('nav').data('expand', 'true');
	});
	
	$(window).swipe({
	  swipeRight:function(event, direction, distance, duration, fingerCount) {
		if($('nav').data('expand') == 'true' && cnt != 1) {
			
			$("header").removeClass('stay');
			$('nav').animate({
				opacity: 0,
				width: 0	
			}, 200);
			$('section').animate({
				marginLeft: 0,
				marginRight: 0
			}, 300);
			
			$('nav').data('expand', 'false');
			cnt = 0;
		}
		cnt++;
	  }
	});
	
	$('header, section').click(function() {
		if($('nav').data('expand') == 'true' && cnt != 1) {
			
			$("header").removeClass('stay');
			$('nav').animate({
				opacity: 0,
				width: 0	
			}, 200);
			$('section').animate({
				marginLeft: 0,
				marginRight: 0
			}, 300);
			
			$('nav').data('expand', 'false');
			cnt = 0;
		}
		cnt++;
	});
	
	$('nav .expand').click(function () {
		$(this).next('ul').slideToggle();	
	});
	
	$('#user').click(function() {
		$(this).children('ul').fadeToggle();	
	});
	
	$("header").headroom({
		"tolerance": 100	
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
	
	$('.delete').on('click', function(e) {
		e.preventDefault();
		var _this = $(this),
			title = _this.data('title');
			message = _this.data('message'),
			url = _this.attr('href');
			
		$.get('index.php', {'deleteAction': true, 'title': title, 'message': message}, function(data) {
			$('body').append(data);
			$('#delete_modal').modal('show');
		});		
		
		$(document.body).on('click', '#delete_modal .confirm', function() {
			window.location.href = url;
		});
		
		$(document.body).on('hidden.bs.modal', '#delete_modal', function () {
			$(this).remove();	
		})
		
		return false;
		
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
