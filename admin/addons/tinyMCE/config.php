<?php

layout::addJS('addons/tinyMCE/layout/js/tinymce/tinymce.min.js');
layout::addJsCode('tinymce.init({
    selector: "textarea.tinyMCE",
    relative_urls : false,
    document_base_url: "'.dyn::get('hp_url').'",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste filemanager"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
', false);

$page = type::get('page', 'string');
$subpage = type::get('subpage', 'string');
$layout = type::get('layout', 'string');

if($page == 'media' && $subpage == 'popup' && $layout == 'true') {
    layout::addJsCode("
    if(parent.tinymce.editors.length) {
        $('.dyn-media-select').on('click', function() {
            parent.tinymce.activeEditor.windowManager.getParams().setUrl('/media/' + $(this).data('name'));
            parent.tinymce.activeEditor.windowManager.close();
        });
	}
    ");
}

?>