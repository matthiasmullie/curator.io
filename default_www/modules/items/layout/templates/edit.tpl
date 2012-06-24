{include:'{$CORE_PATH}/layout/templates/head.tpl'}

	<div id="container">
		{include:'{$CORE_PATH}/layout/templates/header.tpl'}

		{include:'{$CORE_PATH}/layout/templates/nav.tpl'}


		{form:edit}
			<label for="name">Name</label> {$txtName} {$txtNameError}
			<label for="description">Description</label> {$txtDescription} {$txtDescriptionError}
			<img src="/files/items/source/{$item.image}" />
			<label for="image">Image</label> {$fileImage} {$fileImageError}
			<input type="submit" value="Edit" />
		{/form:edit}


		{include:'{$CORE_PATH}/layout/templates/footer.tpl'}
	</div>
</body>
</html>