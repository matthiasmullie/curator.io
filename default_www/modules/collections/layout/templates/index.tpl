{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main">
		<div class="container">
	
			{option:report}<div class="message success"><p>{$report}</p></div>{/option:report}

			<!-- Mobile Tabs -->
			<div class="multiTab">
				<div class="inner">
					<nav>
						<ul>
							<li class="selected"><a href="#">Popular</a></li>
							<li><a href="#">Latest</a></li>
							<li><a href="#">Categories</a></li>
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