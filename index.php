
<?php

require 'vendor/autoload.php';

ini_set("display_errors", 1);

require_once('macro_functions.php');

session_start();

use App\Core\Application;
use App\Core\Request\Request;

$request = new Request($_SERVER, $_POST, $_GET);
$app = new Application();
try {
    $response = $app->dispatch($request);
} catch (Throwable $e) {
	die($e->getMessage());
}

$first_connection_ckey = $app->getContext()->getConfig()->getAll()["first_connection_cookiekey"];
if(!$app->getContext()->request()->cookie()->has($first_connection_ckey)) {
    $app->getContext()->request()->cookie()->set($first_connection_ckey, 1);
    $app->getContext()->request()->flash()->push("infos", "Info: La compléxité du projet par rapport à l'exercice donné a été implémenté uniquement pour un entraînement personnel (code entièrement maison et réalisé durant l'examen).");
}

?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Exam DFS22C</title>

	    <!-- Material Icons -->
	    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

	    <!-- Meterial Design Lite -->
	    <script defer type="text/javascript" src="/js/material.min.js"></script>
        <link rel="stylesheet" href="/css/material.min.css">

        <link rel="stylesheet" href="/css/app.css">
    </head>

    <body>
	    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer
            mdl-layout--fixed-header">
		    <header class="mdl-layout__header">
			    <div class="mdl-layout__header-row">
				    <div class="mdl-layout-spacer"></div>
				    <div class="">
					    <label class="mdl-button mdl-js-button mdl-button--icon"
					           for="fixed-header-drawer-exp">
						    <?php if($app->getContext()->auth()->isAuth()): ?>
							    <a href="<?= $app->getContext()->route()->url("auth:logout") ?>" id="header__button__account">
								    <i class="material-icons">logout</i>
							    </a>
						    <?php else: ?>
							    <a href="<?= $app->getContext()->route()->url("auth:login") ?>" id="header__button__account">
								    <i class="material-icons">person_circle</i>
							    </a>
						    <?php endif; ?>
					    </label>
				    </div>
			    </div>
		    </header>
		    <div class="mdl-layout__drawer">
			    <span class="mdl-layout-title"><a class="color-black no-decoration" href="<?= $app->getContext()->route()->url("root") ?>">Exam DFS22C</a></span>
			    <nav class="mdl-navigation">
				    <?php if(!$app->getContext()->auth()->isAuth()): ?>
				        <a class="mdl-navigation__link" href="<?= $app->getContext()->route()->url("auth:login") ?>">Login</a>
				        <a class="mdl-navigation__link" href="<?= $app->getContext()->route()->url("user:create") ?>">Create Account</a>
				    <?php endif; ?>
                    <?php if($app->getContext()->auth()->isAuth()): ?>
	                    <a class="mdl-navigation__link" href="<?= $app->getContext()->route()->url("dashboard:index") ?>">Dashboard</a>
				        <a class="mdl-navigation__link" href="<?= $app->getContext()->route()->url("user:update") ?>">Update Account</a>
	                    <a class="mdl-navigation__link" href="<?= $app->getContext()->route()->url("auth:logout") ?>">Logout</a>
	                    <a class="mdl-navigation__link" href="<?= $app->getContext()->route()->url("user:delete", ['id' => $app->getContext()->auth()->user()->getId()]) ?>" style="color: red" data-confirm="Are you sure you want to delete this account ?">Delete Account</a>
				    <?php endif; ?>
			    </nav>
		    </div>
		    <main class="mdl-layout__content">
			    <div class="page-content container">
				    <div class="alerts">
                        <?php foreach (["errors", "success", "warnings", "infos"] as $alert_type): ?>
	                        <?php foreach ($app->getContext()->request()->flash()->get($alert_type, []) as $alert): ?>
						        <div class="alert alert-<?= $alert_type ?>">
							        <p><?= $alert ?></p>
							        <span class="alert-close">X</span>
						        </div>
						    <?php endforeach; ?>
                        <?php endforeach; ?>
				    </div>

                    <?= $response->render(); ?>
			    </div>
		    </main>
	    </div>

	    <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', () => {
                Array.from(document.querySelectorAll('[data-confirm]')).map((item) => {
                    item.addEventListener('click', function(e) {
                        if(confirm(item.dataset.confirm))
                            return;

                        e.preventDefault()
                        e.stopPropagation()
                    })
                })

                Array.from(document.querySelectorAll('.alert-close')).map((item) => {
                    item.addEventListener('click', function(e) {
                        if(item.dataset.noremove === undefined)
                            item.parentElement.remove()
                    })
                })
            })
	    </script>
    </body>
</html>
