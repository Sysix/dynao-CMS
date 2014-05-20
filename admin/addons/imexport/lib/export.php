<?php
class export
{
	use traitFactory;
	public static function exportTables()
	{
		if(!isset ($_POST["export"]))
		{
			echo message :: danger(lang :: get('exportSelect'), true);
		}
		else
		{
			$result = '';
			foreach($_POST["export"] as $table => $status)
			{
				$DB = dyn :: get('DB');
				$prefix = strlen($DB['prefix']);
				$result .= 'DROP TABLE IF EXISTS `dynaoimportexporttoll'.$table.'`;';
				$sql = sql :: factory();
				$sql->query('SHOW CREATE TABLE '.$DB['prefix'].$table)->result();
				$creatTable = "\n\n".str_replace("CREATE TABLE `".$DB['prefix'], "CREATE TABLE IF NOT EXISTS `dynaoimportexporttoll", $sql->get("Create Table")).";\n\n";
                $result .= preg_replace("/(`.*` int.* DEFAULT) '(.*)'/", '${1} ${2}', $creatTable);
                $sql->query("SELECT * FROM ".$DB['prefix'].$table)->result();
				while($sql->isNext())
				{
					$result .= 'INSERT INTO `dynaoimportexporttoll'.$table.'` VALUES(';
					$i = 1;
					foreach($sql->result as $row)
					{
						$result .= "'".$sql->escape(str_replace(";", "`#semikolon#`",$row))."'";
						if(count($sql->result) > $i)
						{
							$result .= ",";
						}
						$i++;
					}
					$result .= ");\n";
					$sql->next();
				}
			}
			$length = strlen($result);
			header('Content-Description: File Transfer');
			header('Content-Type: application/sql');
			header('Content-Disposition: attachment; filename=backup.sql');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.$length);
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Expires: 0');
			header('Pragma: public');
            echo $result;
            exit;
		}
	}
}
?>