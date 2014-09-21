<?php

// Klasse zu Erstellung für Formulare
class form {
	use traitFactory;
	use traitMeta;
	
	var $method;
	var $action;

    /** @var sql $sql */
	var $sql;
	
	var $mode = 'add';
	
	var $return = [];
	var $buttons = [];
	var $params = [];
	
	var $formAttributes = [];
	
	// Formular wirklich speichern
	var $toSave = true;
	
	var $successMessage;
	var $errorMessage;
	
	var $isSubmit;
	
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
		
		$this->method = $method;
		$this->action = $action;
		
		$this->sql = sql::factory();
		$this->sql->query('SELECT * FROM '.sql::table($table).' WHERE '.$where.' LIMIT 1');
		$this->sql->result();
		
		if($this->sql->num() == 1) {
			$this->setMode('edit');
		}

        $this->setWhere($where);

		$this->setTable($table);
		
		if(dyn::get('backend')) {
			$this->loadBackend();
		}
		
		$this->addFormAttribute('class', 'form-horizontal');
		$this->addFormAttribute('action', $this->action);
		$this->addFormAttribute('method', $this->method);
		
		$this->setButtons();
		
		$this->setSuccessMessage(lang::get('form_saved'));
		$this->addParam('action', $this->mode);
		
	}

    /**
     * @param sql $sql
     * @return $this
     */
    public function setSql(sql $sql) {

        $this->sql = $sql;

        return $this;
    }


	
	/**
	 * Ausgabe der SQL Spalte, falls nichts gefunden nimmt er $default
	 *
	 * @param	string	$value			Die zu suchende Spalte
	 * @param	mixed	$default		Falls nichts gefunden
	 * @return	mixed
	 *
	 */
	public function get($value, $default = null) {
		
		// Falls per Post übermittelt		
		return type::post($value, '',
			$this->sql->get($value, $default)
		);
		
		
	}
	
	/**
	 * Die Speichern Buttons setzten
	 *
	 * @return	this
	 */
	public function setButtons() {
				
		$submit = $this->addSubmitField('save', lang::get('save'));
		$submit->addClass('btn-default btn-sm');
			
		$submit = $this->addSubmitField('save-back', lang::get('apply'));
		$submit->addClass('btn-default btn-sm');
		
		$back = $this->addButtonField('back', lang::get('back'));
		$back->addClass('btn-warning btn-sm');
		$back->addClass('form-back');
		
		return $this;
		
	}
	
	/**
	 * Standarteinstellungen fürs Backend
	 *
	 * @return	this
	 */
	public function loadBackend() {
	
		$page = type::super('page', 'string');
		$subpage = type::super('subpage', 'string');
		
		$this->addParam('page', $page);
		$this->addParam('subpage', $subpage);
		
		return $this;
		
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
	public function addFreeField($name, $value, $class, $attributes = []) {
	
		return new $class($name, $value, $attributes);
		
	}
	
	/**
	 * Ein neues Element erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	object	$object			Das Element
	 * @return	class
	 *
	 */
	public function addElement($name, $object) {
		
		$this->return[$name] = $object;
		
		return $object;
		
	}
	
	/**
	 * Ein neues Element erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	string	$class			Die entsprechende PHP Klasse
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	formField
	 *
	 */
	private function addField($name, $value, $class, $attributes = []) {
		
		$field = new $class($name, $value, $attributes);
		$this->addElement($name, $field);
		
		return $field;
		
	}
	
	/**
	 * Ein Textfeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	formInput	 *
	 */
	public function addTextField($name, $value, $attributes = []) {
		
		$attributes['type'] = 'text';
		return $this->addField($name, $value, 'formInput', $attributes);
		
	}

    /**
     * Ein Zahlenfeld erstellen
     *
     * @param	string	$name			Der Name
     * @param	string	$value			Der Value
     * @param	array	$attributes		Die HTML Attribute
     * @return	formInput	 *
     */
    public function addNumberField($name, $value, $attributes = []) {

		$attributes['type'] = 'number';
		return $this->addField($name, $value, 'formInput', $attributes);

	}
	
	/**
	 * Ein Passwordfeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	formInput
	 *
	 */
	public function addPasswordField($name, $value, $attributes = []) {
		
		$attributes['type'] = 'password';
		return $this->addField($name, $value, 'formInput', $attributes);
		
	}
	
	/**
	 * Ein UnsichtbaresFeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	formInput
	 *
	 */
	public function addHiddenField($name, $value, $attributes = []) {
		
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
	 * @return	formButton
	 *
	 */
	public function addSubmitField($name, $value, $attributes = [], $toButtons = true) {
		
		$attributes['type'] = 'submit';
		$field = $this->addFreeField($name, $value, 'formButton', $attributes);
		if($toButtons) {
			$this->addButton($field);
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
	 * @return	formButton
	 *
	 */
	public function addButtonField($name, $value, $attributes = [], $toButtons = true) {
		
		$attributes['type'] = 'button';
		$field = $this->addFreeField($name, $value, 'formButton', $attributes);
		if($toButtons) {
			$this->addButton($field);
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
	 * @return	formButton
	 *
	 */
	public function addResetField($name, $value, $attributes = [], $toButtons = true) {
		
		$attributes['type'] = 'reset';
		$field = $this->addFreeField($name, $value, 'formButton', $attributes);
		if($toButtons) {
			$this->addButton($field);
		}
		return $field;
		
	}
	
	/**
	 * Ein Textareafeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	formTextarea
	 *
	 */
	public function addTextareaField($name, $value, $attributes = []) {
		
		return $this->addField($name, $value, 'formTextarea', $attributes);
		
	}
	
	/**
	 * Ein Radiofeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	formRadio
	 *
	 */
	public function addRadioField($name, $value, $attributes = []) {
		
		return $this->addField($name, $value, 'formRadio', $attributes);
		
	}
	
	/**
	 * Ein Checkboxfeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	formCheckbox
	 *
	 */
	public function addCheckboxField($name, $value, $attributes = []) {
		
		return $this->addField($name, $value, 'formCheckbox', $attributes);
		
	}
	
	/**
	 * Ein Selectfeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @param	array	$attributes		Die HTML Attribute
	 * @return	formSelect
	 *
	 */
	public function addSelectField($name, $value, $attributes = []) {
		
		return $this->addField($name, $value, 'formSelect', $attributes);
		
	}
	
	/**
	 * Ein Freies Feld erstellen
	 *
	 * @param	string	$value			Der Inhalt
	 * @return	formRaw
	 *
	 */
	public function addRawField($value) {
		
		return $this->addField('form_'.count($this->return), $value, 'formRaw');
		
	}
	
	/**
	 * Ein Linkfeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @return	formLink
	 *
	 */
	public function addLinkField($name, $value, $attributes = []) {
		
		return $this->addField($name, $value, 'formLink', $attributes);
		
	}
	
	/**
	 * Ein Linkfeld erstellen
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Value
	 * @return	formLinklist
	 *
	 */
	public function addLinklistField($name, $value, $attributes = []) {
		
		return $this->addField($name, $value, 'formLinklist', $attributes);
		
	}
	
	/**
	 * Ein Button hinzufügen am Ende des Formulars
	 *
	 * @param	object	$field			Der Button als Klasse
	 * @return	this
	 */
	public function addButton($field) {
		
		$field->setSave(false);
		$this->buttons[$field->getName()] = $field;
				
		return $this;
		
	}
	
	/**
	 * Einen Button löschen
	 *
	 * @param	string	$name			Der Name des Buttons
	 * @return	this
	 */
	public function delButton($name) {
	
		unset($this->buttons[$name]);
				
		return $this;
		
	}
	
	/**
	 * Ein Paramter hinzufügen
	 *
	 * @param	string	$name			Der Name des Paramter
	 * @param	string	$value			Der Value
	 * @return	this
	 */
	public function addParam($name, $value) {
	
		$this->params[$name] = $value;
				
		return $this;
		
	}
	
	/**
	 * Ein Paramter löschen
	 *
	 * @param	string	$name			Der Name des Paramter
	 * @return	this
	 */
	public function delParam($name) {
	
		unset($this->params[$name]);
				
		return $this;
		
	}
	
	/**
	 * Alle Parameter zurückgeben
	 *
	 * @return	array
	 */
	public function getParams() {
	
		return $this->params;
		
	}
		
	/**
	 * Modus setzen
	 *
	 * @param	string	$mode			Der Modus
	 * @return	this
	 */
	public function setMode($mode) {	
			
		$this->mode = $mode;

        $this->addParam('action', $this->mode);
		
		return $this;
		
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
	public function isSubmit($ignoreSaves = false) {
		
		$save = type::post('save');
		$save_edit = type::post('save-back');
		
		if(!is_null($save) || !is_null($save_edit)) {
			
			if(is_null($this->isSubmit) || !$ignoreSaves) {
				$this->setPostsVar();
			}
			
			$this->isSubmit = true;
			
			return true;
				
		}
			
		return false;	
				
	}
	
	/**
	 * Tabelle setzen für SQL
	 *
	 * @param	string	$table			Die Tabelle
	 * @return	this
	 */
	public function setTable($table) {
	
		$this->sql->setTable($table);
		
		return $this;
			
	}
	
	/**
	 * Where setzten für SQL
	 *
	 * @param	string	$where			Die Where Bedigung
	 * @return	this
	 */
	public function setWhere($where) {
	
		$this->sql->setWhere($where);
		
		return $this;
		
	}

    /**
     * get whereparam
     *
     * @return	string
     */
    public function getWhere() {

        return $this->sql->where;

    }
	
	/**
	 * Geht die ganzen Felder durch, und speichert sie in der SQL für die spätere Speicherung
	 *
	 * @return	this
	 */
	protected function setPostsVar() {
	
		foreach($this->return as $ausgabe) {
			
			if(!$ausgabe->isValid()) {
				
				$this->setErrorMessage($ausgabe->getError());
				$this->setSave(false);
				
			}
			
			if(!$ausgabe->toSave()) {
				continue;
			}
			
			$name = $ausgabe->getName();
			$value = $ausgabe->getSaveValue();
						
			$this->addPost($name, $value);
			
		}
		
		return $this;
		
	}
	
	/*
	 * Fügt eine SQL Spalte hinzu
	 *
	 * @param	string	$name			Die Spalte
	 * @param	string	$val			Der Inhalt
	 * @return	this
	 */
	public function addPost($name, $val) {
		
		$this->sql->addPost($name, $val);
		
		return $this;
		
	}
	
	/**
	 * Löscht eine SQL Spalte
	 *
	 * @param	string	$name			Die Spalte
	 * @return	this
	 */
	public function delPost($name) {
		
		$this->sql->delPost($name);
		
		return $this;
		
	}

    /*
     * return the sql object
     * @return sql
     */
    public function getSql() {

        return $this->sql;

    }
	
	/*
	 * Setzt einen Paramater, der später dazu führt, ob das Formular gespeichert wird oder nicht
	 *
	 * @param	bool	$bool			Ja/Nein
	 * @return	this
	 */
	public function setSave($bool) {
		
		$this->toSave = $bool;
		
		return $this;
		
	}
	
	/**
	 * Die SQL Speicherung
	 *
	 * @return	this
	 */
	public function saveForm() {
		
		extension::get('FORM_BEFORE_SAVE', $this->sql);

		if(!$this->toSave)
			return $this;

		if($this->isEditMode()) {
			$this->sql->update();
		} else {
			$this->sql->save();
		}
		
		extension::get('FORM_AFTER_SAVE', $this->sql);

		return $this;
		
	}
	
	/**
	 * Überprüfen ob auf Übernehmen geklickt worde nist
	 *
	 * @return	bool
	 *
	 */
	public function isSaveEdit() {
	
		return !is_null(type::post('save-back'));
		
	}
	
	/**
	 * Fügt ein Form-HTML Attribute hinzu
	 *
	 * @param	string	$name			Der Name
	 * @param	string	$value			Der Inhalt
	 * @return	$this
	 */
	public function addFormAttribute($name, $value) {
		
		$this->formAttributes[$name] = $value;	
		
		return $this;
		
	}

    /**
     * Gibt ein Formular element zurück
     *
     * @return	class
     */
    public function getElement($name) {

        return $this->return[$name];

    }

        /**
	 * Löscht ein Formular Feld
	 *
	 * @return	$this
	 */
	public function deleteElement($name) {
	
		unset($this->return[$name]);
		
		return $this;
		
	}
	
	/**
	 * Weiterleitung falls auf "Speichern" geklickt worden ist
	 *
	 */
	public function redirect() {		
		
		if(!is_null($this->errorMessage)) {
			
			$this->addParam('error_msg', $this->errorMessage);
			
		} elseif(!is_null($this->successMessage)) {
			
			$this->addParam('success_msg', $this->successMessage);
			
		}
		
		$this->delParam('action');
	
		$params = url_addParam(
			array_keys($this->getParams()), 
			array_values($this->getParams())
			);
			
		$url = 'index.php?'.$params;
		
		header('Location: '.$url);
		die;
		
	}
	
	/**
	 * Eine Erfolgnachricht speichern für spätere erfolgreiche Ausgabe
	 *
	 * @param	string	$message		Die Nachricht
	 * @return	this
	 *
	 */
	public function setSuccessMessage($message) {
	
		$this->successMessage = $message;
		
		return $this;
		
	}
	
	/**
	 * Eine Fehlernachricht speichern für spätere fehlerhafte Ausgabe
	 *
	 * @param	string	$message		Die Nachricht
	 * @return	this
	 *
	 */
	public function setErrorMessage($message) {
	
		$this->errorMessage = $message;
		
		return $this;
		
	}
	
	
	/**
	 * Fügt das Formular zusammen und speichert es, falls nötig
	 *
	 * @return	string
	 *
	 */
	public function show() {
		
		extension::get('FORM_BEFORE_ACTION', $this);

		foreach($this->getParams() as $key=>$value) {
			$param = $this->addHiddenField($key, $value);
			$param->setSave(false);
		}

        $return = [];
        $buttons = [];
        $hidden = [];
        $x = 1;

        $return[] = '<form'.html_convertAttribute($this->formAttributes).'>'.PHP_EOL;
		
		if($this->isSubmit(true)) {

            $where = $this->getWhere();

            $regex = '/[`\s]*([\w_-]+)[`\s]*=\s*[\'|"]?(\d*)[\'|"]?/';

            if(preg_match($regex, $where, $match)) {

                if(!$this->isEditMode()) {
                    $this->setWhere('');
                } else {
                    $this->deleteElement($match[1]);
                }

                $this->saveForm();

                $this->addParam('action', 'edit');
                $this->setMode('edit');

                $col = ($match[1] == 'id' && !$this->isEditMode()) ? $this->getSql()->insertId() : $this->get($match[1]);

                $this->setWhere('`'.$match[1].'` = '.$col);

                $this->addHiddenField($match[1], $col);
                $this->addHiddenField('action', $this->mode);
                $this->action = $this->mode;

            }

			if(!$this->isSaveEdit() && is_null($this->errorMessage)) {		
				$this->redirect();
			}
			
			if(!is_null($this->errorMessage)) {
			
				$return[] = message::danger($this->errorMessage);
			
			} elseif(!is_null($this->successMessage)) {
				
				$return[] = message::success($this->successMessage);
					
			}
			
		}



		foreach($this->return as $ausgabe) {
			
			if($ausgabe->getAttribute('type') == 'hidden') {
				$hidden[] = $ausgabe->get();
				continue;
			}
				
			if(!$ausgabe->hasAttribute('id')) {
				$ausgabe->addAttribute('id', 'form_'.$x);
			}

            $class = '';

            if(!$ausgabe->isValid()) {
                $class = ' class="has-error"';
            }
				
			$return[] = '<div class="form-group"'.$class.'>';
			$return[] = '<label for="'.$ausgabe->getAttribute('id').'">'.$ausgabe->fieldName.'</label>';
			$return[] = '<div class="form-wrap-input">'.$ausgabe->prefix . $ausgabe->get() . $ausgabe->suffix.'</div>';
			$return[] = '</div>';
			
			$x++;
			
		}		
		
		foreach($this->buttons as $button) {
			$buttons[] = $button->get();
		}
		
		$return[] = implode(PHP_EOL, $hidden);
		$return[] = '<div class="form-group">';
		$return[] = '<div class="form-submit-area col-sm-10 btn-group">'.implode(PHP_EOL, $buttons).'</div>';
		$return[] = '</div>';
			
		$return[] = '</form>';
		
		$return = extension::get('FORM_BEFORE_SHOW', implode(PHP_EOL, $return));
		
		return $return;
		
	}
	
}

?>