{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main">
		<div class="container">
			{option:report}<div class="message success"><p>{$report}</p></div>{/option:report}

			{* @todo	Yoni style me *}
			{option:isCollectionOwner}
				<a href="{$var|buildurl:'edit'}/{$collection.user.uri}/{$collection.uri}">Edit collection</a><br>
				<a href="">Add item</a>
			{/option:isCollectionOwner}

			<!-- Content -->
			<div class="mod content">
				<div class="inner">
					{$collection.description|cleanupplaintext}
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
								{* @todo *}
								<li>
									<div class="itemImage">
										<a href="#">
											<img src="images/test.jpg" alt="Test" />
										</a>
									</div>
									<div class="likeWrapper">
										<a href="#">5 Likes</a>
									</div>
									<div class="userInfo">
										<a href="#">
											<div class="userInfoImage">
												<img src="images/test.jpg" alt="Test" />
											</div>
											<div class="userInfoData">
												Yoni De Beule
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