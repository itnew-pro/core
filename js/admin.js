/**
 * Устанавливает панель
 */
function setPanel() {
	$("#panel .scroll-container").css("max-height", function () {
		return $(window).outerHeight()
		- 40
		- $("#panel .title").outerHeight()
		- $("#panel .description").outerHeight()
		- parseInt($("#panel .container").css("padding-bottom"));
	});
}

/**
 * Устанавливает субпанель
 */
function setSubpanel() {
	$("#subpanel .scroll-container").css("max-height", function () {
		return $(window).outerHeight()
		- 130
		- $("#subpanel .title").outerHeight();
	});
}

/**
 * Устанавливает окно
 */
function setWindows() {
	$(".window").each(function () {
		$(this).find(".scroll-container").css("max-height", $(window).height() - 250);
		$(this).css("margin-top", "-" + ($(this).height() / 2) + "px");
	});
}

$(document).ready(function () {

	// Кнопка панели управления
	$(".panel-tab").on("click", function () {
		var controller = $(this).data("controller");
		$.ajax({
			url: "/" + LANG + "/ajax/" + controller + "/panel/",
			success: function (data) {
				$("#panel").remove();
				$("#subpanel").remove();
				$(".panel-tab").removeClass("active");
				$("body").append(data);
				setPanel();
				$(".panel-tab-" + controller).addClass("active").parent().addClass("active");
			}
		});
		return false;
	});

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

	// Кнопка "закрыть" на окне
	$("body").on("click", ".window .close-window", function () {
		hideWindow($(this).parent().data("type"));
	});

	// Клик по затемнению
	$("body").on("click", ".window .close-window", function () {
		hideWindow($(this).data("type"));
	});

	// Изменение окна браузера
	$(window).resize(function () {
		setPanel();
		setSubpanel();
		setWindows();
	});

});