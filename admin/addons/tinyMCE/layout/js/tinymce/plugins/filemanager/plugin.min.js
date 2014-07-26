tinymce.PluginManager.add('filemanager', function(editor) {

    tinymce.activeEditor.settings.file_browser_callback = filemanager;

    function filemanager (id, value, type, win) {

        var title="FileManager";


        tinymce.activeEditor.windowManager.open({
            title: title,
            file: 'index.php?page=media&subpage=popup&layout=true',
            width: 860,
            height: 570,
            resizable: true,
            maximizable: true,
            inline: 1
        }, {
            setUrl: function (url) {
                var fieldElm = win.document.getElementById(id);
                fieldElm.value = editor.convertURL(url);
                if ("fireEvent" in fieldElm) {
                    fieldElm.fireEvent("onchange")
                } else {
                    var evt = document.createEvent("HTMLEvents");
                    evt.initEvent("change", false, true);
                    fieldElm.dispatchEvent(evt);
                }
            }
        });
    };
    return false;
});
