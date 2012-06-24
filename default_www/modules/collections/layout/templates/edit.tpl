{include:'{$CORE_PATH}/layout/templates/head.tpl'}

	<div id="container">
		{include:'{$CORE_PATH}/layout/templates/header.tpl'}

		{include:'{$CORE_PATH}/layout/templates/nav.tpl'}

		<section id="example" class="index content mod">
			<header class="header">
				<h2>Edit collection</h2>
			</header>

			{form:edit}

				{option:formEditHasError}<div class="message notice"><p>{$errGeneralFormError}</p></div>{/option:formEditHasError}

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
					<input type="submit" class="inputSubmit" name="ok" value="{$lblSave|ucfirst}">
				</p>
			{/form:edit}

			<a href="{$var|buildurl:'delete'}/{$slug}" class="confirm" data-message="Are you sure you want to delete this collection">Delete</a>

		</section>

		{include:'{$CORE_PATH}/layout/templates/footer.tpl'}
	</div>
</body>
</html>