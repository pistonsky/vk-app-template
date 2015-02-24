var AboutPage = Backbone.View.extend({
	el: '#page',
	render: function () {
		$("#about").attr("style","display:block");
		$("#page-container").addClass("blurred");
	},
	events: {
		'click #about': 'closeModal'
	},
	closeModal: function (ev) {
		target = (ev.toElement == undefined)?ev.target:ev.toElement;
		if ($(target).is(".modal")) {
			$(".modal").removeAttr("style");
			$("#page-container").removeClass("blurred");
			window.history.back();
		}
	}
});
