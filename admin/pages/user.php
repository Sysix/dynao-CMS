<?php

$action = type::super('action', 'string');
$id = type::super('id', 'int', 0);

backend::addSubnavi(lang::get('overview'),		url::backend('user'), 		'eye');

if($action == 'delete') {
	
		$sql = sql::factory();
		$sql->setTable('user');
		$sql->setWhere('id='.$id);
		$sql->delete();
		
		$action = '';
		
}

if($action == 'add' || $action == 'edit') {
	
	layout::addJsCode("
	var page_admin_button = $('#pageadmin-button');
	var page_admin_content = $('#pageadmin-content');
	
	page_admin_button.change(function() {
			if(page_admin_button.is(':checked')) {
				page_admin_content.stop().slideUp(300);
			} else {
				page_admin_content.stop().slideDown(300);
			}
	});");
	
	$form = form::factory('user','id='.$id,'index.php');
	
	$field = $form->addTextField('firstname', $form->get('firstname'));
	$field->fieldName("Vorname");
	$field->autofocus();
	
	$field = $form->addTextField('name', $form->get('name'));
	$field->fieldName("Nachname");
	
	$field = $form->addTextField('email', $form->get('email'));
	$field->fieldName(lang::get('email_adress'));
	$field->addValidator('notEmpty', lang::get('user_email_empty'));
	$field->addValidator('email', lang::get('user_wrong_email'));
	
	if($form->get('password') != $form->sql->getResult('password')) {
		$password = userLogin::hash($form->get('password'));
	} else {
		$password = $form->sql->getResult('password');
	}
	
	$field = $form->addTextField('password', $password);
	$field->addValidator('notEmpty', lang::get('user_password_empty'));
	$field->fieldName(lang::get('password'));
	$field->setSuffix(lang::get('password_info'));
	
	if(dyn::get('user')->isAdmin()) {

		$field = $form->addCheckboxField('admin', $form->get('admin'));
		$field->add('1', 'PageAdmin?', ['id'=>'pageadmin-button']);
		
		$field = $form->addSelectField('perms', $form->get('perms'));
		
		if($form->get('admin') == 1)
			$field->addAttribute('style', 'display:none;');
		
		$field->setMultiple(true);
		$field->setId('pageadmin-content');
		$field->setSize(8);
		foreach(userPerm::getAll() as $name=>$value) {
			$field->add($name, $value);	
		}
	
	}
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	if($form->isSubmit()) {
		
		if($form->get('password') != $form->sql->getResult('password')) {
			
			$form->addPost('password', userLogin::hash($form->get('password')));
			
		}
		
	}
	
	?>
	<div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">"<?php echo $form->get('firstname')." ".$form->get('name'); ?>" <?php echo lang::get('edit'); ?></h3>
                </div>
            	<div class="panel-body">
					<?php echo $form->show(); ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php
	
}

if($action == '') {

	$table = table::factory();
	
	$table->addCollsLayout('*, 250,110');
	
	$table->addRow()
	->addCell("Name")
	->addCell(lang::get('email'))
	->addCell(lang::get('action'));
	
	$table->addSection('tbody');	
	
	$table->setSql('SELECT * FROM '.sql::table('user'));
	while($table->isNext()) {
			
		$id = $table->get('id');
			
		$edit = '<a href="'.url::backend('user', ['action'=>'edit', 'id'=>$id]).'" class="btn btn-sm  btn-default fa fa-pencil-square-o"></a>';
		$delete = '<a href="'.url::backend('user', ['action'=>'delete', 'id'=>$id]).'" class="btn btn-sm btn-danger fa fa-trash-o"></a>';
		
		$table->addRow()
		->addCell($table->get('firstname')." ".$table->get('name'))
		->addCell($table->get('email'))
		->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
		
		$table->next();	
		
	}
	
	?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left"><?php echo lang::get('user'); ?></h3>
					<div class="btn-group pull-right">
						<a href="<?php echo url::backend('user', ['action'=>'add']); ?>" class="btn btn-sm btn-default"><?php echo lang::get('add'); ?></a>
					</div>
					<div class="clearfix"></div>
                </div>
                <?php echo $table->show(); ?>
            </div>
        </div>
    </div>
    
    <?php
	
}

?>