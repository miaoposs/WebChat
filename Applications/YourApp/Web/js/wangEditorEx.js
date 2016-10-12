/// <reference path="wangEditor.min.js" />
/// <reference path="../../../jquery-1.10.2.min.js" />
/// <reference path="../../../plupload/plupload.full.min.js" />

/*
wangEditor集成调用
在调用前须先引用Plupload.js和wangEditor.js
*/

function StartWangEditor(component, _menuConfig, _uploadUrl, _needExpression, _expression) {
    /// <summary>wangEditor集成调用函数</summary>
    /// <param name="component" type="Object">将调用wangEditor的textarea组件</param>
    /// <param name="_menuConfig" type="Array">配置wangEditor的菜单项，若为null则构造默认菜单项</param>
    /// <param name="_uploadUrl" type="String">接收上传图片组件的Url</param>
    /// <param name="_needExpression" type="Bool">是否需要表情包</param>
    /// <param name="_expression" type="String">表情包图片的根目录</param>
    /// <returns type="Object">返回wangEditor对象</returns>
    var editor = new wangEditor(component);
    var defaultMenu = [
        'link', 'unlink', 'bold', 'underline', 'italic', 'forecolor', '|', 'img', _needExpression == true ? 'emotion' : null, '|', 'fullscreen'
    ];
    var menuConfig = _menuConfig == null ? defaultMenu : _menuConfig;
    var uploadUrl = _uploadUrl;

    editor.config.menus = menuConfig;
    editor.config.uploadImgUrl = uploadUrl;
    editor.config.emotions = _needExpression ? 
    {
        'default': {
            title: "默认",
            data: JSON.parse(getEmotionsConfig(_expression))
        }
    } : null;
    console.log(editor.config.emotions);
    editor.create();
    return editor;
}


function getEmotionsConfig(exDir)
{
    var arr = new Array();
    for (i = 1; i <= 100; i++) {
        var obj = {
            'icon': exDir + i + '.gif',
            'value': i
        };
        arr.push(obj);
    }
    return JSON.stringify(arr);
}
