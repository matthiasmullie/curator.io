{include:'{$CORE_PATH}/layout/templates/head.tpl'}

	<div id="container">
		{include:'{$CORE_PATH}/layout/templates/header.tpl'}

		{include:'{$CORE_PATH}/layout/templates/nav.tpl'}

		<section id="example" class="index content mod">
			<header class="header">
				<h2>Add collection</h2>
			</header>

			{form:add}

				{option:formAddHasError}<div class="message notice"><p>{$errGeneralFormError}</p></div>{/option:formAddHasError}

				<fieldset class="visibleFieldset">
					<p class="mediumInput{option:txtNameError} errorArea{/option:txtNameError}">
						<label for="name">Name<abbr title="{$msgRequired}">*</abbr></label>
						{$txtName} {$txtNameError}
					</p>
					<p class="mediumInput{option:txtDescriptionError} errorArea{/option:txtDescriptionError}">
						<label for="description">Description</label>
						{$txtDescription} {$txtDescriptionError}
					</p>
				</fieldset>

				<p class="buttonHolder">
					<input type="submit" class="inputSubmit" name="ok" value="{$lblAdd|ucfirst}">
				</p>
			{/form:add}

		</section>

		{include:'{$CORE_PATH}/layout/templates/footer.tpl'}
	</div>
</body>
</html>