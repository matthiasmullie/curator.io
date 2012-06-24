{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main">
		<div class="container">
			{form:add}
				<label for="name">Name</label> {$txtName} {$txtNameError}
				<label for="description">Description</label> {$txtDescription} {$txtDescriptionError}
				<label for="image">Image</label> {$fileImage} {$fileImageError}
				<input type="submit" value="Add" />
			{/form:add}
		</div>
	</div>
{include:'{$CORE_PATH}/layout/templates/footer.tpl'}