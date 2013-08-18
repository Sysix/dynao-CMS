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
		
		$form = new form('module', 'id='.$form_id, 'index.php');
		$form->setSave(false);
		$form->setMode($action);
		$form->addRawField($form->get('input'));
		$form->addHiddenField('parent_id', $parent_id);
		$form->addHiddenField('modul', $form->get('id'));
		$form->addHiddenField('sort', type::super('sort', 'int'));
		
		if($form->isSubmit()) {			
				$module = new module();
				$module->saveBlock($form->get('id'));
				
		}
	
	}
?>
		<li>
<?php
	if($action == 'add' && type::super('sort', 'int') == $sql->get('sort')) {
		?>
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title pull-left"><?php echo $form->get('name'); ?></h3>
				<div class="clearfix"></div>
			</div>
			<div class="panel-body">				
			<?php echo $form->show(); ?>
			</div>
		</div>
		<?php
	} else {
		?>
		<div class="structure-addmodul-box">
			<form action="index.php" method="get">
			<input type="hidden" name="page" value="structure" />
			<input type="hidden" name="subpage" value="content" />
			<input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
			<input type="hidden" name="action" value="add" />		
			<select name="modul" class="form-control">
				<option>Modul hinzufügen</option>
				<?php echo $module_options;	?>
			</select>
			</form>
		</div>            
		<?php
	}
		
	if($action == 'edit' && $id == $sql->get('id')) {
		?>
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title pull-left"><?php echo $form->get('name'); ?></h3>
				<div class="clearfix"></div>
			</div>
			<div class="panel-body">				
			<?php echo $form->show(); ?>
			</div>
		</div>
		<?php
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
		
	$form = new form('module', 'id='.$form_id, 'index.php');
	$form->setSave(false);
	$form->setMode('add');
	$form->addRawField($form->get('input'));
	$form->addHiddenField('parent_id', $parent_id);
	$form->addHiddenField('modul', $form->get('id'));
	$form->addHiddenField('sort', type::super('pos', 'int'));
	
	if($form->isSubmit()) {
			$module = new module();
			$module->saveBlock($form->get('id'));			
	}
	
	?>
    <div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title pull-left"><?php echo $form->get('name'); ?></h3>
				<div class="clearfix"></div>
			</div>
			<div class="panel-body">				
			<?php echo $form->show(); ?>
			</div>
		</div>
    <?php
} else {
?>
<div class="structure-addmodul-box">
<form action="index.php" method="get">
			<input type="hidden" name="page" value="structure" />
			<input type="hidden" name="subpage" value="content" />
			<input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
			<input type="hidden" name="action" value="add" />
			<input type="hidden" name="sort" value="<?php echo $sql->num()+1; ?>" />			
			<select name="modul" class="form-control" onchange="this.form.submit()">
				<option>Modul hinzufügen</option>
				<?php echo $module_options;	?>
			</select>
			</form>
</div>
<?php 
}
?>