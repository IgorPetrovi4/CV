// Creates links to the TinyMCE
require.context(
    '!file-loader?name=[path][name].[ext]&context=node_modules/tinymce!tinymce/skins/ui/oxide',
    true,
    /.*/
);



// Import TinyMCE
var tinymce = require('tinymce/tinymce');

// A theme is also required
require('tinymce/themes/silver');
// language
require('./tinymce/langs/ru');
require('tinymce/icons/default')
// Any plugins you want to use has to be imported
require('tinymce/plugins/paste');
require('tinymce/plugins/link');
require('tinymce/plugins/image');
require('tinymce/plugins/table');
// Initialize the app



tinymce.init({
    mode : 'specific_textareas',
    editor_selector : 'mceEditor',
    language: 'ru',
    plugins: 'paste image link table',
    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | image | link | table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
    image_title: true,
    // enable automatic uploads of images represented by blob or data URIs
    automatic_uploads: false,
    // URL of our upload handler (for more details check: https://www.tinymce.com/docs/configure/file-image-upload/#images_upload_url)
    // images_upload_url: 'postAcceptor.php',
    // here we add custom filepicker only to Image dialog
    file_picker_types: 'image',
    // and here's our custom image picker
    file_picker_callback: function(cb, value, meta) {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');

        // Note: In modern browsers input[type="file"] is functional without
        // even adding it to the DOM, but that might not be the case in some older
        // or quirky browsers like IE, so you might want to add it to the DOM
        // just in case, and visually hide it. And do not forget do remove it
        // once you do not need it anymore.

        input.onchange = function() {
            var file = this.files[0];

            var reader = new FileReader();
            reader.onload = function () {
                // Note: Now we need to register the blob in TinyMCEs image blob
                // registry. In the next release this part hopefully won't be
                // necessary, as we are looking to handle it internally.
                var id = 'blobid' + (new Date()).getTime();
                var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(',')[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                // call the callback and populate the Title field with the file name
                cb(blobInfo.blobUri(), { title: file.name });
            };
            reader.readAsDataURL(file);
        };

        input.click();
    }
});