{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main">
		<div class="container">

			{option:report}<div class="message success"><p>{$report}</p></div>{/option:report}

			<h1>{$collection.name}</h1>

			<p>
				{$collection.description}
			</p>

			{* loop items hier *}

			{option:isCollectionOwner}
				<a href="{$var|buildurl:'edit'}/{$collection.user.uri}/{$collection.uri}">Edit collection</a><br>
				<a href="">Add item</a>
			{/option:isCollectionOwner}

			<a href="{$collection.user.full_uri}">
				<img src="{$collection.user.avatar_50x50}">
				{$collection.user.name}
			</a>

		</div>
	</div>
{include:'{$CORE_PATH}/layout/templates/footer.tpl'}