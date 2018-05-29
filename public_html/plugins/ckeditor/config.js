/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */


CKEDITOR.editorConfig = function( config ) {
    config.language = "ko";
    config.height = 350;
    config.skin = typeof is_admin && is_admin ? 'moono-dark': "moono-lisa";
    config.allowedContent = true;
    config.extraPlugins= 'autoembed,image2,uploadimage,uploadfile,youtube';
    config.removePlugins= 'image';
    config.removeDialogTabs = 'image:advanced;link:advanced';
    config.toolbar = [
        ['Format','Font','FontSize'],
        ['Image','Youtube','Link','-','Table','-','Smiley'],
        ['Print','Maximize'],
        ['Source'],
        '/',
        ['Bold','Italic','Underline','Strike','-','TextColor','BGColor','-','Find','Replace','-','Outdent','Indent'],
        ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']
    ];
    config.youtube_width = '640';
    config.youtube_height = '480';
    config.youtube_responsive = false;
    config.youtube_related = false;
    config.youtube_older = false;
    config.youtube_privacy = false;
    config.youtube_autoplay = false;
    config.youtube_controls = true;
    config.font_defaultLabel = "나눔고딕";
    config.font_names = "굴림;돋움;바탕;궁서;굴림체;돋움체;바탕체;궁서체;나눔고딕;나눔명조;"+
        'Arial;Comic Sans MS;Courier New;Lucida Sans Unicode;monospace;sans-serif;serif;Tahoma;Times New Roman;Verdana';
    config.fontSize_defaultLabel = "12px";
    config.fontSize_sizes = "7px/9px;9px/11px;11px/12px;12px/13px;13px/15px;15px/19px;18px/24px;24px/32px;32px/48px;";
    config.enterMode = CKEDITOR.ENTER_DIV;
    config.shiftEnterMode = CKEDITOR.ENTER_DIV;
    config.uploadUrl = base_url + "/ajax/editor/ckeditor/json";
    config.filebrowserUploadUrl = base_url + "/ajax/editor/ckeditor/";
    config.keystrokes=[
        // Formatting
        [ CKEDITOR.CTRL + 81 /*Q*/, 'blockquote' ],
        [ CKEDITOR.CTRL + 66 /*B*/, 'bold' ],
        [ CKEDITOR.CTRL + 56 /*8*/, 'bulletedlist' ],
        [ CKEDITOR.CTRL + CKEDITOR.SHIFT + 56 /*8*/, 'bulletedListStyle' ],
        [ CKEDITOR.CTRL + 77 /*M*/, 'indent' ],
        [ CKEDITOR.CTRL + CKEDITOR.SHIFT + 77 /*M*/, 'outdent' ],
        [ CKEDITOR.CTRL + 73 /*I*/, 'italic' ],
        [ CKEDITOR.CTRL + 74 /*J*/, 'justifyblock' ],
        [ CKEDITOR.CTRL + 69 /*E*/, 'justifycenter' ],
        [ CKEDITOR.CTRL + 76 /*L*/, 'justifyleft' ],
        [ CKEDITOR.CTRL + 82 /*R*/, 'justifyright' ],
        [ CKEDITOR.CTRL + 55 /*7*/, 'numberedlist' ],
        [ CKEDITOR.CTRL + CKEDITOR.SHIFT + 55 /*7*/, 'numberedListStyle' ],
        [ CKEDITOR.CTRL + 89 /*Y*/, 'redo' ],
        [ CKEDITOR.CTRL + 32 /*SPACE*/, 'removeFormat' ],
        [ CKEDITOR.CTRL + 65 /*A*/, 'selectall' ],
        [ CKEDITOR.CTRL + CKEDITOR.SHIFT + 88 /*X*/, 'strike' ],
        [ CKEDITOR.CTRL + 188 /*COMMA*/, 'subscript' ],
        [ CKEDITOR.CTRL + 190 /*PERIOD*/, 'superscript' ],
        [ CKEDITOR.CTRL + 85 /*U*/, 'underline' ],
        [ CKEDITOR.CTRL + 90 /*Z*/, 'undo' ],
        // Insert
        [ CKEDITOR.ALT + 65 /*A*/, 'anchor' ],
        [ CKEDITOR.ALT + 68 /*D*/, 'creatediv' ],
        [ CKEDITOR.ALT + CKEDITOR.SHIFT + 68 /*D*/, 'editdiv' ],
        [ CKEDITOR.ALT + 70 /*F*/, 'flash' ],
        [ CKEDITOR.ALT + 72 /*H*/, 'horizontalrule' ],
        [ CKEDITOR.CTRL + 57 /*9*/, 'image' ],
        [ CKEDITOR.ALT + 73 /*I*/, 'image' ],
        [ CKEDITOR.CTRL + 75 /*K*/, 'link' ],
        [ CKEDITOR.ALT + 76 /*L*/, 'link' ],
        [ CKEDITOR.CTRL + CKEDITOR.SHIFT + 75 /*K*/, 'unlink' ],
        [ CKEDITOR.ALT + CKEDITOR.SHIFT + 76 /*L*/, 'unlink' ],
        [ CKEDITOR.CTRL + 13 /*ENTER*/, 'pagebreak' ],
        [ CKEDITOR.ALT + 13 /*ENTER*/, 'pagebreak' ],
        [ CKEDITOR.ALT + 69 /*E*/, 'smiley' ],
        [ CKEDITOR.ALT + 67 /*C*/, 'specialchar' ],
        [ CKEDITOR.ALT + 84 /*T*/, 'table' ],
        [ CKEDITOR.ALT + 79 /*O*/, 'templates' ],
        // Other - dialogs, views, etc.
        [ 112 /*F1*/, 'about' ],
        [ CKEDITOR.ALT + 48 /*ZERO*/, 'blur' ],
        [ CKEDITOR.ALT + 8 /*Backspace*/, 'blur' ],
        [ CKEDITOR.CTRL + 87 /*W*/, 'blur' ],
        [ CKEDITOR.ALT + 51 /*#3*/, 'colordialog' ],
        [ CKEDITOR.ALT + 77 /*M*/, 'contextMenu' ],
        [ CKEDITOR.ALT + 122 /*F11*/, 'elementsPathFocus' ],
        [ CKEDITOR.CTRL + CKEDITOR.SHIFT + 70 /*F*/, 'find' ],
        [ CKEDITOR.ALT + 88 /*X*/, 'maximize' ],
        [ CKEDITOR.CTRL + 113 /*F2*/, 'preview' ],
        [ CKEDITOR.CTRL + CKEDITOR.SHIFT + 80 /*P*/, 'print' ],
        [ CKEDITOR.CTRL + 72 /*H*/, 'replace' ],
        [ CKEDITOR.ALT + 83 /*S*/, 'scaytcheck' ],
        [ CKEDITOR.ALT + 66 /*B*/, 'showblocks' ],
        [ CKEDITOR.ALT + CKEDITOR.SHIFT + 84 /*T*/, 'showborders' ],
        [ CKEDITOR.ALT + 90 /*Z*/, 'source' ],
        [ CKEDITOR.ALT + 48 /*ZERO*/, 'toolbarCollapse' ],
        [ CKEDITOR.ALT + 121 /*F10*/, 'toolbarFocus' ]
    ];
};