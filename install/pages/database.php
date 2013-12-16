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
						
						$DB = dyn::get('DB');
						
						$field = $form->addTextField('db_host', $DB['host']);
						$field->fieldName(lang::get('db_host'));
						
						$field = $form->addTextField('db_user', $DB['user']);
						$field->fieldName(lang::get('db_user'));
						
						$field = $form->addTextField('db_password', $DB['password']);
						$field->fieldName(lang::get('db_password'));
						
						$field = $form->addTextField('db_database', $DB['database']);
						$field->fieldName(lang::get('db_database'));
						
						if($form->isSubmit()) {
							
							$DB = [
								'host' => $form->get('db_host'),
								'user' => $form->get('db_user'),
								'password' => $form->get('db_password'),
								'database' => $form->get('db_database')
								];
							
							dyn::add('DB', $DB, true);
							dyn::save();
							
							if($error)
								echo message::danger('error');
							else
								$form->addParam('page', 'finish');
							
							$form->delParam('subpage');
							$form->delParam('success_msg');
						
						}
						
						echo $form->show();
					
					?>
                
                </div>
        
        </div>
    
    </div>
    
</div>