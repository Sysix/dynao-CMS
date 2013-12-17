<div class="row">

    <div class="col-lg-12">
    
        <div class="panel panel-default">
        
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo lang::get('db_connect'); ?></h3>
                </div>
                <div class="panel-body">
                
                    <?php
						
						$form = form_install::factory('', '', 'index.php');
						$form->setSave(false);
						
						$form->delButton('back');
						
						$DB = dyn::get('DB');
						
						$field = $form->addRawField('<h4>'.lang::get('db_database').'</h4>');
						
						$field = $form->addTextField('db_host', $DB['host']);
						$field->addValidator('notEmpty', lang::get('validator_not_empty'));
						$field->fieldName(lang::get('db_host'));
						
						$field = $form->addTextField('db_user', $DB['user']);
						$field->addValidator('notEmpty', lang::get('validator_not_empty'));
						$field->fieldName(lang::get('db_user'));
						
						$field = $form->addTextField('db_password', $DB['password']);
						$field->addValidator('notEmpty', lang::get('validator_not_empty'));
						$field->fieldName(lang::get('db_password'));
						
						$field = $form->addTextField('db_database', $DB['database']);
						$field->addValidator('notEmpty', lang::get('validator_not_empty'));
						$field->fieldName(lang::get('db_database'));
						
						$field = $form->addTextField('db_prefix', $DB['prefix']);
						$field->fieldName(lang::get('db_prefix'));
						
						$field = $form->addRawField('<h4>'.lang::get('user').'</h4>');
						
						$field = $form->addTextField('email', '');
						$field->fieldName(lang::get('email'));
						$field->addValidator('notEmpty', lang::get('validator_not_empty'));
						$field->addValidator('email', lang::get('user_wrong_email'));
						
						$field = $form->addTextField('password', '');
						$field->fieldName(lang::get('password'));
						
						if($form->isSubmit()) {
							
							$sql = sql::connect($form->get('db_host'), $form->get('db_user'), $form->get('db_password'), $form->get('db_database'));
							
							if(is_null($sql)) {
								
								$DB = [
									'host' => $form->get('db_host'),
									'user' => $form->get('db_user'),
									'password' => $form->get('db_password'),
									'database' => $form->get('db_database'),
									'prefix' => $form->get('db_prefix')
								];
								
								dyn::add('DB', $DB, true);
								dyn::save();
								
								install::newInstall();
								
								$form->addParam('page', 'finish');
								
							} else {
								echo message::danger($sql);
							}
							
							
							$form->delParam('success_msg');
						
						}
						
						echo $form->show();
					
					?>
                
                </div>
        
        </div>
    
    </div>
    
</div>