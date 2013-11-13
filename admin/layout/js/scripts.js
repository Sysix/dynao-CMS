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
				
				$.post('index.php', { ajaxGet : getString, array: results }, function(data) {
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
	
});
