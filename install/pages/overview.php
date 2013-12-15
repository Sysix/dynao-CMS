<div class="row">

    <div class="col-lg-6">
    
        <div class="panel panel-default">
        
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo lang::get('general'); ?></h3>
                </div>
                <div class="panel-body">
                
                    <?php
						
						$form = form_install::factory('', '', 'index.php');
						
						$field = $form->addTextField('name', $form->get('name'));
						$field->fieldName('Seitenname');
						
						$field = $form->addTextField('url', $form->get('url'));
						$field->fieldName('URL der Seite');
						
						echo $form->show();
					
					?>
                
                </div>
        
        </div>
    
    </div>
	
    <div class="col-lg-6">
    
        <div class="panel panel-default">
        
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo lang::get('general'); ?></h3>
                </div>
                <div class="panel-body">
                
                    <?php var_dump(install::checkChmod(dir::cache())); ?>
                
                </div>
        
        </div>
    
    </div>
    
</div>