<?php
// Définir le code de statut 500
header('HTTP/1.0 500 Internal Server Error');
require_once 'include/config.php';
require_once PRESENTATION_DIR . 'link.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <title>
        Erreur d'Application TShirtShop (500) : Catalogue de Produits de Démonstration de
        Beginning PHP and MySQL E-Commerce
    </title>
    <link href="<?php echo Link::Build('styles/tshirtshop.css'); ?>"
        type="text/css" rel="stylesheet" />
</head>

<body>
    <div id="doc" class="yui-t7">
        <div id="bd">
            <div id="header" class="yui-g">
                <a href="<?php echo Link::Build(''); ?>">
                    <img src="<?php echo Link::Build('images/tshirtshop.png'); ?>"
                        alt="logo tshirtshop" />
                </a>
            </div>
            <div id="contents" class="yui-g">
                <h1>
                    TShirtShop rencontre des difficultés techniques.
                </h1>
                <p>
                    Veuillez
                    <a href="<?php echo Link::Build(''); ?>">nous rendre visite</a> bientôt,
                    ou <a href="<?php echo ADMIN_ERROR_MAIL; ?>">nous contacter</a>.
                </p>
                <p>Merci !</p>
                <p>L'équipe TShirtShop.</p>
            </div>
        </div>
    </div>
</body>

</html>