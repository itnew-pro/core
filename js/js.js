// Методы для работы с к\окнами
var windowFunctions = {

	// Показывает окно
	show: function (name) {
		$(".window-" + name).animate({opacity: 1}, 500);
		$(".overlay-" + name).animate({opacity: 0.7}, 500);
	},

	// Скрывает окно
	hide: function (name) {
		$(".overlay-" + name).animate({opacity: 0}, 500, function () {
			$(this).remove();
		});
		$(".window-" + name).animate({opacity: 0}, 500, function () {
			$(this).remove();
		});
	}
};

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
 * Преобразует первый символ строки в верхний регистр
 *
 * @param {string} string строка
 *
 * @return string
 */
function ucwords(string) {
	return string.charAt(0).toUpperCase() + string.substr(1).toLowerCase();
}

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

// Методы для валидации
var validateFunctions = {

	// Проверка на пустоту
	required: function (data) {
		return data != "";
	},

	// Проверка на email
	email: function (data) {
		if (data == "") {
			return true;
		}
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(data);
	},

	// Проверка на email и на пустую строку
	emailRequired: function (data) {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(data);
	},

	// Выполняет действия
	execute: function (method, value) {
		return this[method]($.trim(value));
	}
};

$(document).ready(function () {

	// Кнопка входа
	$("#login-button").on("click", function () {
		$.ajax({
			url: "/" + LANG + "/ajax/" + "login/form",
			success: function (data) {
				$("body").append(data);
				windowFunctions.show("login");

				$(".window-login input").on("keyup", function () {
					$(".window-login .error").hide();
				});

				$(".window-login .button").on("click", function () {
					$.ajax({
						url: "/" + LANG + "/ajax/" + "login/login",
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
		windowFunctions.hide($(this).parent().data("type"));
	});

	// Клик по затемнению
	$("body").on("click", ".overlay", function () {
		windowFunctions.hide($(this).data("type"));
	});

	// Увеличение изображений
	$(".fancybox").fancybox({
		openEffect: "elastic",
		closeEffect: "elastic",
		helpers: {
			thumbs: {
				width: 50,
				height: 50
			},
			buttons: {}
		}
	});

	// Слайдер
	$(".chopslider .slider").chopSlider({
		slide: ".slide",
		nextTrigger: ".chopslider a.slide-next",
		prevTrigger: ".chopslider a.slide-prev",
		hideTriggers: true,
		sliderPagination: ".chopslider .slider-pagination",
		autoplay: true,
		autoplayDelay: 5000,
		t2D: csTransitions["half"]["3"],
		noCSS3: csTransitions["noCSS3"]["random"],
		mobile: csTransitions["mobile"]["random"]
	});

	// Обратная связь
	$("body").on("click", ".feedback-button", function() {
		var $form = $(this).parents("form");
		var $hasErrors = $form.find(".has-error");
		var errorsCount = $hasErrors.length;
		if (errorsCount > 0) {
			$form.find(".error").hide();
			var i = 0;
			$hasErrors.each(function() {
				var value = $(this).val();
				var error = $(this).data("error");
				var id = $(this).attr("id");
				if (error != "" && !validateFunctions.execute(error, value)) {
					$("#" + id + "-" + error).show();
					return false;
				} else {
					i++;
					if (i == errorsCount) {
						executeFeedbackAjax($form);
					}
				}
			});
		} else {
			executeFeedbackAjax($form);
		}
		return false;
	});

});

/**
 * Выполняет AJAX для формы обратной связи
 *
 * @param {object} $form
 *
 * @return void
 */
function executeFeedbackAjax($form) {
	var $button = $form.find(".feedback-button");
	$.ajax({
		url: "/" + LANG + "/ajax/feedback/send/",
		type: "POST",
		data: $form.serialize(),
		dataType: "JSON",
		beforeSend: function() {
			$button.find("span").css("opacity", 0);
			$button.find(".button-loader").show();
		},
		success: function (data) {
			$button.find("span").css("opacity", 1);
			$button.find(".button-loader").hide();
			$form.css("opacity", 0);
			if (data.status) {
				$form.parent().find(".success").show();
			} else {
				$form.parent().find(".not-success").show();
			}
		}
	});
}