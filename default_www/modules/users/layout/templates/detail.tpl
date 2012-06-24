{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main">
		<div class="container">
			{option:report}<div class="message success"><p>{$report}</p></div>{/option:report}

			<!-- Content -->
			<div class="bigUserInfo clearfix">
				<div class="userInfoImage">
					<a href="#">
						<img src="{$user.avatar_x200}">
					</a>
				</div>
				<div class="userInfoData content">
					<ul>
						<li>{$numCollections} Collections</li>
						<li>{$numItems} Items</li>
						<li>{$numLikes} Likes</li>
					</ul>
				</div>
			</div>

			{option:isCurrentUser}
			<div class="mod">
				<a href="{$var|buildurl:'add':'collections'}/{$user.uri}" class="bigOrangeButton">Add collection</a>
			</div>
			{/option:isCurrentUser}

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
										<a href="{$items.full_uri}">
											<img src="{$items.image_160x160}" alt="{$items.name}" />
										</a>
									</div>
									{option:items.like_count}
										<div class="likeWrapper">
											<a href="{$items.full_uri}">{$items.like_count} Likes</a>
										</div>
									{/option:items.like_count}
									<div class="userInfo">
										<a href="{$items.full_uri}">
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