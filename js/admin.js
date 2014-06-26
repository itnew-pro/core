// Методы для работы с ajax
var ajaxFunctions = {
	controller: "section",
	action: "action",

	// Кнопка панели управления
	panelTab: function (data) {
		$("#panel").remove();
		$("#subpanel").remove();
		$(".panel-tab").removeClass("active");
		$("body").append(data);
		setPanel();
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
	},

	execute: function (method, data, controller, action) {
		this.controller = controller;
		this.action = action;
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
	}

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
	$(".ajax").on("click", function () {
		var method = $(this).data("function");
		var controller = $(this).data("controller");
		var action = $(this).data("action");
		var url = "/" + LANG + "/ajax/" + controller + "/" + action + "/";

		$.ajax({
			url: url,
			success: function (data) {
				ajaxFunctions.execute(method, data, controller, action);
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