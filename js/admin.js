$(document).ready(function(){

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
				$(".panel-tab-" + controller).addClass("active").parent().addClass("active");
			}
		});
		return false;
	});

});