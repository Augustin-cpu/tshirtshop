<?php
// Classe qui gère l'authentification des administrateurs
class AdminLogin
{
    // Variables publiques disponibles dans les templates smarty
    public $mUsername;
    public $mLoginMessage = '';
    public $mLinkToAdmin;
    public $mLinkToIndex;
    // Constructeur de la classe
    public function __construct()
    {
        // Vérifier si le nom d'utilisateur et le mot de passe corrects ont été fournis
        if (isset($_POST['submit'])) {
            if (
                $_POST['username'] == ADMIN_USERNAME
                && $_POST['password'] == ADMIN_PASSWORD
            ) {
                $_SESSION['admin_logged'] = true;
                header('Location: ' . Link::ToAdmin());
                exit();
            } else
                $this->mLoginMessage = 'Échec de la connexion. Veuillez réessayer :';
        }
        $this->mLinkToAdmin = Link::ToAdmin();
        $this->mLinkToIndex = Link::ToIndex();
    }
}
