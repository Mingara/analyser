	const einst = new Map();
	var level = 1;
	einst.set('level', level);

	function ReadQuery(sender,arg)
	{
		// Einstellungen: Selected Query //////////////////////////////////////////////////////////////////////////////////////////////
		level = 0;
		einst.set('queryID', arg.ItemId);
		//alert(einst.get("queryID"));

		var modal = document.getElementById("formSettings");
		modal.style.display = "block";
//		ksm.getItem(arg.ItemId).unselect();
	}
	// Register for OnSelect event
	ksm.registerEvent("OnSelect",ReadQuery);

	function startReport_ORIGINAL() {
		document.getElementById("loading").style.visibility = "visible";

		var un = document.getElementById("lun");
		var d = new Date();
		var ident = un.innerHTML + "_" + d.getFullYear() + ("0" + (d.getMonth() + 1)).slice(-2) + ("0" + d.getDate()).slice(-2) + ("0" + d.getHours() ).slice(-2) + ("0" + d.getMinutes()).slice(-2) + ("0" + d.getSeconds()).slice(-2);

		einst.set('ident', ident);
		
		// Einstellungen: Selected Companies //////////////////////////////////////////////////////////////////////////////////////////////
		var selCompanies = "";
		var classCompany = document.getElementsByClassName("company");
		for(var index=0; index < classCompany.length; index++) {
			var companyID = classCompany[index].getAttribute("id");
			if (document.getElementById(companyID).checked) {
				companyID = companyID.substring(companyID.lastIndexOf("_") + 1);
				selCompanies = selCompanies + "," + companyID;
			}
		}
		selCompanies = selCompanies.substring(1);
		einst.set('selCompanies', selCompanies);
		//alert(einst.get("selCompanies"));

		// Einstellungen: Select Options //////////////////////////////////////////////////////////////////////////////////////////////
		const arrSelect = ["selMonth", "selYear", "priceYear"];
		for (var i = 0; i < arrSelect.length; i++) {
			var mySelect = document.getElementById(arrSelect[i]);
			for (var j = 0; j < mySelect.options.length; j++) {
				if (mySelect.options[j].selected) {
					einst.set(arrSelect[i], mySelect.options[j].value);
					break;
				}
			}
			//alert(einst.get(arrSelect[i]));
		}

		// Einstellungen: Radio Groups //////////////////////////////////////////////////////////////////////////////////////////////
		var arrRadioGroup = ["timeMode", "zk", "ic", "tp", "besch"];
		for (var i = 0; i < arrRadioGroup.length; i++) {
			var myRadioButton = document.getElementsByName(arrRadioGroup[i]);
			for (var j = 0; j < myRadioButton.length; j++) {
				einst.set(myRadioButton[j].id, (myRadioButton[j].checked ? "1" : "0"));
				//alert(myRadioButton[j].id + " = " + einst.get(myRadioButton[j].id))
			}
		}

		let jsonEinst = JSON.stringify(Object.fromEntries(einst));
		//koolajax.callback(createReport(jsonEinst),onDone);

		



		
		
		//koolajax.callback(setArrTabSet(newTabIndex, jsonEinst));


		




		createNewTab();
		$('#tabs-' + newTabIndex).load('?grid_id=list' + newTabIndex + '&oper=ajaxload&ident=' + newTabIndex);
		newTabIndex++;

		var modal = document.getElementById("formSettings");
		modal.style.display = "none";
		document.getElementById("loading").style.visibility = "hidden";
	}


	function getSettings() { // startReport_ORIGINAL
		document.getElementById("loading").style.visibility = "visible";

		var un = document.getElementById("lun");
		var d = new Date();
		var ident = un.innerHTML + "_" + d.getFullYear() + ("0" + (d.getMonth() + 1)).slice(-2) + ("0" + d.getDate()).slice(-2) + ("0" + d.getHours() ).slice(-2) + ("0" + d.getMinutes()).slice(-2) + ("0" + d.getSeconds()).slice(-2);

		einst.set('ident', ident);
		
		// Einstellungen: Selected Companies //////////////////////////////////////////////////////////////////////////////////////////////
		var selCompanies = "";
		var classCompany = document.getElementsByClassName("company");
		for(var index=0; index < classCompany.length; index++) {
			var companyID = classCompany[index].getAttribute("id");
			if (document.getElementById(companyID).checked) {
				companyID = companyID.substring(companyID.lastIndexOf("_") + 1);
				selCompanies = selCompanies + "," + companyID;
			}
		}
		selCompanies = selCompanies.substring(1);
		einst.set('selCompanies', selCompanies);
		//alert(einst.get("selCompanies"));

		// Einstellungen: Select Options //////////////////////////////////////////////////////////////////////////////////////////////
		const arrSelect = ["selMonth", "selYear", "priceYear"];
		for (var i = 0; i < arrSelect.length; i++) {
			var mySelect = document.getElementById(arrSelect[i]);
			for (var j = 0; j < mySelect.options.length; j++) {
				if (mySelect.options[j].selected) {
					einst.set(arrSelect[i], mySelect.options[j].value);
					break;
				}
			}
			//alert(einst.get(arrSelect[i]));
		}

		// Einstellungen: Radio Groups //////////////////////////////////////////////////////////////////////////////////////////////
		var arrRadioGroup = ["timeMode", "zk", "ic", "tp", "besch"];
		for (var i = 0; i < arrRadioGroup.length; i++) {
			var myRadioButton = document.getElementsByName(arrRadioGroup[i]);
			for (var j = 0; j < myRadioButton.length; j++) {
				einst.set(myRadioButton[j].id, (myRadioButton[j].checked ? "1" : "0"));
				//alert(myRadioButton[j].id + " = " + einst.get(myRadioButton[j].id))
			}
		}

		let jsonEinst = JSON.stringify(Object.fromEntries(einst));
		//koolajax.callback(createReport(jsonEinst),onDone);

		



		
		
		//koolajax.callback(setArrTabSet(newTabIndex, jsonEinst));


		


//alert("begin");
		addCollTab();
//alert("end");
		createNewTab();
		$('#tabs-' + newTabIndex).load('?grid_id=list' + newTabIndex + '&oper=ajaxload&ident=' + newTabIndex);
		newTabIndex++;

		var modal = document.getElementById("formSettings");
		modal.style.display = "none";
		document.getElementById("loading").style.visibility = "hidden";
	}

	// TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS
	function remove(_id)
	{
		kts.removeTab(_id);
	}
	function m_over(_this)
	{
		_this.src='img/closeover.gif';
	}
	function m_out(_this)
	{
		_this.src='img/closenormal.gif';
	}		

	// COMPANIES COMPANIES COMPANIES COMPANIES COMPANIES COMPANIES COMPANIES COMPANIES COMPANIES COMPANIES
	function toogle(nodeId)
	{
		if (nodeId.startsWith("company")) {
			var parentid = document.getElementById(nodeId).getAttribute("name");
			document.getElementById(parentid).checked = false;
			var classCompany = document.querySelectorAll("[name='" + parentid + "']")
			for(var index=0; index < classCompany.length; index++) {
				var companyID = classCompany[index].getAttribute("id");
				if (document.getElementById(companyID).checked)
					document.getElementById(parentid).checked = true;
			}
			toogle("root");
		} else if (nodeId.startsWith("area")) {
			var stateArea = document.getElementById(nodeId).checked;
			var classCompany = document.querySelectorAll("[name='" + nodeId + "']")
			for(var index=0; index < classCompany.length; index++) {
				var companyID = classCompany[index].getAttribute("id");
				document.getElementById(companyID).checked = false;
				if (stateArea)
					document.getElementById(companyID).checked = true;
				toogle("root");
			}
		} else if (nodeId.startsWith("treeview")) {
			// click on root: on or off
			var stateRoot = document.getElementById(nodeId).checked;
			var classArea = document.getElementsByClassName("area");
			for(var index=0; index < classArea.length; index++) {
				var areaID = classArea[index].getAttribute("id");
				document.getElementById(areaID).checked = false;
				if (stateRoot)
					document.getElementById(areaID).checked = true;
				toogle(areaID);
			}
		} else if (nodeId.startsWith("root")) {
			var classArea = document.getElementsByClassName("area");
			document.getElementById("treeview.root").checked = false;
			for(var index=0; index < classArea.length; index++) {
				var areaID = classArea[index].getAttribute("id");
				if (document.getElementById(areaID).checked)
					document.getElementById("treeview.root").checked = true;
			}
		}
	}

	//$(document).ready(function() {
   	$("#tabs").tabs({
      	activate: TabSelected
    	});

   	// Идентификатор активного таба
		var newTabIndex = 1;
   	var tabActive = "tabs-" + newTabIndex;

		function createNewTab() {
			var newTabId = "tabs-" + newTabIndex;
			var queryCaption = "Custom Caption " + newTabIndex;

			var newDiv = $('<div id="' + newTabId + '" class="tab-content">Content for Tab ' + newTabIndex + '</div>');

			$("body").append(newDiv);

			$("#tabs ul").append('<li><a href="#' + newTabId + '" data-caption="' + queryCaption + '">Tab ' + newTabIndex + '<button class="close-tab" title="Close Tab">-</button></a></li>');

			$("#tabs").append(newDiv);

			$("#tabs").tabs("refresh");

			// Устанавливаем новый таб активным
			$('a[href="#' + newTabId + '"]').click();
		}

		$("#tabs").on("click", ".close-tab", function() {
			var panelId = $(this).closest("li").remove().attr("aria-controls");
			$("#" + panelId).remove();

			if (panelId === tabActive) {
				var nextTab = $('a[href="#' + tabActive + '"]').closest("li").next().find("a");
				var prevTab = $('a[href="#' + tabActive + '"]').closest("li").prev().find("a");

				if (prevTab.length > 0) {
						prevTab.click();
				} else if (nextTab.length > 0) {
						nextTab.click();
				}
			} else {
				$('a[href="#' + tabActive + '"]').click();
			}
			$("#tabs").tabs("refresh");
		});
		
		$("#tabs").on("mouseenter", "a", function() {
			var caption = $(this).data("caption");
			if (caption) {
				$(this).attr("title", caption);
			}
		});

		// Функция вызывается при смене активной вкладки
		function TabSelected(event, ui) {
			if (!$(event.originalEvent.target).hasClass("close-tab")) {
				// Событие не вызвано кликом на кнопке закрытия
				// Обновляем переменную tabActive только если это не кнопка закрытия
				tabActive = ui.newPanel.attr("id");
			}
		}
	//});

	function onDone(s)
	{
	}

//$(function () {
  let tabCount = 0;

  $("#tabsColl").tabs();


	function addCollTab() {
		//alert("1");
		tabCount++;
		//alert("2");
		const newTab = `<li><a href="#tab-${tabCount}">Collection ${tabCount}</a><span class="close-tab">&#10754;</span></li>`;
		//alert("3");
		$("#tabsColl ul").append(newTab);
		$("#tabsColl").tabs("refresh"); // Обновление вкладок после добавления
		checkOverflow();
	}

/*
  $("#add-tab").on("click", function () {
    tabCount++;
    const newTab = `<li><a href="#tab-${tabCount}">Collection ${tabCount}</a><span class="close-tab">&#10754;</span></li>`;
    $("#tabsColl ul").append(newTab);
    $("#tabsColl").tabs("refresh"); // Обновление вкладок после добавления
    checkOverflow();
  });
*/

  // Обработчик клика на табы
  $(document).on("click", "#tabsColl ul li a", function (event) {
    const tabId = $(this).attr("href");
    // Устанавливаем активную вкладку
    const index = $(this).parent().index(); // Определяем индекс вкладки
    $("#tabsColl").tabs("option", "active", index); // Устанавливаем активную вкладку
    //alert(`Событие: click на таб, ID: ${tabId}`);
  });

  $(document).on("click", ".close-tab", function (event) {
    event.preventDefault(); // Предотвращает переход по ссылке
    const tabId = $(this).prev("a").attr("href");
    //alert(`Событие: click на кнопку закрытия, ID: ${tabId}`);
    $(this).closest("li").remove(); // Удаление таба
    //$(tabId).remove(); // Удаление содержимого таба // ЗДЕСЬ НАДО УДАЛИТЬ ВСЕ ТАБЫ ОТ KoolPHP.net
    $("#tabsColl").tabs("refresh");
    checkOverflow();
  });

  function checkOverflow() {
    const wrapper = $("#tabsColl-wrapper")[0];
    const tabs = $("#tabsColl")[0];
    if (tabs.scrollWidth > wrapper.clientWidth) {
      $("#scroll-left, #scroll-right").show();
    } else {
      $("#scroll-left, #scroll-right").hide();
    }
  }

  $("#scroll-left").on("click", function () {
    $("#tabsColl").animate({ scrollLeft: "-=100" }, 200);
  });

  $("#scroll-right").on("click", function () {
    $("#tabsColl").animate({ scrollLeft: "+=100" }, 200);
  });

  $(window).on("resize", checkOverflow);
