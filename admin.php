<?php
// Activer la session
session_start();
// Démarrer le tampon de sortie
ob_start();
// Inclure les fichiers utilitaires
require_once 'include/config.php';
require_once BUSINESS_DIR . 'error_handler.php';
// Définir le gestionnaire d'erreurs
ErrorHandler::SetHandler();
// Charger le template de la page d'application
require_once PRESENTATION_DIR . 'application.php';
require_once PRESENTATION_DIR . 'link.php';
// Charger le gestionnaire de base de données
require_once BUSINESS_DIR . 'database_handler.php';
// Charger la couche métier
require_once BUSINESS_DIR . 'catalog.php';
// Charger le fichier de template Smarty
$application = new Application();
// Afficher la page
$application->display('store_admin.tpl');
// Fermer la connexion à la base de données
DatabaseHandler::Close();
// Afficher le contenu du tampon
flush();
ob_flush();
ob_end_clean();
