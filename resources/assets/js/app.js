
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('jqtree');
require('./jqTreeContextMenu');

const $tree = $('#tree');

const deleteNode = (node)=>{

    $.ajax({
        url: `/ajax/categories/${node.id}`,
        type: 'DELETE'
    });

    $('#tree').tree('removeNode',node);
};

$tree.tree({
    dragAndDrop: true,
    autoOpen: true,
    usecontextmenu: true
});

$tree.jqTreeContextMenu($('#myMenu'), {
    "edit": editNode,
    "delete": deleteNode,
    "add": addNode
});


function editNode(node) {
    location.href="/?" + node.id;
    // node.name = "foobar";
    // $('#tree').tree('updateNode',node,node.getData());
}
function addNode(node) {
    $('#tree').tree('addNodeAfter',{name: 'new_node'},node);
 }
