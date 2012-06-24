{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main">
		<div class="container">
		{form:edit}
			<label for="name">Name</label> {$txtName} {$txtNameError}
			<label for="description">Description</label> {$txtDescription} {$txtDescriptionError}
			<img src="/files/items/source/{$item.image}" />
			<label for="image">Image</label> {$fileImage} {$fileImageError}
			<input type="submit" value="Edit" />
		{/form:edit}
		</div>
	</div>
{include:'{$CORE_PATH}/layout/templates/footer.tpl'}