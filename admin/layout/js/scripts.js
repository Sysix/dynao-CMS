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

$.dynField = function(options) {

    var settings = $.extend({
        list: false,
        url: '',
        wrap: '',
        add: '',
        del: '',
        top: '',
        up: '',
        down: '',
        bottom: '',
        selectButton: '',
    }, options);
    var _wrap = $(settings.wrap),
        _add = $(settings.add),
        _del = $(settings.del);
    if(settings.list) {
        var _top = $(settings.top);
        _up = $(settings.up);
        _down = $(settings.down);
        _bottom = $(settings.bottom);
    }
    var wrap_id = settings.wrap.replace(/\./g, "");
    _add.click(function() {
        var _this = $(this);
        _this.closest(settings.wrap).attr('id', wrap_id+'-active');
        openModal();
        $('#'+wrap_id+'-select').find('.modal-body').load(settings.url);
        $('#'+wrap_id+'-select').modal('show');
    });
    _del.click(function() {
        if(settings.list) {
            var selectForm = $(this).closest(settings.wrap).children('select'),
                index = selectForm[0].selectedIndex;
            selectForm.children('option').eq(index).remove().end().eq(index-1).attr('selected', 'selected');
        } else {
            console.log('del');
            $(this).closest(settings.wrap).find('input').attr('value', '');
        }
    });
    $(document.body).on("click", settings.selectButton, function() {
        var _this = $(this),
            name = _this.data('name'),
            id = _this.data('id')
        input_wrap = $('#'+wrap_id+'-active');
        _this.button('loading');
        setTimeout(function() {
            _this.button('reset');
        }, 300);
        if(settings.list) {
            input_wrap.find('select').append('<option value="'+id+'">"'+name+'"</option>');
        } else {
            input_wrap.find('input[type=hidden]:first').attr('value', id);
            input_wrap.find('input[type=text]:first').attr('value', name);
            $('#'+wrap_id+'-select').modal('hide');
        }
    });
    $(document.body).on('hidden.bs.modal', '#'+wrap_id+'-select', function () {
        $('#'+wrap_id+'-active').removeAttr('id');
        $(this).remove();
    })
    if(settings.list) {
        _up.click(function() {
            var selectForm = $(this).closest(settings.wrap).children('select'),
                index = selectForm[0].selectedIndex,
                options = selectForm.children('option');
            if(index < 1) {
                return $(this);
            }
            var option = options.eq(index);
            options.eq(index - 1).before('<option value="'+option.val()+'" selected="selected">'+option.text()+'</option>');
            option.remove();
        });
        _down.click(function() {
            var selectForm = $(this).closest(settings.wrap).children('select'),
                index = selectForm[0].selectedIndex,
                options = selectForm.children('option');
            if(index == options.size()) {
                return $(this);
            }
            var option = options.eq(index);
            options.eq(index + 1).after('<option value="'+option.val()+'" selected="selected">'+option.text()+'</option>');
            option.remove();
        });
        _top.click(function() {
            var selectForm = $(this).closest(settings.wrap).children('select'),
                index = selectForm[0].selectedIndex,
                options = selectForm.children('option');
            if(index == 0) {
                return $(this);
            }
            var option = options.eq(index);
            selectForm.prepend('<option value="'+option.val()+'" selected="selected">'+option.text()+'</option>');
            option.remove();
        });
        _bottom.click(function() {
            var selectForm = $(this).closest(settings.wrap).children('select'),
                index = selectForm[0].selectedIndex,
                options = selectForm.children('option');
            if(index == options.size()) {
                return $(this);
            }
            var option = options.eq(index);
            selectForm.append('<option value="'+option.val()+'" selected="selected">'+option.text()+'</option>');
            option.remove();
        });
        $('form').on('submit', function() {
            var selectForm = $(settings.wrap).find('select');
            if(selectForm.length) {
                selectForm.attr('multiple', 'multiple');
                selectForm.children('option').prop('selected', true);
            }
        });
    }
    function openModal() {
        $('body').append('<div class="modal fade" id="'+wrap_id+'-select"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Struktur</h4></div><div class="modal-body"></div></div></div></div>');
    }
}

function getAjaxLoad() {
	
	$('<div>').attr('id', 'loading-body').attr('hidden', true).appendTo('body').fadeIn(200);
	$('<div>').attr('id', 'loading-spin').addClass('fa fa-spin fa-spinner').attr('hidden', true).appendTo('body').fadeIn(200);
	
}

function removeAjaxLoad() {
	$('#loading-body').remove();
	$('#loading-spin').remove();	
}

$(document).ready(function () {
    var body_width = window.innerHeight;
    var _window = $(window);

    _window.resize(function() {
        body_width = window.innerHeight;
    })
	
	$('.form-back').click(function() {
		 window.history.go(-1);
	});
	
	$('.news h5 a').tooltip();
	
	$('nav').on('navClick', function(object,action) {
        var _nav = $(this);
        action = action || auto;
        console.log(action);
        if(_nav.hasClass('active') && (action == 'auto' || action == 'close')) {
            _nav.removeClass('active');
            console.log('remove')
        } else if(action == 'auto' || action == 'open') {
            _nav.addClass('active');
            console.log('add')
        }

		$("header").addClass('stay');

	});
	
	$(window).swipe({
	    swipeRight:function(event, direction, distance, duration, fingerCount) {
            if(distance > 100 && body_width < 768) {
                $('nav').trigger('navClick', ['open']);
            }
	    },
        swipeLeft:function(event, direction, distance, duration, fingerCount) {
            if(distance > 100 && body_width < 768) {
              $('nav').trigger('navClick', ['close']);
            }
        }
	});


	$('#nav-expand').click(function() {
        $('nav').trigger('navClick', ['auto']);
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

    $.dynField({
        list: false,
        url: 'index.php?page=structure&subpage=popup',
        wrap: '.dyn-link',
        add: '.dyn-link-add',
        del: '.dyn-link-del',
        selectButton: '.dyn-link-select'
    });

    $.dynField({
        list: true,
        url: 'index.php?page=structure&subpage=popup',
        wrap: '.dyn-linklist',
        add: '.dyn-linklist-add',
        del: '.dyn-linklist-del',
        top: '.dyn-linklist-top',
        up: '.dyn-linklist-up',
        down: '.dyn-linklist-down',
        bottom: 'dyn-linklist-bottom',
        selectButton: '.dyn-link-select'
    });

});
