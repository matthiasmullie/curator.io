{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main" class="homeMain">
		<div class="container">

			{option:report}<div class="message success"><p>{$report}</p></div>{/option:report}

			<!-- Mobile Tabs -->
			<div class="multiTab">
				<div class="inner">
					<nav>
						<ul>
							<li{option:sortPopular} class="selected"{/option:sortPopular}><a href="{$var|buildurl:'index':'collections'}">Popular</a></li>
							<li{option:sortLatest} class="selected"{/option:sortLatest}><a href="{$var|buildurl:'index':'collections'}/latest">Latest</a></li>
							<li{option:sortCategories} class="selected"{/option:sortCategories}><a href="{$var|buildurl:'index':'collections'}/categories">Categories</a></li>
						</ul>
					</nav>
				</div>
			</div>

			<!-- Item List-->
			<div class="itemList">
				<div class="inner">
					{option:items}
						<ul>
							{iteration:items}
								<li>
									<div class="itemImage">
										<a href="{$items.full_uri}">
											<img src="/files/items/160x160/{$items.image}" alt="{$items.name}" />
										</a>
									</div>
									{option:items.like_count}
										<div class="likeWrapper">
											<a href="{$items.full_uri}">{$items.like_count} Likes</a>
										</div>
									{/option:items.like_count}
									<div class="userInfo">
										<a href="{$items.user.full_uri}">
											<div class="userInfoImage">
												<img src="{$collection.user.avatar_50x50}" width="24" height="24" alt="{$collection.user.name}" />
											</div>
											<div class="userInfoData">
												{$collection.user.name}
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