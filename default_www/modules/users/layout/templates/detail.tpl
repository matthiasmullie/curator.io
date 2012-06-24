{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main">
		<div class="container">
			{option:report}<div class="message success"><p>{$report}</p></div>{/option:report}

			{* @todo	Yoni style me *}
			{option:isCurrentUser}
				<a href="{$var|buildurl:'add':'collections'}">Add collection</a><br>
			{/option:isCurrentUser}

			<!-- Content -->
			<div class="mod content">
				<div class="inner">
					<img src="{$user.avatar_x200}">
					{$user.name}<br>

					{$numCollections} collections<br>
					{$numItems} items<br>
					{$numLikes} likes
				</div>
			</div>

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
									<div class="likeWrapper">
										<a href="{$items.full_uri}">{$items.likes} Likes</a>
									</div>
									<div class="userInfo">
										<a href="{$items.user.full_uri}">
											<div class="userInfoImage">
												<img src="{$items.user.avatar_50x50}" width="24" height="24" alt="{$items.user.name}" />
											</div>
											<div class="userInfoData">
												{$items.user.name}
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