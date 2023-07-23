$.noConflict();

jQuery(document).ready(function ($) {
	// listen for the events before we init the tree
	$('#tree').on('acitree', function(event, api, item, eventName, options) {
		var itemId = api.getId(item);
		// do some stuff on init
        if (eventName == 'added') {
            if (itemId == selectedId) {
				// then select it
				api.select(item);
			}
		}
	});
	// init the tree
	$('#tree').aciTree({
		ajax: {
			url: treeDataUrl,
		},
		selectable: true,
		itemHook: function(parent, item, itemData, level) {
            let view = '';
            let update = '';
            let child = '';
            if (itemData['view-url']) {
                view = '<a class="modal-btn" href="'+itemData['view-url']+'" title="Detail '+itemData.level+': '+itemData.code+'">Detail</a>';
            }
            if (itemData['update-url']) {
                update = ' | <a class="modal-btn" href="'+itemData['update-url']+'" title="Update '+itemData.level+': '+itemData.code+'">Update</a>';
            }
            if (itemData['child-url'] && itemData.level.toLowerCase() != 'item') {
                child = ' | <a class="modal-btn" href="'+itemData['child-url']+'" title="Childs '+itemData.level+': '+itemData.code+'">Childs</a>';
            }
            this.setLabel(item, {
                label: itemData.level + ': ' + itemData.code +'<br/>'+ itemData.label + '<br/>' + view + update + child,
            });
		}
	});
});