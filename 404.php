<?php
// Définir le code de statut 404
header('HTTP/1.0 404 Not Found');
require_once 'include/config.php';
require_once PRESENTATION_DIR . 'link.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>
TShirtShop Page Introuvable (404) : Catalogue de Produits de Démonstration de
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
La page que vous avez demandée n'existe pas sur TShirtShop.
</h1>
<p>
Veuillez visiter le
<a href="<?php echo Link::Build(''); ?>">catalogue TShirtShop</a>
si vous recherchez des T-shirts,
ou <a href="<?php echo ADMIN_ERROR_MAIL; ?>">envoyez-nous un e-mail</a>
si vous avez besoin d'aide supplémentaire.
</p>
<p>Merci !</p>
<p>L'équipe TShirtShop.</p>
</div>
</div>
</div>
</body>
</html>