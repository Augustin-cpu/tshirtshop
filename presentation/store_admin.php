<?php
class StoreAdmin
{
    public $mSiteUrl;
    // Définir le fichier de template pour le menu de la page
    public $mMenuCell = 'blank.tpl';
    // Définir le fichier de template pour le contenu de la page
    public $mContentsCell = 'blank.tpl';
    // Constructeur de la classe
    public function __construct()
    {
        $this->mSiteUrl = Link::Build('', 'https');
        // Forcer l'accès à la page via HTTPS si USE_SSL est activé

    }
    public function init()
    {
        // Si l'administrateur n'est pas connecté, charger le template admin_login
        if (
            !(isset($_SESSION['admin_logged'])) ||
            $_SESSION['admin_logged'] != true
        )
            $this->mContentsCell = 'admin_login.tpl';
        else {
            // Si l'administrateur est connecté, charger la page de menu admin
            $this->mMenuCell = 'admin_menu.tpl';
            // Si déconnexion ...
            if (isset($_GET['Page']) && ($_GET['Page'] == 'Logout')) {
                unset($_SESSION['admin_logged']);
                header('Location: ' . Link::ToAdmin());
                exit();
            }
            // Si 'Page' n'est pas explicitement défini, on suppose la page Départements
            $admin_page = isset($_GET['Page']) ? $_GET['Page'] : 'Departments';
            // Choisir quelle page d'administration charger ...
            if ($admin_page == 'Departments')
                $this->mContentsCell = 'admin_departments.tpl';
            elseif ($admin_page == 'Categories')
                $this->mContentsCell = 'admin_categories.tpl';
            elseif ($admin_page == 'Attributes')
                $this->mContentsCell = 'admin_attributes.tpl';
            elseif ($admin_page == 'AttributeValues')
                $this->mContentsCell = 'admin_attribute_values.tpl';
            elseif ($admin_page == 'Products')
                $this->mContentsCell = 'admin_products.tpl';
            elseif ($admin_page == 'ProductDetails') // <--- Ajouté
                $this->mContentsCell = 'admin_product_details.tpl';
            elseif ($admin_page == 'Carts')
                $this->mContentsCell = 'admin_carts.tpl';
            elseif ($admin_page == 'orders')
                $this->mContentsCell = 'admin_orders.tpl';
            elseif ($admin_page == 'OrderDetails')
                $this->mContentsCell = 'admin_order_details.tpl';
        }
    }
}
