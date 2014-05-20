<?php
if($action == 'import')
{
	if(isset ($_FILES["importfile"]) && count($_FILES["importfile"]) > 0)
	{
		$upload = $_FILES["importfile"];
		$sql = sql :: factory();
		$DB = dyn :: get('DB');
		$prefix = $DB['prefix'];
		$querys = file_get_contents($upload["tmp_name"]);
		$querys = str_replace("`dynaoimportexporttoll", "`".$prefix, $querys);
		foreach(explode(';', $querys) as $query)
		{
		    $replace = array("\r\n", "\n", "\r");
            $check = str_replace($replace, '', $query);
			if(substr($check, -1) != '')
			{
				$sql->query(str_replace("`#semikolon#`", ";", $query).';');
			}
		}
        echo message::success(lang::get('sqlimportsuccess'));
	}
}
?>
<form action="index.php" method="post" enctype="multipart/form-data">
    <div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo lang :: get('sqlimport') ?></h3>
				</div>
                <div class="panel-body">
                    <?php echo message :: info(lang :: get('sqlimporttext')) ?>

                    <input name="importfile" type="file" accept="application/octet-stream"/>
                    <input type="hidden" name="page" value="import" class="form-control" />
                    <input type="hidden" name="subpage" value="import" class="form-control" />
                    <input type="hidden" name="action" value="import" class="form-control" />
                    <br>
                    <button type="submit" class="btn-default btn-sm btn" name="save-back"><?php echo lang :: get('imports') ?></button>
                </div>
            </div>
        </div>
    </div>
</form>