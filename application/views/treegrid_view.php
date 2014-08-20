<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	
    <title>Редактор иерархического списка</title>
	
    <link rel="shortcut icon" type="image/x-icon" href="img/shortcut.png" />
    <link rel="stylesheet" type="text/css" href="css/themes/metro/easyui.css">
    <link rel="stylesheet" type="text/css" href="css/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="css/general.css">
	
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="js/treegrid-dnd.js"></script>
    <script type="text/javascript" src="js/locale/easyui-lang-ru.js"></script>
    <script type="text/javascript" src="js/general.js"></script>
</head>
<body>
<!-- Button items -->
<div style="margin: 20px auto; width: 800px;">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add'" onclick="append()">Добавить</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove'" onclick="removeIt()">Удалить</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit'" onclick="edit()">Редактировать</a>
</div>

<table id="tg" class="easyui-treegrid" title="Редактор иерархического списка" align="center" style="width:800px; height:500px;"
       data-options="
                    iconCls: 'icon-ok',
                    rownumbers: true,
                    animate: true,
                    collapsible: true,
                    fitColumns: true,
                    url: '/index.php/treegrid/select/',
                    method: 'get',
                    idField: 'id',
                    treeField: 'name',
                    showFooter: false,
                    onContextMenu: onContextMenu,
                    dnd: true,
                    onLoadSuccess: function(row, data) {					
                        $(this).treegrid('enableDnd', row?row.id:null);

                        if (data.length == 1) {
                            var id = data[0].id, parent_id = data[0]._parentId;
                            if(parent_id == null) {
                                parent_id = 0;
                            }

                            var ids = '', orders = '';
                            if(row && row.children.length == 2) {
                                ids = row.children[0].id + ',' + row.children[1].id;
                                orders = row.children[1].order + ',' + row.children[0].order;
                            }

                            $.post('/index.php/treegrid/edit/', {oper: 'dnd', ids: ids, orders: orders, id: id, parent_id: parent_id}, function(data) {
                                if(data) {
                                    // Show notification
                                    $('.success').fadeIn(500);
                                } else {
                                    // Show notification
                                    $('.error').fadeIn(500);
                                }
                            });
                        }
                    }
                    ">
    <thead>
    <tr>
        <th data-options="field:'name',width:100,editor:'text'">Название</th>
        <th data-options="field:'description',width:150,editor:'text'">Описание</th>
    </tr>
    </thead>
</table>

<!-- ContextMenu items -->
<div id="mm" class="easyui-menu" style="width: 150px;">
    <div onclick="append()" data-options="iconCls:'icon-add'">Добавить</div>
    <div onclick="removeIt()" data-options="iconCls:'icon-remove'">Удалить</div>
    <div onclick="edit()" data-options="iconCls:'icon-edit'">Редактировать</div>
    <div class="menu-sep"></div>
    <div onclick="collapse()">Свернуть</div>
    <div onclick="expand()">Развернуть</div>
</div>

<div class="success">
    Операция прошла успешно.
</div>

<div class="error">
    Ошибка операции.
</div>

<div class="info">
    Ограничение в ширине полей: Название - 1 .. 50, Описание - 1 .. 255.
</div>

<form id="edit_form" method="post" action="/index.php/treegrid/edit/">
    <table cellspacing="3" cellpadding="3" class="edit_block">
        <tr>
            <td class="label"><label>Название</label></td>
            <td class="input"><input type="text" id="name" class="input_width" name="name" value="" required=""></td>
        </tr>
        <tr>
            <td class="label"><label>Описание</label></td>
            <td class="input"><textarea name="description" id="description" class="input_width" required=""></textarea></td>
        </tr>
         <tr>
             <td class="label"><label>&nbsp;</label></td>
             <td class="input">
                 <input type="hidden" name="oper" value="edit">
                 <input type="hidden" id="id" name="id" value="">
                 <input type="submit" id="save" class="btn easyui-linkbutton" value="Сохранить">
                 <input type="button" id="cancel" class="btn easyui-linkbutton" value="Отмена">
             </td>
        </tr>
    </table>
</form>
</body>
</html>