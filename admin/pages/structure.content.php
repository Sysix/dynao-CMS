<?php
if($action == '')
	layout::addJsCode('$("#structure-content").sortable({handle: ".panel-heading"})');

$module_options = '';
foreach(module::getModuleList() as $module) {

	$module_options .= '<option value="'.$module['id'].'">'.$module['name'].'</option>';
	
}

if($action == 'delete') {

	$sql = new sql();
	$sql->setTable('structure_block');
	$sql->setWhere('id='.$id);
	$sql->delete();
	echo message::success('Erfolgreich gelöscht');
	
}

$module = new module();
?>
<div class="clearfix"></div>
<ul id="structure-content">
<?php

$sql = new sql();
$sql->result('SELECT s.*, m.name, m.output, m.input
FROM structure_block as s
LEFT JOIN 
	module as m
		ON m.id = s.modul
WHERE structure_id = '.$parent_id);
while($sql->isNext()) {

	if(($action == 'add' && type::super('sort', 'int') == $sql->get('sort')) || ($action == 'edit' && $id == $sql->get('id'))) {
		
		$form_id = ($action == 'add') ? type::super('modul', 'int') : $sql->get('modul');
		
		$form = $module->setFormBlock($parent_id, $form_id);
	
	}
?>
		<li>
<?php
	if($action == 'add' && type::super('sort', 'int') == $sql->get('sort')) {
		
		echo $module->setFormBlockout($form);

	} else {
		
		echo $module->setSelectBlock($parent_id, $module_options);
		
	}
		
	if($action == 'edit' && $id == $sql->get('id')) {

		echo $module->setFormBlockout($form);

	} else {
		?>
		<div class="panel">
		  <div class="panel-heading">
			<h3 class="panel-title pull-left"><?php echo $sql->get('name'); ?></h3>
			<div class="pull-right btn-group">
				<a class="btn btn-small structure-online">ON</a>
				<a class="btn btn-default btn-small icon-cog"></a>
				<a href="<?php echo url::backend('structure', array('subpage'=>'content', 'action'=>'edit', 'id'=>$sql->get('id'))); ?>" class="btn btn-default  btn-small icon-edit-sign"></a>
				<a href="<?php echo url::backend('structure', array('subpage'=>'content', 'action'=>'delete', 'id'=>$sql->get('id'))); ?>" class="btn btn-danger btn-small icon-trash"></a>
			</div>
			<div class="clearfix"></div>
		  </div>
		  <div class="panel-body">
			<?php echo $sql->get('output'); ?>
		  </div>
		</div>
		<?php
	}
	?>
    </li>
    <?php
	$sql->next();	
	
}
?>	
</ul>
<?php
if((!$sql->num() || $sql->num()+1 == type::super('sort', 'int')) && $action == 'add') {
	
	$form_id = type::super('modul', 'int');
	
	$module = new module();
	$form = $module->setFormBlock($parent_id, $form_id);
	
	echo $module->setFormBlockout($form);
	
} else {
	
	echo $module->setSelectBlock($parent_id, $module_options, $sql->num()+1);
	
}
?>