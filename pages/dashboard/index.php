<div>
	<h3>User information</h3>

	<div>
		ID: <span><?= $user->getId(); ?></span>
	</div>
	<div>
		Name: <span><?= $user->getName(); ?></span>
	</div>
	<div>
		Email: <span><?= $user->getEmail(); ?></span>
	</div>
	<div>
		Pets: <span><?= count($pets); ?></span>
	</div>

	<h3>Pets</h3>
    <?php if(count($pets) > 0): ?>
    <div class="table-container">
	    <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
		    <thead>
			    <tr>
				    <th></th>
				    <th class="mdl-data-table__cell--non-numeric">ID</th>
				    <th>Nb Photos</th>
				    <th>Name</th>
				    <th>Type</th>
				    <th>Race</th>
				    <th>Actions</th>
			    </tr>
		    </thead>
		    <tbody>
                <?php foreach($pets as $pet): ?>
				    <tr>
					    <td>
						    <a href="<?= $context->route()->url("pet:delete", ['id' => $pet->getId()]) ?>">
							    <button data-confirm="Are you sure you want to delete this animal ?" class="mdl-button mdl-js-button mdl-button js-ripple-effect">
								    <i class="material-icons">delete</i>
							    </button>
						    </a>
						    <a href="<?= $context->route()->url("pet:imageView", ['id' => $pet->getId()]) ?>">
							    <button type="submit" class="mdl-button mdl-js-button mdl-button js-ripple-effect">
								    <i class="material-icons">image</i>
							    </button>
						    </a>
					    </td>
					    <td class="mdl-data-table__cell--non-numeric"><?= $pet->getId() ?></td>
					    <td><?= $pet_image_count_array[$pet->getId()]; ?></td>
					    <td><?= $pet->getName() ?></td>
					    <td><?= $pet->getType() ?></td>
					    <td><?= $pet->getRace() ?></td>
					    <td>
						    <form action="<?= $context->route()->url("pet:uploadImage") ?>" method="post" enctype="multipart/form-data">
							    <input type="hidden" name="id" value="<?= $pet->getId(); ?>">
							    <input type="file" id="file" name="file" style="width:initial!important;">
							    <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
								    Add Image
							    </button>
						    </form>
					    </td>
				    </tr>
                <?php endforeach; ?>
		    </tbody>
	    </table>
    </div>
    <?php else: ?>
	    <div class="alert alert-warnings">
		    <p>No pets.</p>
	    </div>
    <?php endif; ?>

	<h3>Add Pet</h3>

	<form action="<?= $context->route()->url("user:addPet") ?>" method="post">
		<div class="row">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input class="mdl-textfield__input" type="text" id="name" name="name">
				<label class="mdl-textfield__label" for="name">Name</label>
			</div>
		</div>

		<div class="row">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input class="mdl-textfield__input" type="text" id="type" name="type">
				<label class="mdl-textfield__label" for="type">Type</label>
			</div>
		</div>

		<div class="row">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input class="mdl-textfield__input" type="text" id="race" name="race">
				<label class="mdl-textfield__label" for="race">Race</label>
			</div>
		</div>

		<button type="submit" name="submit" value="1" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
			Add
		</button>
	</form>
</div>
