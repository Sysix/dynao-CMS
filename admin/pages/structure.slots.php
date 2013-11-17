<?php

	$table = table::factory();
	
	$table->addCollsLayout('*,200');
	
	$table->addRow()
	->addCell('Name')
	->addCell(lang::get('module'));
	
	$table->addSection('tbody');
	
	foreach(slot::getArray() as $slot) {
		
		$edit = '<select name="module">';
		$edit .= '<option value="">Nicht belegt</option>';
		//Module auslesen, wenn zugewiesen durch die Tabelle "slots" dann ausgewählt - das ganze bestenfalls per Ajax speichern wenn man das select-Feld ändern <- das ganze pro Template
		$edit .= '</select>';
		
		$table->addRow()
		->addCell($slot)
		->addCell('<span class="btn-group">'.$edit.'</span>');
		
	}
	
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo lang::get('slots_current_page'); ?></h3>
            </div>
            <?php echo $table->show(); ?>
        </div>
    </div>
</div>