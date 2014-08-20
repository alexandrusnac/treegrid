$(document).ready(function() {
    $('#edit_form').form({
        success: function(data) {
            hide_notifications();
            
            if(data > 0) {
                // Show notification
                $('.success').fadeIn(500);
                $('.edit_block').fadeOut(50);
            } else {
                // Show notification
                $('.error').fadeIn(500);
                // Show notification
                $('.info').fadeIn(500);
            }
            $('#tg').treegrid({url: '/index.php/treegrid/select/'});
        },
        error: function(xhr, str) {
            hide_notifications();
            
            // Show notification
            $('.error').fadeIn(500);
        }
    });
    
    $('#cancel').click(function() {
        $('.edit_block').fadeOut(50);
    });
});

// Редактирование
function edit() {
    hide_notifications();

    var t = $('#tg');		
    var row = t.treegrid('getSelected');
    if(row) {
        $('#id').val(row.id);
        $('#name').val(row.name);
        $('#description').val(row.description);

        $('.edit_block').fadeIn(500);
    } else {
        // Show notification
        $('.error').fadeIn(500);
    }
}

function onContextMenu(e,row) {
    e.preventDefault();
    $(this).treegrid('select', row.id);
    $('#mm').menu('show',{
            left: e.pageX,
            top: e.pageY
    });
}

// Добавление новой записи
function append() {
    hide_notifications();

    var node = $('#tg').treegrid('getSelected');

    if (node) {
        parent_id = node.id;
    } else {
        parent_id = 0;
    }

    $.post("/index.php/treegrid/edit/", {oper: "add", parent_id: parent_id, name: "Новый элемент", description: "Описание элемента"}, function(row_id) {
        if (row_id) {
            $('#tg').treegrid('append',{
                parent: parent_id,
                data: [{
                        id: row_id,
                        name: "Новый элемент",
                        description: "Описание элемента",
                        order: row_id
                }]
            })

            // Show notification
            $('.success').fadeIn(500);
        } else {
            // Show notification
            $('.error').fadeIn(500);
        }
    });
}

// Удаление записи
function removeIt() {
    hide_notifications();

    var node = $('#tg').treegrid('getSelected');
    if (node) {
        $.post("/index.php/treegrid/edit/", {oper: "del", id: node.id}, function(data) {
            if (data) {
                $('#tg').treegrid('remove', node.id);

                // Show notification
                $('.success').fadeIn(500);
            } else {
                // Show notification
                $('.error').fadeIn(500);
            }
        });
    } else {
        // Show notification
        $('.error').fadeIn(500);
    }
}

function hide_notifications() {
    // Show notification
    $('.success').fadeOut(50);

    // Show notification
    $('.error').fadeOut(50);

    // Show notification
    $('.info').fadeOut(50);
}

// Свернуть узел
function collapse() {
    var t = $('#tg');
    var node = t.treegrid('getSelected');
    if (node) {
            t.treegrid('collapse', node.id);
    }
}

// Развернуть узел
function expand() {
    var t = $('#tg');
    var node = t.treegrid('getSelected');
    if (node) {
            t.treegrid('expand', node.id);
    }
}