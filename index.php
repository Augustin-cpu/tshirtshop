<?php
// Activer la session
session_start();
// Démarrer le buffer de sortie
ob_start();
// Include utility files
require_once 'include/config.php';
// Load the application page template
require_once BUSINESS_DIR . 'error_handler.php';
// Charge le gestionnaire de base de données
require_once BUSINESS_DIR . 'database_handler.php';

require_once PRESENTATION_DIR . 'application.php';

// Charge la classe Link (Mise en évidence)
require_once PRESENTATION_DIR . 'link.php';

// Charger la Couche Métier
require_once BUSINESS_DIR . 'catalog.php';
require_once BUSINESS_DIR . 'shopping_cart.php';
// Correction d'URL
Link::CheckRequest();
// Charger le fichier de modèle Smarty
$application = new Application();

// Gérer les requêtes AJAX
if (isset ($_GET['AjaxRequest']))
{
// Les en-têtes sont envoyés pour empêcher les navigateurs de mettre en cache
    header('Expires: Fri, 25 Dec 1980 00:00:00 GMT'); // Temps dans le passé
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Content-Type: text/html');

    if (isset ($_GET['CartAction']))
    {
        $cart_action = $_GET['CartAction'];
        if ($cart_action == ADD_PRODUCT)
        {
            require_once PRESENTATION_DIR . 'cart_details.php';
            $cart_details = new CartDetails();
            $cart_details->init();
            $application->display('cart_summary.tpl');
        }
        else
        {
            $application->display('cart_details.tpl');
        }
    }
    else
        trigger_error('CartAction not set', E_USER_ERROR);
}
else
{
// Afficher la page
    $application->display('store_front.tpl');
}

// Fermer la connexion à la base de données
DatabaseHandler::Close();
// Afficher le contenu du buffer
flush();
ob_flush();
ob_end_clean();