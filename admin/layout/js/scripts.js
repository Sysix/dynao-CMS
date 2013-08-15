$(document).ready(function () {
	
	$('.form-back').click(function() {
		 window.history.go(-1);
	});
	
	var fixHelper = function(e, ui) {
        ui.children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	}
	
	$('.js-sort tbody').sortable({
		handle: ".icon-sort", 
		helper: fixHelper,
		update : function() {
			
			var trs = $(this).children('tr');
			var results = Array();
			
			for (var i=0;i<trs.length;i++){
				results[i] = $(trs[i]).data('id');
			}
			
			var getString = document.location.search.substr(1,document.location.search.length);
			
			$.post('index.php', { ajaxGet : getString, array: results }, function(data) {
				$('#content').prepend(data);
			});
		}
	}).disableSelection();
	
});
