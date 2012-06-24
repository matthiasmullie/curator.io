{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main">
		<div class="container">
			<div class="leftCol">
				<!-- Big Image -->
				<div class="bigImageWrapper">
					<img src="/files/items/436x436/{$item.image}" alt="{$item.name}" />
				</div>

				<!-- Like Module -->
				<div class="likeModule">
					<div class="likeButtonWrapper">
						<div class="fb-like" data-href="{$SITE_URL}{$item.full_uri}" data-send="false" data-width="225" data-show-faces="false"></div>
					</div>
				</div>
			</div>

			<div class="rightCol">
				<!-- Content -->
				<div class="mod content">
					<div class="inner">
						{$item.description|cleanupplaintext}
						{option:item.custom}
							<ul>
								{iteration:item.custom}
									<li>
										{$item.custom.name}: {$item.custom.value}
									</li>
								{/iteration:item.custom}
							</ul>
						{/option:item.custom}
					</div>
				</div>

				<div class="hr">
					<hr />
				</div>

				{option:isOwner}
					<!-- Action buttons-->
					<div class="mod content">
						<div class="inner">
							<p><a href="{$var|buildurl:'add':'items'}/{$item.collection.user.uri}/{$item.collection.uri}" class="bigOrangeButton">Add new</a></p>
							<p style="padding: 0;"><a href="{$var|buildurl:'edit':'items'}/{$item.collection.user.uri}/{$item.collection.uri}/{$item.uri}" class="bigOrangeButton">Edit</a></p>
						</div>
					</div>
				{/option:isOwner}

				<!-- Whitelist -->
				<div class="whiteList">
					<div class="inner">
						<ul>
							<li>
								<a href="{$category.full_uri}">
									<img src="/files/icons/{$category.icon}" alt="{$category.name}">
									{$category.name}
									<span class="rightWhiteList">Category</span>
								</a>
							</li>
							<li>
								<a href="{$item.collection.user.full_uri}">
									<img src="{$item.collection.user.avatar_50x50}" width="32" height="32" alt="{$item.collection.user.name}">
									{$item.collection.user.name}
									<span class="rightWhiteList">Curator</span>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>var itemId = '{$item.id}';</script>
{include:'{$CORE_PATH}/layout/templates/footer.tpl'}