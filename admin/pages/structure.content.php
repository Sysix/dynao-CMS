<div class="clearfix"></div>
<?php

$module_options = '';
foreach(module::getModuleList() as $module) {

	$module_options .= '<option value="'.$module['id'].'">'.$module['name'].'</option>';
	
}

if(ajax::is()) {
	
	$sort = type::post('array', 'array');
	$sql = new sql();
	$sql->setTable('structure_block');
	foreach($sort as $s=>$s_id) {
		$sql->setWhere('id='.$s_id);
		$sql->addPost('sort', $s+1);
		$sql->update();
	}
	
	ajax::addReturn(message::success('Sortierung erfolgreich übernommen', true));
	
	
}
$module = new module();

if($action == 'delete') {

	$module->delete($id);
	echo message::success('Erfolgreich gelöscht');
	
}


?>
<ul id="structure-content">
<?php

$sql = new sql();
$sql->result('SELECT s.*, m.name, m.output, m.input
FROM structure_block as s
LEFT JOIN 
	module as m
		ON m.id = s.modul
WHERE structure_id = '.$parent_id.'
ORDER BY `sort`');
while($sql->isNext()) {

	if(($action == 'add' && type::super('sort', 'int') == $sql->get('sort')) || ($action == 'edit' && $id == $sql->get('id'))) {
		
		$form_id = ($action == 'add') ? type::super('modul', 'int') : $sql->get('modul');
		$m_id = ($action == 'add') ? false : $sql->get('id');
		
		$form = $module->setFormBlock($m_id, $form_id, $parent_id);
	
	}
?>
		<li data-id="<?php echo $sql->get('id'); ?>">
<?php
	// Wenn Aktion == add
	// UND Wenn Sortierung von SQL gleich der $_GET['sort']
	// UND
	// Wenn Formular noch nicht abgeschickt worden
	// ODER Abgeschickt worden ist und ein Übernehmen geklickt worden ist
	if($action == 'add' && type::super('sort', 'int') == $sql->get('sort') && (!$form->isSubmit() || ($form->isSubmit() && type::post('save-back', 'string', false) !== false))) {
		
		echo $module->setFormBlockout($form);

	} else {
		
		echo $module->setSelectBlock($parent_id, $module_options);
		
	}
	
	// Wenn Aktion == edit
	// UND Wenn ID von SQL gleich der $_GET['id']
	// UND
	// Wenn Formular noch nicht abgeschickt worden
	// ODER Abgeschickt worden ist und ein Übernehmen geklickt worden ist
	
	if($action == 'edit' && $id == $sql->get('id') && (!$form->isSubmit() || ($form->isSubmit() && type::post('save-back', 'string', false) !== false))) {

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
			<?php echo $module->OutputFilter($sql->get('output'), $sql); ?>
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
if((!$sql->num() || ($sql->num()+1 == type::super('sort', 'int'))) && $action == 'add') {
	
	$form_id = type::super('modul', 'int');
	
	$form = $module->setFormBlock(false, $form_id, $parent_id);
	echo $module->setFormBlockout($form);
	
} else {
	
	echo $module->setSelectBlock($parent_id, $module_options, $sql->num()+1);
	
}
?>