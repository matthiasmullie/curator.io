<div id="topWrapper">
	<header id="header" role="banner">
		<div class="container">

			<!-- Logo -->
			<div id="logo">
				<div class="inner">
					{option:title}
						<h1><a href="/">{$title}</a></h1>
					{/option:title}
					{option:!title}
						<h1><a href="/" title="Discover the collections">Curator.io</a></h1>
					{/option:!title}
				</div>
			</div>

			<!-- Mobile Navigation -->
			<div id="mobileNavigation">
				<div id="mobileNavigationInactive">
					<nav>
						<ul>
							<li><a href="#" class="navigationButton">Menu</a></li>
						</ul>
					</nav>
				</div>
				<div id="mobileNavigationActive" style="display: none;">
					<nav>
						<ul>
							<li><a href="/">Home</a></li>
							{option:!currentUser}
								<li><div class="fb-login-button">Log in with Facebook</div></li>
							{/option:!currentUser}
							{option:currentUser}
							<li>
								<a href="{$var|buildurl:'add':'collections'}/{$currentUser.uri}">Add Collection</a>
							</li>
							<li id="currentUser">
								<a href="{$currentUser.full_uri}">
									<img src="{$currentUser.avatar_50x50}" alt="{$currentUser.name}" width="32" height="32" />
									My Collections
								</a>
							</li>
							{/option:currentUser}
							<li id="search">
								<form method="get" action="{$var|buildurl:'search':'collections'}">
									<fieldset>
										<p class="mobileField">
											<input type="search" name="q" class="inputSearch" placeholder="Search..." id="searchField" />
											<input type="submit" value="submit" style="display: none;">
										</p>
									</fieldset>
								</form>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</header>
</div>