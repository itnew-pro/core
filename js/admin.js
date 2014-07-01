// Методы для работы с ajax
var ajaxFunctions = {
	controller: "section",
	action: "action",
	id: 0,

	// Кнопка панели управления
	panelTab: function (data) {
		$("#subpanel").remove();
		$(".panel-tab").removeClass("active");
		this.updatePanel(data);
		$(".panel-tab-" + this.controller).addClass("active").parent().addClass("active");
		$("body")
			.on("click", "#panel .close", function () {
				$("#panel").remove();
				$("#subpanel").remove();
				$(".panel-tab").removeClass("active");
				$("#panel-tabs").removeClass("active");
			})
			.on("click", "#subpanel .close", function () {
				$("#subpanel").remove();
			})
			.on("keyup", "#itnew_model_Block_name", function () {
				$(this).parent().find(".error").hide();
			});
	},

	// Панель
	updatePanel: function (data) {
		$("#subpanel").remove();
		$("#panel").remove();
		$("body").append(data);
		setFunctions.panel();
	},

	// Субпанель
	updateSubpanel: function (data) {
		$("#subpanel").remove();
		$("body").append(data);
	},
	saveSettings: function (data) {
		this.updatePanel(data["panel"]);
		$(".content-" + this.controller + "-" + this.id).replaceWith(data["content"]);
	},
	addSubpanel: function (data) {
		this.updateSubpanel(data);
		$("#panel .scroll-container *").removeClass("active");
	},
	showTextSubpanel: function (data) {
		this.updateSubpanel(data);
	},
	showImagesSubpanel: function (data) {
		this.updateSubpanel(data);
		imagesFunctions.showImageStyleParams();
		$("#imagesViewType").change(function () {
			imagesFunctions.showImageStyleParams();
		});
	},
	showRecordsSubpanel: function (data) {
		this.updateSubpanel(data);
		recordsFunctions.showImageStyleParams();
		$("#images-view").change(function () {
			recordsFunctions.showImageStyleParams();
		});
		recordsFunctions.showFormBlockContainerCover();
		$("#itnew_models_Records_isCover").change(function () {
			recordsFunctions.showFormBlockContainerCover();
		});
		recordsFunctions.showFormBlockContainerImages();
		$("#itnew_models_Records_isImages").change(function () {
			recordsFunctions.showFormBlockContainerImages();
		});
	},
	showSectionSubpanel: function (data) {
		this.updateSubpanel();
		$("#panel .section-item").removeClass("active");
		$("#panel .section-" + this.id).addClass("active");
		$("#subpanel").on("click", ".close", function () {
			$("#panel .section-item").removeClass("active");
		});
	},
	saveSectionSubpanel: function (data) {
		if (data["error"]) {
			$(".error-" + data["error"]).show();
		} else {
			$("#subpanel").remove();
			$("#panel").remove();
			$("body").append(data["panel"]);
		}
	},

	// Окно
	showWindow: function (data) {
		$("body").append(data);
		windowFunctions.show(this.controller);
		setFunctions.windows();
	},
	saveWindow: function (data) {
		windowFunctions.hide(this.controller);
		$(".content-" + this.controller + "-" + this.id).replaceWith(data);
	},
	showTextWindow: function (data) {
		this.showWindow(data);
	},
	showImagesWindow: function (data) {
		this.showWindow(data);
		$(".sortable").sortable({
			items: "> .image-window-item",
			stop: function () {
				var sortString = "";
				$(this).find(".image-window-item").each(function () {
					sortString += $(this).data("id") + ",";
				});
				$(this).parent().find(".imageContentIds").val(sortString);
			}
		});
		$(".window .image-file-field").on("change", function () {
			var $object = $(this);
			$object.hide();
			//$object.parent().find(".loader").show();
			$object.parent().find("i.c").hide();
			imagesFunctions.upload(this.files, 0, $object, $object.data("id"));
		});
		$("body").on("click", ".close-container", function () {
			$(this).parent().remove();
		});
	},
	showStaffWindow: function (data) {
		this.showWindow(data);
		$(".sortable").sortable({
			stop: function () {
				var sortString = "";
				$(this).find(".move-item").each(function () {
					sortString += $(this).data("id") + ",";
				});
				$("#itnew_models_Staff_groupIds").val(sortString);
			}
		});
	},
	showStaffGroupWindow: function (data) {
		$("body").append(data);
		windowFunctions.show("staff-group");
		setFunctions.windows();
	},
	saveStaffGroupWindow: function (data) {
		this.saveWindow();
		windowFunctions.hide("staff-group");
		$("body").append(data);
		windowFunctions.show("staff");
	},
	showRecordsWindow: function (data) {
		this.showWindow(data);
		$(".sortable").sortable({
			stop: function () {
				var sortString = "";
				$(this).find(".move-item").each(function () {
					sortString += $(this).data("id") + ",";
				});
				$("#itnew_models_Records_contentIds").val(sortString);
			}
		});
	},
	showRecordsFormWindow: function (data) {
		$("body").append(data);
		showWindow("records-form");
		$(".datepicker").datepicker({
			dateFormat: "dd.mm.yy"
		});
		$(".move-item").on("click", function () {
			$(this).parent().remove();
		});
	},
	showRecordsAddWindow: function (data) {
		$("body").append(data);
		windowFunctions.show("records-add");
	},
	saveNewRecordsWindow: function (data) {
		if (data["errorClass"]) {
			$(".window-records-add ." + data["errorClass"]).show();
		} else {
			this.saveWindow();
			windowFunctions.hide("records-add");
			$("body")
				.append(data["recordsForm"])
				.append(data["records"]);
			windowFunctions.show("records-form");
			windowFunctions.show("records");
		}
	},
	saveRecordsFormWindow: function (data) {
		this.saveWindow();
		windowFunctions.hide("records-form");
		$("body").append(data);
		windowFunctions.show("records");
	},

	// Пустая функция
	empty: function (data) {
	},

	// Получает действия в случае успешного выполнения ajax
	execute: function (method, data, controller, action, id) {
		this.controller = controller;
		this.action = action;
		this.id = id;
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
	},

	// SEO
	seo: function () {
		$("body").on("keyup", ".seo-name", function () {
			var $parent = $(this).parent().parent();
			$parent.find(".error-name-empty").hide();
			$parent.find(".error-url-empty").hide();
			$parent.find(".error-url-exist").hide();
			$parent.find(".seo-url").val($(this).val().translit());
		});

		$("body").on("keyup", ".seo-url", function () {
			var $parent = $(this).parent();
			$parent.find(".error-url-empty").hide();
			$parent.find(".error-url-exist").hide();
			$parent.find(".seo-url").val($(this).val().translit());
		});

		$("body").on("click", ".seo-title a", function () {
			$(this).parent().parent().find(".seo-optimization").slideToggle(200);
			return false;
		});
	}
};

// Функции для работы с изображениями
var imagesFunctions = {

	// Загружает изображения
	upload: function (files, i, $object, id) {
		if (i < files.length) {
			var formData = new FormData();
			formData.append("itnew_models_ImagesContent[file]", files[i]);
			$.ajax({
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				url: "/" + LANG + "/ajax/" + "images/upload?id=" + id,
				data: formData,
				success: function (data) {
					if (data) {
						$object.parent().before(data);
					}
					i++;
					this.upload(files, i, $object, id);
				}
			});
		} else {
			$object.show();
			$object.parent().find(".loader").hide();
			$object.parent().find("i.c").show();
		}
	},

	// Показывает параметры изображения
	showImageStyleParams: function () {
		var $imagesViewType = $("#imagesViewType");
		var style = $imagesViewType.val();
		var $formStyle = $imagesViewType.parent().parent().find(".form-style");
		$formStyle.addClass("hide");
		if (style == 1) {
			$formStyle.removeClass("hide");
		}
	}
};

// Функции для работы с записями
var recordsFunctions = {

	// Показывает стили изображения
	showImageStyleParams: function () {
		if ($("#images-view").val() == 1) {
			$(".form-thumb-images-view").removeClass("hide");
		} else {
			$(".form-style-images-view").addClass("hide");
		}
	},

	// Показывает параметры обложки
	showFormBlockContainerCover: function () {
		if ($("#itnew_models_Records_isCover").prop("checked")) {
			$(".form-block-container-cover").removeClass("hide");
		} else {
			$(".form-block-container-cover").addClass("hide");
		}
	},

	// Показывает блок с изображениями
	showFormBlockContainerImages: function () {
		if ($("#Records_isImages").prop("checked")) {
			$(".form-block-container-images").removeClass("hide");
		} else {
			$(".form-block-container-images").addClass("hide");
		}
	}
};

$(document).ready(function () {

	// Обрабатываются клики по элементам для ajax запросов
	$("body").on("click", ".ajax", function () {
		if ($(this).data("confirm")) {
			if (!confirm('Восстановить будет невозможно! \\r\\n Вы действительно хотите удалить безвозвратно?')) {
				return false;
			}
		}
		var method = $(this).data("function");
		var controller = $(this).data("controller");
		var action = $(this).data("action");
		var url = "/" + LANG + "/ajax/" + controller + "/" + action;
		var dataType = "text";
		var type = "GET";
		var data = {};
		var id = 0;
		if ($(this).data("post")) {
			type = "POST";
			data = $(this).parents("form").serialize()
		}
		if ($(this).data("json")) {
			dataType = "JSON";
		}
		if ($(this).data("id")) {
			id = $(this).data("id");
		}

		$.ajax({
			url: url,
			type: type,
			data: data,
			dataType: dataType,
			success: function (data) {
				ajaxFunctions.execute(method, data, controller, action, id);
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

	setFunctions.seo();
});