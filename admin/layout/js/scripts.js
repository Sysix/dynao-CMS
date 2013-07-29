$(document).ready(function () {
	
	$(".alert").addClass('in');
	
	var content_div = $('#content');
	
	if(content_div.height() < $('#subnavi').height()) {
	
		content_div.css('bottom', 'auto');
		
	}
	
	content_div.children(".span4" ).sortable({
		connectWith: ".span4",
		handle: ".panel-heading"
	}).disableSelection();
	
	$("#trash").hover(function () {
		$("#trash-text").stop().animate({"right":"-10px"}, "slow");	
	}, function () {
		$("#trash-text").stop().animate({"right":"-230px"}, "slow");
	});
});
