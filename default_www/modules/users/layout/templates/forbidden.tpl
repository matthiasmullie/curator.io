{include:'{$CORE_PATH}/layout/templates/head.tpl'}

	<div id="container">
		{include:'{$CORE_PATH}/layout/templates/header.tpl'}

		{include:'{$CORE_PATH}/layout/templates/nav.tpl'}

		<section id="users" class="forbidden content mod">
			<p>
				For this function you should be authenticated, please login with Facebook <div class="fb-login-button"></div>
			</p>
		</section>

		{include:'{$CORE_PATH}/layout/templates/footer.tpl'}
	</div>
</body>
</html>