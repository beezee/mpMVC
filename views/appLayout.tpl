<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title><!-- app_name --><!-- /app_name --></title>
	<meta name="description" content="">
	<meta name="author" content="">
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="<!-- base_url --><!-- /base_url -->public/stylesheets/base.css">
	<link rel="stylesheet" href="<!-- base_url --><!-- /base_url -->public/stylesheets/skeleton.css">
	<link rel="stylesheet" href="<!-- base_url --><!-- /base_url -->public/stylesheets/layout.css">

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="<!-- base_url --><!-- /base_url -->public/images/favicon.ico">
	<link rel="apple-touch-icon" href="<!-- base_url --><!-- /base_url -->public/images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<!-- base_url --><!-- /base_url -->public/images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<!-- base_url --><!-- /base_url -->public/images/apple-touch-icon-114x114.png">

</head>
<body>



	<!-- Primary Page Layout
	================================================== -->

	<!-- Delete everything in this .container and get started on your own site! -->

	<div class="container">
		<div class="sixteen columns">
			<h1 class="remove-bottom" style="margin-top: 40px"><a href="<!-- base_url --><!-- /base_url -->"><!-- app_name --><!-- /app_name --></a></h1>
			<hr />
		</div>
		<div class="two columns">
			&nbsp;
		</div>
		<div class="twelve columns">
			<!-- yield --><!-- /yield -->
		</div>
		<div class="two columns">
			&nbsp;
		</div>

	</div><!-- container -->



	<!-- JS
	================================================== -->
	<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
	<script src="<!-- base_url --><!-- /base_url -->public/javascripts/tabs.js"></script>
        <script src="<!-- base_url --><!-- /base_url -->public/javascripts/underscore-1.2.4.js"></script>
        <script src="<!-- base_url --><!-- /base_url -->public/javascripts/backbone.js"></script>
        <script src="<!-- base_url --><!-- /base_url -->public/javascripts/json2.js"></script>
	<script type="text/javascript">
		$('.del').click(function(e) {
			e.preventDefault();
			var c = confirm('Are you sure?');
			if (c) window.location.href = $(this).attr('href');
		})
	</script>

<!-- End Document
================================================== -->
</body>
</html>