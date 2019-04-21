$.noConflict();

jQuery(document).ready(function ($) {
	// listen for the events before we init the tree
	$('#tree').on('acitree', function(event, api, item, eventName, options) {
		var itemId = api.getId(item);
		// do some stuff on init
		if (eventName == 'added') {
			if(itemId == selectedId) {
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
			this.setLabel(item, {
				label: itemData.level + ': ' + itemData.code + itemData.label + '<a class="modal-btn" href="'+itemData['view-url']+'" title="Detail '+itemData.level+': '+itemData.code+'">Detail</a> | <a href="'+itemData['update-url']+'" title="Update '+itemData.level+': '+itemData.code+'">Update</a>',
			});
		}
	});
});