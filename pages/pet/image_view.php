<div class="container-center">
    <a href="<?= $context->route()->url("dashboard:index") ?>">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
            Return to dashboard
        </button>
    </a>
    <h3>Images for: <?= $pet->getName() ?> (<?= $pet->getType(); ?>)</h3>
	<?php if(count($images) > 0): ?>
        <?php foreach ($images as $image): ?>
            <div class="demo-card-image mdl-card mdl-shadow--2dp">
                <img src="<?= $image->getWebUri(); ?>" alt="">
                <div class="mdl-card__actions">
                    <span class="demo-card-image__filename"><?= $image->getName() ?></span>
                    <span>
                        <a href="<?= $context->route()->url("pet:imageDelete", ['id' => $pet->getId(), 'image_id' => $image->getId()]) ?>">
                            <button data-confirm="Aye you sure you want to delete this image ?">
                                <i class="material-icons">delete</i>
                            </button>
                        </a>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warnings">
            <p>No image uploaded.</p>
        </div>
    <?php endif; ?>
</div>
