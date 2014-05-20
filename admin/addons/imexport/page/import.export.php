<?php
if($action == 'export')
{
	export :: exportTables();
}                          
$DB = dyn :: get('DB');
$prefix = strlen($DB['prefix']);
$table = table :: factory();
$table->addCollsLayout('*, 215');
$table->addRow()->addCell(lang :: get('name'))->addCell(lang :: get('exports'));
$table->addSection('tbody');
$table->setSql("SHOW TABLES IN ".sql :: $DB_datenbank." LIKE '".dyn :: get('DB') ["prefix"]."%'");
if($table->numSql())
{
	while($table->isNext())
	{
		$name = $table->get("Tables_in_".sql :: $DB_datenbank." (".dyn :: get('DB') ["prefix"]."%)");
		$name = substr($name, $prefix);
		$inputCheckbox = formInput :: factory('export['.$name.']', $name);
		$inputCheckbox->addAttribute('type', 'checkbox');
		$inputCheckbox->addClass('input-sm');
		$inputCheckbox->autofocus();
		$table->addRow()->addCell($name)->addCell($inputCheckbox->get());
		$table->next();
	}
}
?>
<form action="index.php" method="post">
<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo lang :: get('sqlexport') ?></h3>
				</div>
                <?php echo $table->show();?>
        </div>
    </div>
</div>
<input type="hidden" name="page" value="import" class="form-control" />
<input type="hidden" name="subpage" value="export" class="form-control" />
<input type="hidden" name="action" value="export" class="form-control" />
<button type="submit" class="btn-default btn-sm btn" name="save-back"><?php echo lang :: get('exports') ?></button>
</form>