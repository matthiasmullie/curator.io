<div id="topWrapper">
	<header id="header" role="banner">
		<div class="container">

			<!-- Logo -->
			<div id="logo">
				<div class="inner">
					<h1><a href="#" title="">Curator.io</a></h1>
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
							<li class="selected"><a href="#">Home</a></li>
							<li><a href="#">Discover</a></li>
							<li><a href="#">Log in with Facebook</a></li>
							<li><a href="#">About</a></li>
							<li id="search">
								<form>
									<fieldset>
										<p class="mediumInput">
											<input type="search" name="" class="inputSearch" value="Search..." id="searchField" />
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





{option:currentUser}
	<section id="user">
		<p>
			<a href="{$currentUser.full_uri}">
				<img src="{$currentUser.avatar_50x50}" alt="{$currentUser.name}" width="50" height="50" />
				{$currentUser.name}
			</a>
		</p>
	</section>
{/option:currentUser}
{option:!currentUser}
	<div class="fb-login-button"></div>
{/option:!currentUser}
