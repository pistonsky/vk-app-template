var DashboardPage = Backbone.View.extend({

	el: '#page',
	events: {

	},
	render: function () {
		VK.callMethod("showInstallBox"); 

		if ($("#dashboard").length == 0) {
			setFrameHeight(1020);
			var template = _.template($('#dashboard-template').html(), {

			});
			$("#container").append(template);

			var tab_1 = _.template($('#tab-1-template').html(), {
			});
			$('#tab-1').html(tab_1);

			$.ajax({
				method: "POST",
				url: "<?= \Yii::$app->request->baseUrl ?>/endpoint",
				dataType: "json",
				data: {
					uid: id,
					auth_key: auth_key
				},
				success: function (data) {

				},
				error: function (x, t, m) {

				}
			});
		} else {
				setFrameHeight(1020);
		}
		$("#container > *").hide();
		$("#dashboard").show();
	},

	resetTab: function (ev) {
		// перейти по адресу активного таба, например #/tab-1
		href = $("#dashboard-tabs li.active a").attr('href');
		route = href.replace(/#\//g,'');
		$('#tab-' + route).addClass('empty');
		Backbone.history.fragment = null;
		Backbone.history.navigate(route, {trigger: true, replace: true});
	}
});