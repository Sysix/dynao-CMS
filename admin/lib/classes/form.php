<?php

// Klasse zu Erstellung für Formulare
class form {
	
	
	var $method;
	var $action;
	
	var $sql;
	
	var $mode = 'add';
	
	var $return = array();
	var $buttons = array();
	
	var $formAttributes = array();
	
	// Beim Senden schauen ob die Forumluar-Einträge schon übernommen worden sind Methode: setPostsVar
	var $isGetPosts = false;
	var $isSubmit;
	
	// Formular wirklich speichern
	var $toSave = true;
	
	/**
	 * Das Formular erstellen
	 *
	 * @param	string	$table			Die SQL Tabelle
	 * @param	string	$where			Die SQL Abfrage
	 * @param	string	$action			Ziel URL um die Daten zu bearbeiten
	 * @param	string	$method			Die Methode (post|get)
	 *
	 */
	public function __construct($table, $where, $action, $method = 'post') {
		
		// Gültige Methode?		
		if(!in_array($method, array('post', 'get'))) {
			// new Exception();
		}
		
		$this->method = $method;
		$this->action = $action;
		
		$sql = new sql();
		$this->sql = $sql->query('SELECT * FROM '.sql::table($table).' WHERE '.$where.' LIMIT 1');		
		
		$this->sql->result();
		
		if($this->sql->num() == 1) {
			$this->setMode('edit');
			$this->setWhere($where);
		}
		
		$this->setTable($table);
		
		$this->loadBackend();
		
		$this->addFormAttribute('class', 'form-horizontal');
		$this->addFormAttribute('action', $this->action);
		$this->addFormAttribute('method', $this->method);

		
	}
	
	/**
	 * Ausgabe der SQL Spalte, falls nichts gefunden nimmt er $default
	 *
	 * @param	string	$value			Die zu suchende Spalte
	 * @param	mixed	$default		Falls nichts gefunden
	 * @return	mixed
	 *
	 */
	public function get($value, $default = false) {
		
		// Falls per Post übermittelt
		if(isset($_POST[$value])) {
			
			return $_POST[$value];
			
		}
		
		if($this->sql->get($value))	{
		
			return $this->sql->get($value);
			
		}
		
		return $default;
		
	}
	
	/**
	 * Die Speichern Buttons setzten
	 *
	 */
	public function setButtons() {
				
		$submit = $this->addSubmitField('save', lang::get('save'));
		$submit->addClass('btn');
		$submit->addClass('btn-default');
			
		$submit = $this->addSubmitField('save-back', lang::get('apply'));
		$submit->addClass('btn');
		$submit->addClass('btn-default');	
		
		$back = $this->addButtonField('back', lang::get('back'));
		$back->addClass('btn');
		$back->addClass('btn-warning');
		$back->addClass('form-back');
		
	}
	
	/**
	 * Standarteinstellungen fürs Backend
	 *
	 */
	public function loadBackend() {
	
		$page = type::super('page', 'string');
		$subpage = type::super('subpage', 'string');
		
		$this->addHiddenField('page', $page);
		$this->addHiddenField('subpage', $subpage);
		
	}
	
	/**
	 * Ein neues freies Element erstellen, welches nicht ins Formular automatisch gespeichert wird
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	string	$class			Die entsprechende PHP Klasse
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	class
	 *
	 */	
	public function addFreeField($name, $value, $class, $attributes = array()) {
	
		return new $class($name, $value, $attributes);
		
	}
	
	/**
	 * Ein neues Element erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	string	$class			Die entsprechende PHP Klasse
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	class
	 *
	 */
	private function addField($name, $value, $class, $attributes = array()) {
		
		$field = new $class($name, $value, $attributes);
		$this->return[$name] = $field;
		
		return $field;
		
	}
	
	/**
	 * Ein Textfeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	class
	 *
	 */
	public function addTextField($name, $value, $attributes = array()) {
		
		$attributes['type'] = 'text';
		return $this->addField($name, $value, 'formInput', $attributes);
		
	}
	
	/**
	 * Ein Passwordfeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	class
	 *
	 */
	public function addPasswordField($name, $value, $attributes = array()) {
		
		$attributes['type'] = 'password';
		return $this->addField($name, $value, 'formInput', $attributes);
		
	}
	
	/**
	 * Ein UnsichtbaresFeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	class
	 *
	 */
	public function addHiddenField($name, $value, $attributes = array()) {
		
		$attributes['type'] = 'hidden';
		return $this->addField($name, $value, 'formInput', $attributes);
				
	}
	
	/**
	 * Ein Submitfeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @param	bool	$toButtons		Soll das Feld zu den Standardbuttons hinzugefügt werden
	 * @return	class
	 *
	 */
	public function addSubmitField($name, $value, $attributes = array(), $toButtons = true) {
		
		$attributes['type'] = 'submit';
		$field = $this->addFreeField($name, $value, 'formButton', $attributes);
		if($toButtons) {
			$this->buttons[] = $field;
		}
		return $field;
		
	}
	
	/**
	 * Ein Buttonfeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @param	bool	$toButtons		Soll das Feld zu den Standardbuttons hinzugefügt werden
	 * @return	class
	 *
	 */
	public function addButtonField($name, $value, $attributes = array(), $toButtons = true) {
		
		$attributes['type'] = 'button';
		$field = $this->addFreeField($name, $value, 'formButton', $attributes);
		if($toButtons) {
			$this->buttons[] = $field;
		}
		return $field;
		
	}
	
	/**
	 * Ein Resetfeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @param	bool	$toButtons		Soll das Feld zu den Standardbuttons hinzugefügt werden
	 * @return	class
	 *
	 */
	public function addResetField($name, $value, $attributes = array(), $toButtons = true) {
		
		$attributes['type'] = 'reset';
		$field = $this->addFreeField($name, $value, 'formButton', $attributes);
		if($toButtons) {
			$this->buttons[] = $field;
		}
		return $field;
		
	}
	
	/**
	 * Ein Textareafeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	class
	 *
	 */
	public function addTextareaField($name, $value, $attributes = array()) {
		
		return $this->addField($name, $value, 'formTextarea', $attributes);
		
	}
	
	/**
	 * Ein Radiofeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	class
	 *
	 */
	public function addRadioField($name, $value, $attributes = array()) {
		
		return $this->addField($name, $value, 'formRadio', $attributes);
		
	}
	
	/**
	 * Ein Checkboxfeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	class
	 *
	 */
	public function addCheckboxField($name, $value, $attributes = array()) {
		
		$field = $this->addField($name, $value, 'formCheckbox', $attributes);
		$field->setChecked($value);
		return $field; 
		
	}
	
	/**
	 * Ein Selectfeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	class
	 *
	 */
	public function addSelectField($name, $value, $attributes = array()) {
		
		$field = $this->addField($name, $value, 'formSelect', $attributes);
		$field->setSelected($value);
		return $field;
		
	}
	
	/**
	 * Ein Freies Feld erstellen
	 *
	 * @param	string	$value			Der Inhalt
	 * @return	class
	 *
	 */
	public function addRawField($value) {
		
		return $this->addField('', $value, 'formRaw');
		
	}
	
	/**
	 * Modus setzen
	 *
	 * @param	string	$mode			Der Modus
	 *
	 */
	public function setMode($mode) {
	
		if(in_array($mode, array('add', 'edit'))) {
			
			$this->mode = $mode;
				
		} else {			
			// new Exception();				
		}
		
	}
	
	/**
	 * Ist Editermodus?
	 *
	 * @return	bool
	 *
	 */
	public function isEditMode() {
	
		return $this->mode == 'edit';
		
	}
	
	/**
	 * Abfrage ob Formular gerade am speichern ist
	 *
	 * @return	bool
	 *
	 */
	public function isSubmit() {
		
		// Wurde schon isSubmit ausgeführt? dann schnelles Return
		if($this->isSubmit === true || $this->isSubmit === false) {
			return $this->isSubmit;
				
		}
		
		
		$save = false;
		$save_edit = false;
		
		$save = type::post('save', 'string', false);

		if($this->isEditMode()) {
			$save_edit = type::post('save-back', 'string', false);	
		}

		if($save !== false || $save_edit !== false) {
			
			if(!$this->isGetPosts) {
		
				$this->setPostsVar();
				$this->isGetPosts = true;
				
			}
			
			$this->isSubmit = true;
			
			return true;
				
				
		}
		
		$this->isSubmit = false;
		
		return false;
		
		
	}
	
	/**
	 * Tabelle setzen für SQL
	 *
	 * @param	string	$table			Die Tabelle
	 *
	 */
	public function setTable($table) {
	
		$this->sql->setTable($table);
			
	}
	
	/**
	 * Where setzten für SQL
	 *
	 * @param	string	$where			Die Where Bedigung
	 *
	 */
	public function setWhere($where) {
	
		$this->sql->setWhere($where);
		
	}
	
	/**
	 * Geht die ganzen Felder durch, und speichert sie in der SQL für die spätere Speicherung
	 *
	 */
	protected function setPostsVar() {
	
		foreach($this->return as $ausgabe) {
			
			$name = $ausgabe->getName();
			
			if(in_array($name, array('page', 'subpage', 'action', ''))) {
				continue;	
			}
				
			$val = type::post($name, '', '');
			
			if(is_array($val)) {
											
				$val = '|'.implode('|', $val).'|';
					
			}
				
			$this->addPost($name,  $val);
			
		}
		
	}
	
	/**
	 * Fügt eine SQL Spalte hinzu
	 *
	 * @param	string	$name			Die Spalte
	 * @param	string	$val			Der Inhalt
	 *
	 */
	public function addPost($name, $val) {
		
		$this->sql->addPost($name, $val);
		
	}
	
	/**
	 * Löscht eine SQL Spalte
	 *
	 * @param	string	$name			Die Spalte
	 *
	 */
	public function delPost($name) {
		
		$this->sql->delPost($name);
		
	}
	
	/**
	 * Setzt einen Paramater, der später dazu führt, ob das Formular gespeichert wird oder nicht
	 *
	 * @param	bool	$bool			Ja/Nein
	 *
	 */
	public function setSave($bool) {
	
		if(!is_bool($bool)) {
			//throw new Exception;	
		}
		
		$this->toSave = $bool;
		
	}
	
	/**
	 * Die SQL Speicherung
	 *
	 */
	private function saveForm() {
	
		if(!$this->toSave)
			return;
	
		if($this->isEditMode()) {
			$this->sql->update();
		} else {
			$this->sql->save();
		}
		
	}
	
	/**
	 * Überprüfen ob auf Übernehmen geklickt worde nist
	 *
	 * @return	bool
	 *
	 */
	public function isSaveEdit() {
	
		return (bool)(type::post('save-back', 'string', false) !== false);
		
	}
	
	/**
	 * Fügt ein Form-HTML Attribute hinzu
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Inhalt
	 *
	 */
	public function addFormAttribute($name, $value) {
		
		$this->formAttributes[$name] = $value;	
		
	}
	
	/**
	 * Löscht ein Formular Feld
	 *
	 */
	public function deleteElement($name) {
	
		unset($this->return[$name]);
		
	}
	
	/**
	 * Fügt das Formular zusammen und speichert es, falls nötig
	 *
	 * @return	string
	 *
	 */
	public function show() {
		
		$this->addHiddenField('action', $this->mode);
		
		if($this->isSubmit()) {
			
			$this->saveForm();
			
			if(!$this->isSaveEdit()) {
				//
				$GLOBALS['action'] = '';
				return;
			}
			
		}
		
		$return = '<form'.html_convertAttribute($this->formAttributes).'>'.PHP_EOL;
		
		
		$buttons_echo = '';
		
		foreach($this->return as $ausgabe) {
			
			if($ausgabe->getAttribute('type') == 'hidden') {
				
				$buttons_echo .= $ausgabe->get();
				
			} else {
				$ausgabe->addClass('form-control');
				
				$return .= '<div class="form-group">';
				$return .= '<label>'.$ausgabe->fieldName.'</label>';
				$return .= '<div class="form-wrap-input">'.$ausgabe->prefix . $ausgabe->get() . $ausgabe->suffix.'</div>';
				$return .= '</div>';
				
			}
			
		}		
		
		$this->setButtons();		
		
		foreach($this->buttons as $buttons) {
			$buttons_echo .= $buttons->get();	
		}
		
		$return .= '<div class="form-group">';
		$return .= '<label></label>';
		$return .= '<div class="form-wrap-input btn-group">'.$buttons_echo.'</div>';
		$return .= '</div>';
			
		$return .= '</form>';
		
		return $return;
		
	}
	
}

?>