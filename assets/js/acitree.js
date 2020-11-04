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
			// set a custom item label to show the branch level
            if (itemData.level.toLowerCase() == 'item') {
				this.setLabel(item, {
					label: itemData.level + ': ' + itemData.code +'<br/>'+ itemData.label + '<br/><a class="modal-btn" href="'+itemData['view-url']+'" title="Info '+itemData.level+': '+itemData.code+'">Info</a> | <a href="'+itemData['update-url']+'" title="Update '+itemData.level+': '+itemData.code+'">Update</a>',
				});
			} else {
				this.setLabel(item, {
					label: itemData.level + ': ' + itemData.code +'<br/>'+ itemData.label + '<br/><a class="modal-btn" href="'+itemData['view-url']+'" title="Info '+itemData.level+': '+itemData.code+'">Info</a> | <a href="'+itemData['update-url']+'" title="Update '+itemData.level+': '+itemData.code+'">Update</a> | <a href="'+itemData['child-url']+'" title="Childs '+itemData.level+': '+itemData.code+'">Childs</a>',
				});
			}
		}
	});
});