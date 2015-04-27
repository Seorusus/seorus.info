/*
  themebiotic.com
*/

CKEDITOR.plugins.add('simplelineicons', {
    icons: 'simplelineicons',
    init: function(editor) {
        var iconPath = this.path + 'images/simplelineicons.png';
        editor.addCommand('simplelineiconsDialog', new CKEDITOR.dialogCommand('simplelineiconsDialog'));
        editor.ui.addButton('SimpleLineicons', {
            label: 'Insert Simple line icons',
            command: 'simplelineiconsDialog',
            icon: this.path + 'images/simplelineicons.png',
            toolbar: 'document'
        });
        CKEDITOR.dialog.add('simplelineiconsDialog', this.path + 'dialogs/simplelineicons.js')
    }
});
