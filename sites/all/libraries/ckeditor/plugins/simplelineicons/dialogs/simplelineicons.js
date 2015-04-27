/**
 * Themebiotic.com
 */

var glyphicon = '';

var icons = [
'icon-action-redo',
'icon-action-undo',
'icon-anchor',
'icon-arrow-down',
'icon-arrow-left',
'icon-arrow-right',
'icon-arrow-up',
'icon-badge',
'icon-bag',
'icon-ban',
'icon-bar-chart',
'icon-basket',
'icon-basket-loaded',
'icon-bell',
'icon-book-open',
'icon-briefcase',
'icon-bubble',
'icon-bubbles',
'icon-bulb',
'icon-calculator',
'icon-calendar',
'icon-call-end',
'icon-call-in',
'icon-call-out',
'icon-camcorder',
'icon-camera',
'icon-check',
'icon-chemistry',
'icon-clock',
'icon-close',
'icon-cloud-download',
'icon-cloud-upload',
'icon-compass',
'icon-control-end',
'icon-control-forward',
'icon-control-pause',
'icon-control-play',
'icon-control-rewind',
'icon-control-start',
'icon-credit-card',
'icon-crop',
'icon-cup',
'icon-cursor',
'icon-cursor-move',
'icon-diamond',
'icon-direction',
'icon-directions',
'icon-disc',
'icon-dislike',
'icon-doc',
'icon-docs',
'icon-drawer',
'icon-drop',
'icon-earphones',
'icon-earphones-alt',
'icon-emoticon-smile',
'icon-energy',
'icon-envelope',
'icon-envelope-letter',
'icon-envelope-open',
'icon-equalizer',
'icon-eye',
'icon-eyeglasses',
'icon-feed',
'icon-film',
'icon-fire',
'icon-flag',
'icon-folder',
'icon-folder-alt',
'icon-frame',
'icon-game-controller',
'icon-ghost',
'icon-globe',
'icon-globe-alt',
'icon-graduation',
'icon-graph',
'icon-grid',
'icon-handbag',
'icon-heart',
'icon-home',
'icon-hourglass',
'icon-info',
'icon-key',
'icon-layers',
'icon-like',
'icon-link',
'icon-list',
'icon-lock',
'icon-lock-open',
'icon-login',
'icon-logout',
'icon-loop',
'icon-magic-wand',
'icon-magnet',
'icon-magnifier',
'icon-magnifier-add',
'icon-magnifier-remove',
'icon-map',
'icon-microphone',
'icon-mouse',
'icon-moustache',
'icon-music-tone',
'icon-music-tone-alt',
'icon-note',
'icon-notebook',
'icon-paper-clip',
'icon-paper-plane',
'icon-pencil',
'icon-picture',
'icon-pie-chart',
'icon-pin',
'icon-plane',
'icon-playlist',
'icon-plus',
'icon-pointer',
'icon-power',
'icon-present',
'icon-printer',
'icon-puzzle',
'icon-question',
'icon-refresh',
'icon-reload',
'icon-rocket',
'icon-screen-desktop',
'icon-screen-smartphone',
'icon-screen-tablet',
'icon-settings',
'icon-share',
'icon-share-alt',
'icon-shield',
'icon-shuffle',
'icon-size-actual',
'icon-size-fullscreen',
'icon-social-dribbble',
'icon-social-dropbox',
'icon-social-facebook',
'icon-social-tumblr',
'icon-social-twitter',
'icon-social-youtube',
'icon-speech',
'icon-speedometer',
'icon-star',
'icon-support',
'icon-symbol-female',
'icon-symbol-male',
'icon-tag',
'icon-target',
'icon-trash',
'icon-trophy',
'icon-umbrella',
'icon-user',
'icon-user-female',
'icon-user-follow',
'icon-user-following',
'icon-user-unfollow',
'icon-users',
'icon-vector',
'icon-volume-1',
'icon-volume-2',
'icon-volume-off',
'icon-wallet',
'icon-wrench'
];
var iconHTML   = "",
    title = "";
icons.forEach(function(element){
  title = element.replace("icon-", "");
  title = title.replace("-"," ");
  iconHTML += '<a href="#" onclick="klik(this);return false;" title="'+element+'"> \
    <span class="'+element+'"></span> '+toTitleCase(title)+' \
  </a>';

});

function toTitleCase(str) {
  return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}


function klik(el) {
    glyphicon = el.getAttribute('title');
    ckDialog = window.CKEDITOR.dialog.getCurrent();
    ckOk = ckDialog._.buttons['ok'];
    ckOk.click()
};

function searchIcon(val) {
    var aydi = document.getElementById('simplelineicons');
    var klases = aydi.getElementsByTagName('a');
    for (var i = 0, len = klases.length, klas, klasNeym; i < len; i++) {
        klas = klases[i];
        klasNeym = klas.getAttribute('title');
        if (klasNeym && klasNeym.indexOf(val) >= 0) {
            klas.style.display = 'block'
        } else {
            klas.style.display = 'none'
        }
    }
};

function setSpanColor(color) {
    el = document.getElementById('simplelineicons');
    el = el.getElementsByTagName('span');
    for (i = 0; i < el.length; i++) {
        el[i].setAttribute('style', 'color:' + color)
    }
};
CKEDITOR.dialog.add('simplelineiconsDialog', function(editor) {
    return {
        title: 'Insert Simple line icons',
        minWidth: 600,
        minHeight: 400,
        resizable: false,
        contents: [{
            id: 'insertSimplelineicon',
            label: 'insertSimplelineicon',
            elements: [{
                type: 'hbox',
                widths: ['50%', '50%'],
                children: [{
                    type: 'hbox',
                    widths: ['75%', '25%'],
                    children: [{
                        type: 'text',
                        id: 'colorChooser',
                        className: 'colorChooser',
                        label: 'Color',
                        onKeyUp: function(e) {
                            setSpanColor(e.sender.$.value)
                        }
                    }, {
                        type: 'button',
                        label: 'Select',
                        style: 'margin-top:1.35em',
                        onClick: function() {
                            editor.getColorFromDialog(function(color) {
                                document.getElementsByClassName('colorChooser')[0].getElementsByTagName('input')[0].value = color;
                                setSpanColor(color)
                            }, this)
                        }
                    }]
                }, {
                    type: 'text',
                    id: 'size',
                    className: 'size',
                    label: 'Size'
                }]
            }, {
                type: 'text',
                id: 'simplelineiconsSearch',
                className: 'simplelineiconsSearch cke_dialog_ui_input_text',
                label: 'Search',
                onKeyUp: function(e) {
                    searchIcon(e.sender.$.value)
                }
            }, {
                type: 'html',
                html: '<link rel="stylesheet" type="text/css" href="' + CKEDITOR.basePath + 'plugins/simplelineicons/simplelineicons.css" />'
            }, {
                type: 'html',
                html: '<div id="simplelineicons">'+iconHTML+'</div>',
            }, ]
        }],
        onOk: function() {
            glyphs = document.getElementById('simplelineicons');
            glyphs = glyphs.getElementsByTagName('a');
            for (i = 0; i < glyphs.length; i++) {
                glyphs[i].style.display = '';
                glyphs[i].getElementsByTagName('span')[0].style.color = ''
            }
            var dialog = this,
                glyphicons = this.element;
            this.commitContent(glyphicons);
            istayl = '';
            istayl += dialog.getValueOf('insertSimplelineicon', 'colorChooser') != '' ? 'color:' + dialog.getValueOf('insertSimplelineicon', 'colorChooser') + ';' : '';
            istayl += dialog.getValueOf('insertSimplelineicon', 'size') != '' ? 'font-size:' + parseInt(dialog.getValueOf('insertSimplelineicon', 'size')) + 'px;' : '';
            var glyphicons = editor.document.createElement('span');
            glyphicons.setAttribute('class', glyphicon);
            istayl != '' ? glyphicons.setAttribute('style', istayl) : '';
            editor.insertElement(glyphicons)
        }
    }
});
