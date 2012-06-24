{include:'{$CORE_PATH}/layout/templates/head.tpl'}

	<div id="container">
		{include:'{$CORE_PATH}/layout/templates/header.tpl'}

		{include:'{$CORE_PATH}/layout/templates/nav.tpl'}

		<section id="example" class="index content mod">

			{option:report}<div class="message success"><p>{$report}</p></div>{/option:report}

			<header class="header">
				<h2>{$collection.name}</h2>
			</header>

			<p>
				{$collection.description}
			</p>

			{* loop items hier *}

			{option:isCollectionOwner}
				<a href="{$var|buildurl:'edit'}/{$collection.uri}">Edit collection</a><br>
				<a href="">Add item</a>
			{/option:isCollectionOwner}

			<a href="{$collectionOwner.full_uri}">
				<img src="{$collectionOwner.avatar_50x50}">
				{$collectionOwner.name}
			</a>
		</section>

		{include:'{$CORE_PATH}/layout/templates/footer.tpl'}
	</div>
</body>
</html>