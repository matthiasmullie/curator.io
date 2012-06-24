{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main">
		<div class="container">
			<div class="leftCol">
				<!-- Big Image -->
				<div class="bigImageWrapper">
					{* @todo *}
					<img src="{$item.image_436x436}" alt="{$item.name}" />
				</div>

				<!-- Like Module -->
				<div class="likeModule">
					<div class="likeButtonWrapper">
						<div class="fb-like" data-href="{$item.full_uri}" data-send="false" data-width="225" data-show-faces="false"></div>
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
								{.iteration:item.custom}
							</ul>
						{/option:item.custom}
					</div>
				</div>

				<div class="hr">
					<hr />
				</div>

				{* @todo	only when is owner *}
				<!-- Action buttons-->
				<div class="mod content">
					<div class="inner">
						<p><a href="#" class="bigOrangeButton">Edit</a></p>
					</div>
				</div>

				<!-- Whitelist -->
				<div class="whiteList">
					<div class="inner">
						<ul>
							<li>
								{* @todo *}
								<a href="#">
									<img src="images/iconMovie.png" alt="Test">
									Movies 
									<span class="rightWhiteList">Category</span>
								</a>
							</li>
							<li>
								{* @todo *}
								<a href="{$item.user.full_uri}">
									<img src="{$item.user.avatar_50x50}" width="32" height="32" alt="{$item.user.name}">
									{$item.user.name} 
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