<?php
layout::addJsCode(' $(function() {
$( "#sortable" ).sortable();
$( "#sortable" ).disableSelection();
});');
?>
<ul id="sortable">

	<li>
		<div class="panel">
		  <div class="panel-heading">
			<h3 class="panel-title pull-left">Modul #4</h3>
			<div class="pull-right btn-group">
				<a class="btn btn-small structure-online">ON</a>
				<a class="btn btn-default btn-small icon-cog"></a>
				<a class="btn btn-default btn-small icon-edit-sign"></a>
				<a class="btn btn-danger btn-small icon-trash"></a>
			</div>
			<div class="clearfix"></div>
		  </div>
		  <div class="panel-body">
			Content
		  </div>
		</div>
	</li>
	
	<li>
		<div class="panel">
		  <div class="panel-heading">
			<h3 class="panel-title pull-left">Modul #3</h3>
			<div class="pull-right btn-group">
				<a class="btn btn-small structure-online">ON</a>
				<a class="btn btn-default btn-small icon-cog"></a>
				<a class="btn btn-default  btn-small icon-edit-sign"></a>
				<a class="btn btn-danger btn-small icon-trash"></a>
			</div>
			<div class="clearfix"></div>
		  </div>
		  <div class="panel-body">
			Content
		  </div>
		</div>
	</li>
	
</ul>