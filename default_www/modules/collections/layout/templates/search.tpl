{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main">
		<div class="container">
			{option:report}<div class="message success"><p>{$report}</p></div>{/option:report}

			{option:noItems}
				<div class="message">
					<p>We are really sorry, but we didn't find anything that matches the searchquery.</p>
				</div>
			{/option:noItems}
			
			{option:collections}
				<h3>Collections</h3>
			
				<div class="itemList">
					<div class="inner">
						<ul>
							{iteration:collections}
								<li>
									<div class="itemImage">
										<a href="{$collections.full_uri}" title="{$collections.name}">
											<img src="/files/collections/160x160/{$collections.image}" alt="{$collections.name}" />
										</a>
									</div>
									{option:collections.like_count}
										<div class="likeWrapper">
											<a href="{$collections.full_uri}">{$collections.like_count} Likes</a>
										</div>
									{/option:collections.like_count}
									<div class="userInfo">
										<a href="{$collections.user.full_uri}">
											<div class="userInfoImage">
												<img src="{$collections.user.avatar_50x50}" width="24" height="24" alt="{$collections.user.name}" />
											</div>
											<div class="userInfoData">
												{$collections.user.name}
											</div>
										</a>
									</div>
								</li>
							{/iteration:collections}
						</ul>
					</div>
				</div>

				{option:items}
					<div class="hr">
						<hr />
					</div>
				{/option:items}
			{/option:collections}
			
			
			{option:items}
				<h3>Items</h3>
				<div class="itemList">
					<div class="inner">
						<ul>
							{iteration:items}
								<li>
									<div class="itemImage">
										<a href="{$var|buildurl:'detail':'items'}/{$items.collection.user.uri}/{$items.collection.uri}/{$items.uri}" title="{$items.name}">
											<img src="/files/items/160x160/{$items.image}" alt="{$items.name}" />
										</a>
									</div>
									{option:items.like_count}
										<div class="likeWrapper">
											<a href="{$var|buildurl:'detail':'items'}/{$items.collection.user.uri}/{$items.collection.uri}/{$items.uri}">{$items.like_count} Likes</a>
										</div>
									{/option:items.like_count}
									<div class="userInfo">
										<a href="{$items.collection.user.full_uri}">
											<div class="userInfoImage">
												<img src="{$items.collection.user.avatar_50x50}" alt="{$items.collection.user.name}" />
											</div>
											<div class="userInfoData">
												{$items.collection.user.name}
											</div>
										</a>
									</div>
								</li>
							{/iteration:items}
						</ul>
					</div>
				</div>
			{/option:items}
		</div>
	</div>
{include:'{$CORE_PATH}/layout/templates/footer.tpl'}