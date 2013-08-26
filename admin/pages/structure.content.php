<div class="clearfix"></div>
<?php

$structure_id = type::super('structure_id', 'int', $parent_id);

$module_options = '';
foreach(module::getModuleList() as $module) {

	$module_options .= '<option value="'.$module['id'].'">'.$module['name'].'</option>';
	
}

// Bugfix, das neu erstelle Blöcke nicht einzgezeigt werden
if(type::post('action', 'string') == 'add' || type::post('action', 'string') == 'edit') {
	module::saveBlock();	
}

if($action == 'online') {

	$sql = new sql();
	$sql->query('SELECT online FROM '.sql::table('structure_block').' WHERE id='.$id)->result();
	
	$online = ($sql->get('online')) ? 0 : 1;
	
	$sql->setTable('structure_block');
	$sql->setWhere('id='.$id);
	$sql->addPost('online', $online);
	$sql->update();
	
	echo message::success('Status erfoglreich geändert');
	
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

if($action == 'delete') {

	$structure_id = module::delete($id);
	echo message::success('Erfolgreich gelöscht');
	
}


?>
<ul id="structure-content">
<?php

$sql = new sql();
$sql->result('SELECT s.*, m.name, m.output, m.input
FROM '.sql::table('structure_block').' as s
LEFT JOIN 
	'.sql::table('module').' as m
		ON m.id = s.modul
WHERE structure_id = '.$structure_id.'
ORDER BY `sort`');
$i = 1;
while($sql->isNext()) {
	
	$module = new module();

	if(($action == 'add' && type::super('sort', 'int') == $i) || ($action == 'edit' && $id == $sql->get('id'))) {
		
		$form_id = ($action == 'add') ? type::super('modul', 'int') : $sql->get('modul');
		$m_id = ($action == 'add') ? false : $sql->get('id');
		
		$form = $module->setFormBlock($m_id, $form_id, $structure_id);
	
	}
?>
		<li data-id="<?php echo $sql->get('id'); ?>">
<?php
	// Wenn Aktion == add
	// UND Wenn Sortierung von SQL gleich der $_GET['sort']
	// UND
	// Wenn Formular noch nicht abgeschickt worden
	// ODER Abgeschickt worden ist und ein Übernehmen geklickt worden ist
	if($action == 'add' && type::super('sort', 'int') == $i && (!$form->isSubmit() || ($form->isSubmit() && type::post('save-back', 'string', false) !== false))) {
		
		echo $module->setFormBlockout($form);

	} else {
		
		echo $module->setSelectBlock($structure_id, $module_options);
		
	}
	
	// Wenn Aktion == edit
	// UND Wenn ID von SQL gleich der $_GET['id']
	// UND
	// Wenn Formular noch nicht abgeschickt worden
	// ODER Abgeschickt worden ist und ein Übernehmen geklickt worden ist
	
	if($action == 'edit' && $id == $sql->get('id') && (!$form->isSubmit() || ($form->isSubmit() && type::post('save-back', 'string', false) !== false))) {

		echo $module->setFormBlockout($form);

	} else {
		
		if($sql->get('online')) {
			$statusText = 'ON';
			$status = 'online';
		} else {
			$statusText = 'OFF';
			$status = 'offline';
		}
		
		?>
		<div class="panel panel-default">
		  <div class="panel-heading">
			<h3 class="panel-title pull-left"><?php echo $sql->get('name'); ?></h3>
			<div class="pull-right btn-group">
				<a href="<?php echo url::backend('structure', array('subpage'=>'content', 'action'=>'online', 'id'=>$sql->get('id'))); ?>" class="btn btn-sm structure-<?php echo $status; ?>"><?php echo $statusText; ?></a>
				<a class="btn btn-default btn-sm icon-cog"></a>
				<a href="<?php echo url::backend('structure', array('subpage'=>'content', 'action'=>'edit', 'id'=>$sql->get('id'))); ?>" class="btn btn-default  btn-sm icon-edit-sign"></a>
				<a href="<?php echo url::backend('structure', array('subpage'=>'content', 'action'=>'delete', 'id'=>$sql->get('id'))); ?>" class="btn btn-danger btn-sm icon-trash"></a>
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
	$i++;
	
}
?>	
</ul>
<?php
$module = new module();
if((!$sql->num() || ($i == type::super('sort', 'int'))) && $action == 'add') {
	
	$form_id = type::super('modul', 'int');
	
	$form = $module->setFormBlock(0, $form_id, $structure_id);
	echo $module->setFormBlockout($form);
	
} else {
	
	echo $module->setSelectBlock($structure_id, $module_options, $sql->num()+1);
	
}
?>