class PopupMenu {
	constructor(gridId, menuItemsUrl = null) {
		this.gridId = gridId;
        //alert(gridId);
        console.log('jQuery:', typeof jQuery, ' — ', !!window.jQuery);
        //alert("jQuery version: " + jQuery.fn.jquery);
        //alert("jQuery UI version: " + jQuery.ui.version);
        alert("jQuery contextMenu version: " + $.contextMenu.version);
		this.menuItemsUrl = menuItemsUrl;
		this.menuItems = {};
		this.attachMenu();
	}

	attachMenu() {
		if (this.menuItemsUrl) {
			$.ajax({
				url: this.menuItemsUrl,
				method: 'GET',
				dataType: 'json',
				success: (data) => {
					this.menuItems = data;
					this.createContextMenu();
				},
				error: (xhr, status, error) => {
					console.error("Menu load failed:", error);
				}
			});
		} else {
			this.menuItems = this.getDefaultItems();
			this.createContextMenu();
		}
	}

	createContextMenu() {
		const selector = `#gbox_${this.gridId} .jqgrow`;
		$.contextMenu({
			selector: selector,
			appendTo: '.ui-jqgrid',
			callback: (key, options) => {
				const row = $(`#${this.gridId}`).getGridParam("selrow");
				const gender = $(`#${this.gridId}`).jqGrid('getCell', row, 'gender');
				alert(`Row: ${row}, Gender: ${gender}, Clicked: ${key}`);
			},
			items: this.menuItems
		});
	}

	getDefaultItems() {
		return {
			"edit": { "name": "Edit", "icon": "edit" },
			"cut": { "name": "Cut", "icon": "cut" },
			"sep1": "---------",
			"quit": { "name": "Quit", "icon": "quit" },
			"nested": {
				"name": "Nested",
				"items": {
					"alpha": { "name": "Alpha" },
					"beta": { "name": "Beta" }
				}
			}
		};
	}
}
