<div class="container-center">
	<h3>Create Account</h3>

	<form action="<?= $context->route()->url("user:create") ?>" method="post">
		<div class="row">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input class="mdl-textfield__input" type="text" id="name" value="<?= $name ?>" name="name">
				<label class="mdl-textfield__label" for="name">Name</label>
			</div>
		</div>

		<div class="row">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input class="mdl-textfield__input" type="email" id="email" value="<?= $email ?>" name="email">
				<label class="mdl-textfield__label" for="email">Email</label>
			</div>
		</div>

		<div class="row">
			<div class="row mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input class="mdl-textfield__input" type="password" id="password" name="password">
				<label class="mdl-textfield__label" for="password">Password</label>
			</div>
		</div>

		<div class="row">
			<div class="row mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input class="mdl-textfield__input" type="password" id="repeat_password" name="repeat_password">
				<label class="mdl-textfield__label" for="repeat_password">Repeat password</label>
			</div>
		</div>

		<a href="<?= $context->route()->url("auth:login") ?>" class="mdl-button mdl-js-button">
			Login
		</a>

		<button type="submit" name="submit" value="1" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
			Create
		</button>
	</form>
</div>
