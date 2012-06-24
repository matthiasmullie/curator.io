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

			<div class="mod">
				{option:isCollectionOwner}
					<p><a href="{$var|buildurl:'edit'}/{$collection.user.uri}/{$collection.uri}" class="bigOrangeButton">Edit collection</a></p>
					<p style="padding: 0;"><a href="{$var|buildurl:'add':'items'}/{$collection.user.uri}/{$collection.uri}" class="bigOrangeButton">Add item</a></p>
				{/option:isCollectionOwner}
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
									{option:items.likes}
										<div class="likeWrapper">
											<a href="#">5 Likes</a>
										</div>
									{/option:items.likes}
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