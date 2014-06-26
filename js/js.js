/**
 * Отображает всплывающее окно
 *
 * @param {string} name название окна
 */
function showWindow(name) {
	$(".window-" + name).animate({opacity: 1}, 500);
	$(".overlay-" + name).animate({opacity: 0.7}, 500);
}

/**
 * Скрывает всплывающее окно
 *
 * @param {string} name название окна
 */
function hideWindow(name) {
	$(".overlay-" + name).animate({opacity: 0}, 500, function () {
		$(this).remove();
	});
	$(".window-" + name).animate({opacity: 0}, 500, function () {
		$(this).remove();
	});
}

/**
 * Преобразует строку в транслит
 */
String.prototype.translit = (function () {
	var L = {
			"А": "a",
			"а": "a",
			"Б": "b",
			"б": "b",
			"В": "v",
			"в": "v",
			"Г": "g",
			"г": "g",
			"Д": "d",
			"д": "d",
			"Е": "e",
			"е": "e",
			"Ё": "yo",
			"ё": "yo",
			"Ж": "zh",
			"ж": "zh",
			"З": "z",
			"з": "z",
			"И": "i",
			"и": "i",
			"Й": "y",
			"й": "y",
			"К": "k",
			"к": "k",
			"Л": "l",
			"л": "l",
			"М": "m",
			"м": "m",
			"Н": "n",
			"н": "n",
			"О": "o",
			"о": "o",
			"П": "p",
			"п": "p",
			"Р": "r",
			"р": "r",
			"С": "s",
			"с": "s",
			"Т": "t",
			"т": "t",
			"У": "u",
			"у": "u",
			"Ф": "f",
			"ф": "f",
			"Х": "kh",
			"х": "kh",
			"Ц": "ts",
			"ц": "ts",
			"Ч": "ch",
			"ч": "ch",
			"Ш": "sh",
			"ш": "sh",
			"Щ": "sch",
			"щ": "sch",
			"Ъ": "",
			"ъ": "",
			"Ы": "y",
			"ы": "y",
			"Ь": "",
			"ь": "",
			"Э": "e",
			"э": "e",
			"Ю": "yu",
			"ю": "yu",
			"Я": "ya",
			"я": "ya",
			" ": "-",
			".": ""
		},
		r = "",
		k;
	for (k in L) r += k;
	r = new RegExp("[" + r + "]", "g");
	k = function (a) {
		return a in L ? L[a] : "";
	};
	return function () {
		return this.replace(r, k).replace(/[^a-zA-Z0-9_-]/g, "").replace(/_+/g, "_");
	};
})();

/**
 * Отображает ошибки AJAX
 *
 * @param {jqXHR} xhr XMLHTTPRequest
 */
function showExceptionError(xhr) {
	var str = xhr.responseText.match(/<h2>[^<>]*<\/h2>/gi)[0];
	str = str.replace(/<h2>/i, "");
	str = str.replace(/<\/h2>/i, "");
	$(".loader").hide();

	alert(str);
}

/**
 * Производит настройки AJAX
 */
$.ajaxSetup({
	beforeSend: function () {
		$("#loader").show();
	},
	complete: function (data) {
		$("#loader").hide();
	},
	error: function (xhr) {
		showExceptionError(xhr);
	}
});

$(document).ready(function () {

	// Кнопка входа
	$("#login-button").on("click", function () {
		$.ajax({
			url: "/" + LANG + "/ajax/" + "login/form",
			success: function (data) {
				$("body").append(data);
				showWindow("login");

				$(".window-login input").on("keyup", function() {
					$(".window-login .error").hide();
				});

				$(".window-login .button").on("click", function() {
					$.ajax({
						url: "/" + LANG + "/ajax/" +  "login/login",
						type: "POST",
						data: $(this).parents("form").serialize(),
						success: function (data) {
							if (data) {
								$(".window-login .error-" + data).show();
							} else {
								window.location.replace("");
							}
						}
					});
					return false;
				});
			}
		});
		return false;
	});

	// Кнопка выхода
	$("#logout-button").on("click", function () {
		$.ajax({
			url: "/" + LANG + "/ajax/" + "login/logout",
			success: function (data) {
				window.location.replace("");
			}
		});
		return false;
	});

	// Кнопка "закрыть" на окне
	$("body").on("click", ".window .close-window", function () {
		hideWindow($(this).parent().data("type"));
	});

	// Клик по затемнению
	$("body").on("click", ".overlay", function () {
		hideWindow($(this).data("type"));
	});

});