var DashboardPage = Backbone.View.extend({

	el: '#page',
	// Dashboard events
	events: {

	},
	render: function () {
		VK.callMethod("showInstallBox"); // ради рассылки уведомлений прил должен быть установлен

		if ($("#dashboard").length == 0) { // рендерить всё заново только если ещё не рендерили
			setFrameHeight(1020);
			var template = _.template($('#dashboard-template').html(), {

			});
			$("#container").append(template);

			// по-умолчанию открыта вкладка перевод другу - заполняем её
			var tab_1 = _.template($('#tab-1-template').html(), {
			});
			$('#tab-1').html(tab_1);

			// запрос истории переводов
			// если есть новый перевод - показываем
			$.ajax({
				method: "POST",
				url: "<?php echo Yii::app()->request->baseUrl;?>/endpoint",
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