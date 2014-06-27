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
		$("#subpanel #Block_name").on("keyup", function() {
			$("#subpanel .error").hide();
		});
	},

	// Клик по блоку, открытие окна
	showWindow: function (data) {
		$("body").append(data);
		showWindow(this.controller);
	},

	// Открывает субпанель настроект
	updateSubpanel: function (data) {
		$("#subpanel").remove();
		$("body").append(data);
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