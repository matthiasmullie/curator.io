{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main">
		<div class="container">
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
					<input type="submit" class="inputSubmit" name="ok" value="Save">
				</p>
			{/form:edit}

			<a href="{$var|buildurl:'delete'}/{$collection.user.uri}/{$collection.uri}" class="confirm" data-message="Are you sure you want to delete this collection">Delete</a>
		</section>

		</div>
	</div>
{include:'{$CORE_PATH}/layout/templates/footer.tpl'}