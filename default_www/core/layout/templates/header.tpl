<header id="header">
	<h1>
		<a href="/" rel="home">
			<span class="ir">
				TITLE
			</span>
		</a>
	</h1>
</header>

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
