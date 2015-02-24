	<div class="modal fade in" id="about" tabindex="-1" role="dialog">
		<div class="modal-dialog simple-page">
			<h1>О компании</h1>
			<p></p>
		</div>
	</div> 

	<div class="modal fade in" id="support" tabindex="-1" role="dialog">
		<div class="modal-dialog simple-page">
			<h1>Техподдержка</h1>
			<p></p>
		</div>
	</div> 

	<div class="modal fade in" id="alert" tabindex="-1" role="dialog">

	</div> 

<h1>Done!</h1>

<p>If everything went fine, you should see your ID here:</p>
<pre><?= \Yii::$app->user->identity->user_id ?></pre>

<!-- BEGIN PAGE MARKUP -->     
	<div class="page" id="page-container">
		<!-- BEGIN HEADER -->
		<header class="navbar">
			<!-- logo is in the background css property -->
			<div class="container">

				<nav>
					<a href="#/dashboard" class="btn btn-link">Меню</a>
					<a href="#/settings" class="btn btn-link">Настройки</a>
				</nav>

			</div>
		</header>
		<!-- END HEADER -->
		<!-- BEGIN PAGE CONTAINER -->
		<div class="page-container">
			<!-- BEGIN CONTAINER -->
			<div id="container">
				<div class="loading-container">
					<h1>Загружается...</h1>
				</div>
			</div>
			<!-- END CONTAINER -->

		</div>
		<!-- END PAGE CONTAINER -->
		<!-- BEGIN FOOTER -->
		<footer>
			<a href="#/about">О компании</a>
			<a href="#/support">Техническая поддержка</a>
		</footer>
		<!-- END FOOTER -->
	</div>
<!-- END PAGE MARKUP -->

<?php include(dirname(__FILE__) . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR . "templates.php"); ?>

<script type="text/javascript">

	function showMessage(title, message, handler, top) {
		var template = _.template($("#alert-template").html(), {
			title: title,
			message: message
		});
		$("#alert").html(template).attr("style","display:block");
		if (typeof(top) == "number") {
			$("#alert div.alert").css("margin-top",top + "%");
		}
		$("#page-container").addClass("blurred");
		$("button#close-alert").click(handler);
	}

	$('body').on('click', 'div#alert button#close-alert', function (event) {
		$("#alert").removeAttr("style");
		$("#page-container").removeClass("blurred");
	});

	jQuery.expr[':'].contains = function(a, i, m) {
		return jQuery(a).text().toUpperCase()
			.indexOf(m[3].toUpperCase()) >= 0;
	};

	VK.init(function() {

		var ip = "<?php echo $_SERVER['REMOTE_ADDR'];?>";
		var uid = <?php echo $_GET['viewer_id'];?>; // id vkontakte
		var auth_key = "<?php echo $_GET['auth_key'];?>";

<?php include(dirname(__FILE__) . DIRECTORY_SEPARATOR . "backbone" . DIRECTORY_SEPARATOR . "dashboard.php"); ?>
<?php include(dirname(__FILE__) . DIRECTORY_SEPARATOR . "backbone" . DIRECTORY_SEPARATOR . "about.php"); ?>
<?php include(dirname(__FILE__) . DIRECTORY_SEPARATOR . "backbone" . DIRECTORY_SEPARATOR . "support.php"); ?>

		var dashboardPage = new DashboardPage();
		var aboutPage = new AboutPage();
		var supportPage = new SupportPage();

		var Router = Backbone.Router.extend({
			routes: {
				'': 'welcome',
				'dashboard': 'dashboard',
				'tab-1': 'tab-1',
				'tab-2': 'tab-2',
				'tab-3': 'tab-3',
				'about': 'about',
				'support': 'support'
			}
		});

		router.on('route:dashboard', function () {
			dashboardPage.render();
		});

		router.on('route:tab-1', function() {
			$('#dashboard-tabs li, #dashboard .tab-container').removeClass('active');
			$('.tab-1').addClass('active');
			setFrameHeight(1021);
			// перезаполняем вкладку только если она уже не заполнена
			if ($('#tab-1').hasClass('empty')) {

				var tab_1 = _.template($('#tab-1-template').html(), {
				});
				$('#tab-1').html(tab_1);
				
				$('.tab-1').removeClass('empty');
				
			}
		});

		router.on('route:tab-2', function() {
			$('#dashboard-tabs li, #dashboard .tab-container').removeClass('active');
			$('.tab-2').addClass('active');
			setFrameHeight(1021);
			// перезаполняем вкладку только если она уже не заполнена
			if ($('#tab-2').hasClass('empty')) {

				var tab_2 = _.template($('#tab-2-template').html(), {
				});
				$('#tab-2').html(tab_1);
				
				$('.tab-2').removeClass('empty');
				
			}
		});

		router.on('route:tab-3', function() {
			$('#dashboard-tabs li, #dashboard .tab-container').removeClass('active');
			$('.tab-3').addClass('active');
			setFrameHeight(1021);
			// перезаполняем вкладку только если она уже не заполнена
			if ($('#tab-3').hasClass('empty')) {

				var tab_3 = _.template($('#tab-3-template').html(), {
				});
				$('#tab-3').html(tab_1);
				
				$('.tab-3').removeClass('empty');
				
			}
		});

		router.on('route:about', function() {
			aboutPage.render();
		});

		router.on('route:support', function() {
			supportPage.render();
		});

		var router = new Router();

	}, function() {

	}, '5.21');

	function adjustFrameHeight() {
		VK.callMethod("resizeWindow", 1000, $('#page').height()+Number(20));
	}

	function setFrameHeight(height) {
		VK.callMethod("resizeWindow", 1000, height);
	}