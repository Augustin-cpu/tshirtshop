<?php
// Gère les détails du produit
class Product
{
    // Variables publiques à utiliser dans le gabarit Smarty
    public $mProduct;
    public $mProductLocations;
    public $mLinkToContinueShopping;
    public $mLocations;

    // Éléments privés
    private $_mProductId;

    // Constructeur de classe
    public function __construct()
    {
        // Initialisation des variables
        if (isset($_GET['ProductId']))
            $this->_mProductId = (int)$_GET['ProductId'];
        else
            trigger_error('ProductId non défini');
    }

    public function init()
    {
        // Obtenir les détails du produit de la couche métier
        $this->mProduct = Catalog::GetProductDetails($this->_mProductId);

        // Logique pour le lien Continuer mes achats
        if (isset($_SESSION['link_to_continue_shopping'])) {
            $continue_shopping =
                Link::QueryStringToArray($_SESSION['link_to_continue_shopping']);
            $page = 1;

            if (isset($continue_shopping['Page']))
                $page = (int)$continue_shopping['Page'];

            if (isset($continue_shopping['CategoryId']))
                $this->mLinkToContinueShopping =
                    Link::ToCategory(
                        (int)$continue_shopping['DepartmentId'],
                        (int)$continue_shopping['CategoryId'],
                        $page
                    );
            elseif (isset($continue_shopping['DepartmentId']))
                $this->mLinkToContinueShopping =
                    Link::ToDepartment((int)$continue_shopping['DepartmentId'], $page);
            elseif (isset($continue_shopping['SearchResults']))
                $this->mLinkToContinueShopping =
                    Link::ToSearchResults(
                        trim(str_replace('-', ' ', $continue_shopping['SearchString'])),
                        $continue_shopping['AllWords'],
                        $page
                    );
            else
                $this->mLinkToContinueShopping = Link::ToIndex($page);
        }

        // Construction des chemins d'image
        if ($this->mProduct['image'])
            $this->mProduct['image'] =
                Link::Build('product_images/' . $this->mProduct['image']);

        if ($this->mProduct['image_2'])
            $this->mProduct['image_2'] =
                Link::Build('product_images/' . $this->mProduct['image_2']);
        $this->mProduct['attributes'] =
            Catalog::GetProductAttributes($this->mProduct['product_id']);
        // Obtenir les emplacements du produit (Départements/Catégories)
        $this->mLocations = Catalog::GetProductLocations($this->_mProductId);
        if (isset($continue_shopping['DepartmentId']))
            $this->mLinkToContinueShopping =
                Link::ToDepartment((int)$continue_shopping['DepartmentId'], $page);
        elseif (isset($continue_shopping['SearchResults']))
            $this->mLinkToContinueShopping =
                Link::ToSearchResults(
                    trim(str_replace('-', ' ', $continue_shopping['SearchString'])),
                    $continue_shopping['AllWords'],
                    $page
                );
        else
            $this->mLinkToContinueShopping = Link::ToIndex($page);

        // Construire des liens pour les pages de départements et catégories du produit
        for ($i = 0; $i < count($this->mLocations); $i++) {
            $this->mLocations[$i]['link_to_department'] =
                Link::ToDepartment($this->mLocations[$i]['department_id']);
            $this->mLocations[$i]['link_to_category'] =
                Link::ToCategory(
                    $this->mLocations[$i]['department_id'],
                    $this->mLocations[$i]['category_id']
                );
        }
    }
}
