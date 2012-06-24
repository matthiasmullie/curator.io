{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main">
		<div class="container">
			<!-- Item List-->
			<div class="itemList">
				<div class="inner">
					{option:collections}
						<ul>
							{iteration:collections}
								<li>
									<div class="itemImage">
										<a href="{$collections.full_uri}">
											<img src="{$collections.image_160x160}" alt="{$collections.name}" />
										</a>
									</div>
									{option:collections.likes}
										<div class="likeWrapper">
											<a href="{$collections.full_uri}">{$collections.likes} Likes</a>
										</div>
									{/option:collections.likes}
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
		</div>
	</div>
{include:'{$CORE_PATH}/layout/templates/footer.tpl'}