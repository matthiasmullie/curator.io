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

			{option:!sortCategories}
			<!-- Item List-->
			<div class="itemList">
				<div class="inner">
					{option:collections}
						<ul>
							{iteration:collections}
								<li>
									<div class="itemImage">
										<a href="{$collections.full_uri}" title="{$collections.name}">
											<img src="/files/items/160x160/{$collections.image}" alt="{$collections.name}" />
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
					{/option:collections}
				</div>
			</div>
			{/option:!sortCategories}

			{option:sortCategories}
				{option:categories}
				<div class="whiteList">
					<div class="inner">
						<ul>
							{iteration:categories}
								<li>
									<a href="{$categories.full_uri}">
										<img src="/files/icons/{$categories.icon}">{$categories.name}
									</a>
								</li>
							{/iteration:categories}
						</ul>
					</div>
				</div>
				{/option:categories}
			{/option:sortCategories}
		</div>
	</div>
{include:'{$CORE_PATH}/layout/templates/footer.tpl'}