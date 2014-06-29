// Методы для работы с ajax
var ajaxFunctions = {
	controller: "section",
	action: "action",
	modelId: 0,

	// Кнопка панели управления
	panelTab: function (data) {
		$("#subpanel").remove();
		$(".panel-tab").removeClass("active");
		this.updatePanel(data);
		$(".panel-tab-" + this.controller).addClass("active").parent().addClass("active");

		// Кнопка "закрыть" на панели
		$("body").on("click", "#panel .close", function () {
			$("#panel").remove();
			$("#subpanel").remove();
			$(".panel-tab").removeClass("active");
			$("#panel-tabs").removeClass("active");
		});

		// Кнопка "закрыть" на субпанели
		$("body").on("click", "#subpanel .close", function () {
			$("#subpanel").remove();
		});

		// Удаляет ошибку для названия блока
		$("#subpanel #Block_name").on("keyup", function () {
			$("#subpanel .error").hide();
		});
	},

	// Клик по блоку, открытие окна
	showWindow: function (data) {
		$("body").append(data);
		windowFunctions.show(this.controller);
		setFunctions.windows();
	},

	// Показывает окно с изображениями
	showImagesWindow: function(data) {
		this.showWindow(data);
		$(".sortable").sortable({
			items: "> .image-window-item",
			stop: function() {
				var sortString = "";
				$(this).find(".image-window-item").each(function(){
					sortString += $(this).data("id") + ",";
				});
				$(this).parent().find(".imageContentIds").val(sortString);
			}
		});
		$(".window .image-file-field").on("change", function(){
			var $object = $(this);
			$object.hide();
			//$object.parent().find(".loader").show();
			$object.parent().find("i.c").hide();
			imagesFunctions.upload(this.files, 0, $object, $object.data("id"));
		});
		$("body").on("click", ".close-container", function(){
			$(this).parent().remove();
		});
	},

	// Обновляет субпанель
	updateSubpanel: function (data) {
		$("#subpanel").remove();
		$("body").append(data);
	},

	// Обновляет субпанель
	showImagesSubpanel: function (data) {
		this.updateSubpanel(data);
		imagesFunctions.showImageStyleParams();
		$("#imagesViewType").change(function() {
			imagesFunctions.showImageStyleParams();
		});
	},

	// Обновляет панель
	updatePanel: function (data) {
		$("#subpanel").remove();
		$("#panel").remove();
		$("body").append(data);
		setFunctions.panel();
	},

	// Сохраняет настройки в субпанели
	saveSettings: function (data) {
		this.updatePanel();
		$(".content-" + this.controller + "-" + this.modelId).replaceWith(data["content"]);
	},

	// Стандартные действия после сохранения окна
	saveWindow: function (data) {
		windowFunctions.hide(this.controller);
	},

	// Сохранение группы персонала
	saveStaffGroupWindow: function (data) {
		this.saveWindow();
		windowFunctions.hide("staff-group");
		$("body").append(data);
		windowFunctions.show("staff");
	},

	// Окно добавления новой записи
	addRecordsWindow: function (data) {
		if (data["errorClass"]) {
			$(".window-records-add ." + data["errorClass"]).show();
		} else {
			this.saveWindow();
			windowFunctions.hide("records-add");
			$("body").append(data["recordsForm"]);
			windowFunctions.show("records-form");
			$("body").append(data["records"]);
			windowFunctions.show("records");
		}
	},

	// Сохраняет запись
	saveRecordsFormWindow: function (data) {
		this.saveWindow();
		windowFunctions.hide("records-form");
		$("body").append(data);
		windowFunctions.show("records");
	},

	// Удаялет изображение
	empty: function (data) {
	},

	// Получает действия в случае успешного выполнения ajax
	execute: function (method, data, controller, action, modelId) {
		this.controller = controller;
		this.action = action;
		this.modelId = modelId;
		this[method](data);
	}
};

// Методы устанавливающие объекты
var setFunctions = {

	// Панель
	panel: function () {
		$("#panel .scroll-container").css("max-height", function () {
			return $(window).outerHeight()
			- 40
			- $("#panel .title").outerHeight()
			- $("#panel .description").outerHeight()
			- parseInt($("#panel .container").css("padding-bottom"));
		});
	},

	// Субпанель
	subpanel: function () {
		$("#subpanel .scroll-container").css("max-height", function () {
			return $(window).outerHeight()
			- 130
			- $("#subpanel .title").outerHeight();
		});
	},

	// Окна
	windows: function () {
		$(".window").each(function () {
			$(this).find(".scroll-container").css("max-height", $(window).height() - 250);
			$(this).css("margin-top", "-" + ($(this).height() / 2) + "px");
		});
	}
};

// Функции для работы с изображениями
var imagesFunctions = {

	// Загружает изображения
	upload: function (files, i, $object, modelId) {
		if (i < files.length) {
			var formData = new FormData();
			formData.append("itnew_models_ImagesContent[file]", files[i]);
			$.ajax({
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				url: "/" + LANG + "/ajax/" + "images/upload?id=" + modelId,
				data: formData,
				success: function(data) {
					if (data) {
						$object.parent().before(data);
					}
					i++;
					this.upload(files, i, $object, modelId);
				}
			});
		} else {
			$object.show();
			$object.parent().find(".loader").hide();
			$object.parent().find("i.c").show();
		}
	},

	// Показывает параметры изображения
	showImageStyleParams: function() {
		var $imagesViewType = $("#imagesViewType");
		var style = $imagesViewType.val();
		var $formStyle = $imagesViewType.parent().parent().find(".form-style");
		$formStyle.addClass("hide");
		if (style == 1) {
			$formStyle.removeClass("hide");
		}
	}

};

$(document).ready(function () {

	// Обрабатываются клики по элементам для ajax запросов
	$("body").on("click", ".ajax", function () {
		var method = $(this).data("function");
		var controller = $(this).data("controller");
		var action = $(this).data("action");
		var url = "/" + LANG + "/ajax/" + controller + "/" + action;
		var dataType = "text";
		var type = "GET";
		var data = {};
		var modelId = 0;
		if ($(this).data("post")) {
			type = "POST";
			data = $(this).parents("form").serialize()
		}
		if ($(this).data("json")) {
			dataType = "JSON";
		}
		if ($(this).data("modelId")) {
			modelId = $(this).data("modelId");
		}

		$.ajax({
			url: url,
			type: type,
			data: data,
			dataType: dataType,
			success: function (data) {
				ajaxFunctions.execute(method, data, controller, action, modelId);
			}
		});

		return false;
	});

	// Изменение окна браузера
	$(window).resize(function () {
		setFunctions.panel();
		setFunctions.subpanel();
		setFunctions.windows();
	});
});