$(document).ready(function () {
	
	$(".alert").addClass('in');
	
	var content_div = $('#content');
	var subnavi_div = $('#subnavi');
	var mainnav_div = $('#navi');
	
	content_div_height = content_div.height();
	subnavi_div_height = subnavi_div.height();
	mainnav_div_height = mainnav_div.height();
	
	var max_height = $('body').height();
	
	if(content_div_height > max_height)
		max_height =  content_div_height;
	
	if(subnavi_div_height > max_height)
		max_height =  subnavi_div_height;
	
	if(mainnav_div_height > max_height)
		max_height = mainnav_div_height;
		
	
	content_div.height(max_height-60); // 60px wegen PaddingTop/Bottom
	subnavi_div.height(max_height);
	mainnav_div.height(max_height);
	
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
