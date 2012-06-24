{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main">
		<div class="container">
			{option:report}<div class="message success"><p>{$report}</p></div>{/option:report}

			<!-- Content -->
			<div class="mod content">
				<div class="inner">
					{$collection.description|cleanupplaintext}
				</div>
			</div>
			
			{option:isCollectionOwner}
				<div class="mod">
					<p><a href="{$var|buildurl:'edit'}/{$collection.user.uri}/{$collection.uri}" class="bigOrangeButton">Edit collection</a></p>
					<p style="padding: 0;"><a href="{$var|buildurl:'add':'items'}/{$collection.user.uri}/{$collection.uri}" class="bigOrangeButton">Add item</a></p>
				</div>
			{/option:isCollectionOwner}

			<div class="hr">
				<hr />
			</div>

			<!-- Item List-->
			<div class="itemList">
				<div class="inner">
					{option:items}
						<ul>
							{iteration:items}
								<li>
									<div class="itemImage">
										<a href="{$var|buildurl:'detail':'items'}/{$collection.user.uri}/{$collection.uri}/{$items.uri}">
											<img src="/files/items/160x160/{$items.image}" alt="{$items.name}" />
										</a>
									</div>
									{option:items.like_count}
										<div class="likeWrapper">
											<a href="{$var|buildurl:'detail':'items'}/{$collection.user.uri}/{$collection.uri}/{$items.uri}">{$items.like_count} Likes</a>
										</div>
									{/option:items.like_count}
									<div class="userInfo">
										<a href="{$items.user.full_uri}">
											<div class="userInfoData noThumbUserInfoData">
												{$items.name}
											</div>
										</a>
									</div>
								</li>
							{/iteration:items}
						</ul>
					{/option:items}
				</div>
			</div>
		</div>
	</div>
{include:'{$CORE_PATH}/layout/templates/footer.tpl'}