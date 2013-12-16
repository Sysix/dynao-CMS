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
						
						$field = $form->addRawField('<h4>Datenbank</h4>');
						
						$field = $form->addTextField('db_host', $DB['host']);
						$field->fieldName(lang::get('db_host'));
						
						$field = $form->addTextField('db_user', $DB['user']);
						$field->fieldName(lang::get('db_user'));
						
						$field = $form->addTextField('db_password', $DB['password']);
						$field->fieldName(lang::get('db_password'));
						
						$field = $form->addTextField('db_database', $DB['database']);
						$field->fieldName(lang::get('db_database'));
						
						$field = $form->addRawField('<h4>Admin</h4>');
						
						$field = $form->addTextField('email', '');
						$field->fieldName(lang::get('email'));
						
						$field = $form->addTextField('password', '');
						$field->fieldName(lang::get('password'));
						
						if($form->isSubmit()) {
							
							$sql = sql::connect($form->get('db_host'), $form->get('db_user'), $form->get('db_password'), $form->get('db_database'));
							
							if($sql) {
								
								$DB = [
									'host' => $form->get('db_host'),
									'user' => $form->get('db_user'),
									'password' => $form->get('db_password'),
									'database' => $form->get('db_database')
									];
								
								dyn::add('DB', json_encode($DB), true);
								dyn::save();
								
								install::installSql();
								
								$user = sql::factory();
								$user->query('INSERT INTO `user` 
								(`id`, 	`email`, 					`password`, 								`admin`) VALUES
								(1, 	"'.type::post('email').'", 	"'.userLogin::hash(type::post('password')).'", 	1);');
								
								$form->addParam('page', 'finish');
							} else
								echo message::danger('sql error');
							
							$form->delParam('subpage');
							$form->delParam('success_msg');
						
						}
						
						echo $form->show();
					
					?>
                
                </div>
        
        </div>
    
    </div>
    
</div>