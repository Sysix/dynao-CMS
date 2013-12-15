<?php

class mediaUtils {
	

	public static function fixFileName($name) {
		
		$name = mb_strtolower($name);
		
		$replace = [
			'ä'=>'ae',
			'ö'=>'oe',
			'ü'=>'ue',
			'ß'=>'ss'
		];
		$name = str_replace(array_keys($replace), array_values($replace), $name);
		
		$name = preg_replace('/[^a-z0-9._]/', '-', $name);
		
		return preg_replace('/[-]{2,}/', '-', $name);
		
	}
	
	public static function saveFile($file, $form) {
		
		if(!is_uploaded_file($file['tmp_name'])) {			
			return $form;				
		}
		
		$fileName = mediaUtils::fixFileName($file['name']);
		$fileDir = dir::media($fileName);
		$extension = substr(strrchr($fileName, '.'), 1); // z.B. jpg
		
		// Wenn die Datei eine "verbotene" Datei ist
		if(in_array($extension, dyn::get('badExtensions', []))) {
			
			$form->setSave(false);
			$form->setErrorMessage(sprintf(lang::get('media_error_bad_extension'), $file['name']));
			
			return $form;
			
		}
		
		if($form->isEditMode()) {
			$media = new media(type::super('id', 'int', 0));
		}
		
		// Wenn Datei nicht Existiert
		// Oder man möchte sie überspeichern
		if(($form->isEditMode() && $media->get('filename') != $fileName) || (!$form->isEditMode() &&file_exists($fileDir))) {
			
			$form->setSave(false);
			$form->setErrorMessage(sprintf(lang::get('media_error_already_exist'), $file['name']));
			
			return $form;
			
		}
		
		if(!move_uploaded_file($file['tmp_name'], $fileDir)) {
			
			$form->setSave(false);
			$form->setErrorMessage(sprintf(lang::get('media_error_move'), $file['name']));
			
			return $form;
			
		}
		
		$form->addPost('filename', $fileName);
		$form->addPost('size', filesize($fileDir));
		
		return $form;
	
	}
	
	public static function getTreeStructure($parentId = 0, $lvl = 0, $spacer = ' &nbsp;', $active = 0) {
		
		$select = '';
		
		$sql = sql::factory();
		$sql->query('SELECT id, name FROM '.sql::table('media_cat').' WHERE pid = '.$parentId.' ORDER BY sort')->result();	
		while($sql->isNext()) {
			
			$name = $sql->get('name');
			
			if($lvl) {
				$name = '- '.$name;
			}
			
			if($spacer != '') {
				
				for($i = 1; $i <= $lvl; $i++) {
					$name = $spacer.$name;
				}
				
			}
			
			$selected = ($active == $sql->get('id')) ? 'selected="selected"' : '';
			
			$select .= '<option value="'.$sql->get('id').'" '.$selected.'>'.$name.'</option>'.PHP_EOL;
						
			$select .= self::getTreeStructure($sql->get('id'), $lvl+1, $spacer, $active);
			
			$sql->next();
		}
		
		return $select;
		
	}
	
}

?>