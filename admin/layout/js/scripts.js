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
			handle: '.icon-sort',
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
	
	$("#mobil").click(function() {
		$("#subnavi ul.subnav").toggleClass("display");	
		$("#subnavi").toggleClass("round");	
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
	
	$('.dyn-link .dyn-link-add').click(function() {
	
		var _this = $(this);
		_this.closest('.dyn-link').addClass('dyn-link-active');
		
		$("body").append('<div class="modal fade" id="selectLink" tabindex="-1" role="dialog" aria-labelledby="selectLinkLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="selectLinkLabel">Artikel</h4></div><div class="modal-body"></div></div></div></div>');
		
		$(".modal-body").load('index.php?page=structure&subpage=popup #content .row .panel-body, #content .row #load');
		
		$('#selectLink').modal('show');
			
	});
	
	$('.dny-link .dyn-link-del').click(function() {	
		var div = $(this).closest('.dyn-link').find('input').val('');
	});
	
	$(document.body).on("click", '.dyn-link-select', function() {
	
		var _this = $(this),
			name = _this.data('name'),
			id = _this.data('id')
			input_wrap = $('.dyn-link-active').closest('.dyn-link');
		
		input_wrap.find('input[type=hidden]:first').val(id);
		input_wrap.find('input[type=text]:first').val(name);
		input_wrap.removeClass('dyn-media-active');
		
		$('#selectLink').modal('hide');
		
	});
	
	$(document.body).on('hidden.bs.modal','.modal',function(){
		$(this).remove();
	});

	
});
