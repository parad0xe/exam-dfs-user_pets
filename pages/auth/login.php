<div class="container-center">
	<h3>Login</h3>

	<form action="<?= $context->route()->url("auth:login"); ?>" method="post">
		<div class="row">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input class="mdl-textfield__input" type="email" value="demo@demo.com" id="email" name="email">
				<label class="mdl-textfield__label" for="email">Email</label>
			</div>
		</div>

		<div class="row">
			<div class="row mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input class="mdl-textfield__input" type="password" id="password" name="password">
				<label class="mdl-textfield__label" for="password">Password</label>
			</div>
		</div>

		<a href="<?= $context->route()->url("user:create") ?>" class="mdl-button mdl-js-button">
			Create account
		</a>

		<button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
			LOGIN
		</button>
	</form>
</div>
