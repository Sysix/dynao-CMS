<?php

extension::add('MEDIAMANAGER_FILE_FORM', function($form) {

    layout::addJS('layout/js/dropzone.js');
    layout::addJsCode("$('.dropzone').dropzone();");

    $file = $form->getElement('form_2');
    $file->setValue('<div class="dropzone"></div>');


    return $form;

});


?>