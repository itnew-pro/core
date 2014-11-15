var windowFunctions = {

	show: function (type, level, title, content, footer) {
		var windowTemplate = $("#window-template").html();
		windowTemplate = windowTemplate
			.replace(/\{TYPE}/g, type)
			.replace(/\{LEVEL}/g, level)
			.replace(/\{TITLE}/g, title)
			.replace(/\{CONTENT}/g, content)
			.replace(/\{FOOTER}/g, footer);
		$ajaxWrapper.append(windowTemplate);
		var $window = $ajaxWrapper.find(".window-" + type);
		var $overlay = $ajaxWrapper.find(".overlay-" + type);
		$window.animate({opacity: 1}, 500);
		$overlay.animate({opacity: 0.7}, 500);
		$overlay.on("click", function(){
			windowFunctions.hide(type);
		});
		$window.find("div.close").on("click", function(){
			windowFunctions.hide(type);
		});
	},

	hide: function (type) {
		$ajaxWrapper.find(".overlay-" + type).animate({opacity: 0}, 400, function () {
			$(this).remove();
		});
		$ajaxWrapper.find(".window-" + type).animate({opacity: 0}, 400, function () {
			$(this).remove();
		});
	}
};

$(document).ready(function () {

	window.$ajaxWrapper = $("#ajax-wrapper");

	$("#login-button").on("click", function () {
		var title = $(this).data("title");
		windowFunctions.show("login", 1, title, 123, "");
	});

});