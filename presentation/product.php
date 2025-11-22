<?php
// Gère les détails du produit
class Product
{
    // Variables publiques à utiliser dans le gabarit Smarty
    public $mProduct;
    public $mProductLocations;
    public $mLinkToContinueShopping;
    public $mLocations;
    public $mEditActionTarget;
    public $mShowEditButton;

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
        // Show Edit button for administrators
        if (!(isset ($_SESSION['admin_logged'])) ||
            $_SESSION['admin_logged'] != true)
            $this->mShowEditButton = false;
        else
            $this->mShowEditButton = true;
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
                Link::Build('/Images/product_images/' . $this->mProduct['image_2']);
        $this->mProduct['attributes'] =
            Catalog::GetProductAttributes($this->mProduct['product_id']);
        // Obtenir les emplacements du produit (Départements/Catégories)
        $this->mLocations = Catalog::GetProductLocations($this->_mProductId);
        // Créer le lien Ajouter au Panier
        $this->mProduct['link_to_add_product'] =
        Link::ToCart(ADD_PRODUCT, $this->_mProductId);
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
        // Prepare the Edit button
        $this->mEditActionTarget =
            Link::Build(str_replace(VIRTUAL_LOCATION, '', getenv('REQUEST_URI')));
        if (isset ($_SESSION['admin_logged']) &&
            $_SESSION['admin_logged'] == true &&
            isset ($_POST['submit_edit']))
        {
            $product_locations = $this->mLocations;
            if (count($product_locations) > 0)
            {
                $department_id = $product_locations[0]['department_id'];
                $category_id = $product_locations[0]['category_id'];
                header('Location: ' .
                    htmlspecialchars_decode(
                        Link::ToProductAdmin($department_id,
                            $category_id,
                            $this->_mProductId)));
            }
        }
    }
}
