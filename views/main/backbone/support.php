var SupportPage = Backbone.View.extend({
	el: '#page',
	render: function () {
		$("#support").attr("style","display:block");
		$("#page-container").addClass("blurred");
	},
	events: {
		'click #support': 'closeModal'
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
