<?php

layout::addJS('addons/tinyMCE/layout/js/tinymce/tinymce.min.js');
layout::addJsCode('tinymce.init({
    selector: "textarea.tinyMCE",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
', false);

userPerm::add('tinyMCE[]', 'TinyMCE konfigurieren');

?>