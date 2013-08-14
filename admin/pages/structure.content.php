<?php
layout::addJsCode('$("#structure-content").sortable({handle: ".panel-heading"})');


$module_options = '';
foreach(module::getModuleList() as $module) {

	$module_options .= '<option value="'.$module['id'].'">'.$module['name'].'</option>';
	
}
?>
<div class="clearfix"></div>
<ul id="structure-content">
<?php

if($action == 'add') {
	?>
	
	<form action="index.php" method="post">
	
		<?php
		
		$sql = new sql();
		$sql->result('SELECT * FROM module WHERE id='.type::get('modul', 'int'));
		echo $sql->get('input');
		?>
	
		<input type="hidden" name="page" value="<?php echo $page; ?>" />
		<input type="hidden" name="subpage" value="<?php echo $subpage; ?>" />
		<input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
		<input type="hidden" name="modul_id" value="<?php echo type::get('modul', 'int'); ?>" />
		<input type="hidden" name="sort" value="<?php echo type::get('pos', 'int'); ?>" />
		
		<button type="submit" class="btn btn-small btn-default">Speichern</button>
		
	</form>
	<?php
	
}

$sql = new sql();
$sql->result('SELECT * FROM structure_block WHERE structure_id = '.$parent_id);
while($sql->isNext()) {
	?>
	<li>
		<div class="structure-addmodul-box">
			<form action="index.php" method="get">
			<input type="hidden" name="page" value="structure" />
			<input type="hidden" name="subpage" value="content" />
			<input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
			<input type="hidden" name="action" value="add" />
			<input type="hidden" name="pos" value="<?php $sql->get('sort'); ?>" />			
			<select name="modul" class="form-control" onchange="this.form.submit()">
				<option>Modul hinzufügen</option>
				<?php echo $module_options;	?>
			</select>
			</form>
		</div>
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
	<?php
	$sql->next();	
}
?>	
</ul>
<div class="structure-addmodul-box">
<form action="index.php" method="get">
			<input type="hidden" name="page" value="structure" />
			<input type="hidden" name="subpage" value="content" />
			<input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
			<input type="hidden" name="action" value="add" />
			<input type="hidden" name="pos" value="1" />			
			<select name="modul" class="form-control" onchange="this.form.submit()">
				<option>Modul hinzufügen</option>
				<?php echo $module_options;	?>
			</select>
			</form>
</div>