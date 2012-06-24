<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="{$LANGUAGE}" class="ie6"> <![endif]-->
<!--[if IE 7 ]> <html lang="{$LANGUAGE}" class="ie7"> <![endif]-->
<!--[if IE 8 ]> <html lang="{$LANGUAGE}" class="ie8"> <![endif]-->
<!--[if IE 9 ]> <html lang="{$LANGUAGE}" class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="{$LANGUAGE}"> <!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>Curator.io</title>

	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
	<meta name="description" content="Collect, Curate and Share your Passion" />

	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	<link rel="image_src" href="/image_src.png" />

	<link rel="stylesheet" href="/core/layout/css/jquery_ui/jquery_ui.css?m={$LAST_MODIFIED}">
	<link rel="stylesheet" href="/core/layout/css/screen.css?m={$LAST_MODIFIED}">
	{option:css}{iteration:css}<script src="{$css.url}"></script>{/iteration:css}{/option:css}

	<!--[if lt IE 9]> <script src="/core/js/html5.js"></script> <![endif]-->
	<script src="/core/js/jquery.js"></script>
	<script src="/core/js/jquery.ui.js"></script>
	{option:javascript}{iteration:javascript}<script src="{$javascript.url}"></script>{/iteration:javascript}{/option:javascript}
	<script src="/js.php?module=core&amp;file=site.js&amp;language={$LANGUAGE}&amp;m={$LAST_MODIFIED}"></script>

	{option:opengraph}
		{iteration:opengraph}
			<meta property="og:{$opengraph.key}" content="{$opengraph.value}">
		{/iteration:opengraph}
	{/option:opengraph}

	{* Google Analytics *}
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-32885545-1']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
</head>

</head>
<body id="home">
	<div id="fb-root"></div>
	{* Facebook *}
	<script>
		window.fbAsyncInit = function() {
			FB.init({
				appId      : '237320303053296', // App ID
				channelUrl : '//curator.io/channel.html', // Channel File
				status     : true, // check login status
				cookie     : true, // enable cookies to allow the server to access the session
				xfbml      : true  // parse XFBML
			});

			// initialize Facebook
			jsSite.facebook.init();
		};
		// Load the SDK Asynchronously
		(function(d){
			var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement('script'); js.id = id; js.async = true;
			js.src = "//connect.facebook.net/en_US/all.js";
			ref.parentNode.insertBefore(js, ref);
		 }(document));
	</script>



