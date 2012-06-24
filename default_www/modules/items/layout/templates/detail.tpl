{include:'{$CORE_PATH}/layout/templates/head.tpl'}

	<div id="container">
		{include:'{$CORE_PATH}/layout/templates/header.tpl'}

		{include:'{$CORE_PATH}/layout/templates/nav.tpl'}


		{$item|dump}

		<section id="example" class="index content mod">
			<div class="fb-like" data-href="{$item.full_uri}" data-send="false" data-width="225" data-show-faces="false"></div>
		</section>


		{include:'{$CORE_PATH}/layout/templates/footer.tpl'}
	</div>

	<script>
		var itemId = '{$item.id}';
	</script>
</body>
</html>