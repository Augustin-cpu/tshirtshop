<?php
// Classe qui gère l'administration des produits d'une catégorie spécifique
class AdminProducts
{
    // Variables publiques disponibles dans le gabarit Smarty
    public $mProductsCount;
    public $mProducts;
    public $mErrorMessage;
    public $mDepartmentId;
    public $mCategoryId;
    public $mCategoryName;
    public $mLinkToDepartmentCategoriesAdmin;
    public $mLinkToCategoryProductsAdmin;
    // Attributs privés
    private $_mAction;
    private $_mActionedProductId;
    // Constructeur de la classe
    public function __construct()
    {
        if (isset($_GET['DepartmentId']))
            $this->mDepartmentId = (int)$_GET['DepartmentId'];
        else
            trigger_error('DepartmentId non défini');
        if (isset($_GET['CategoryId']))
            $this->mCategoryId = (int)$_GET['CategoryId'];
        else
            trigger_error('CategoryId non défini');
        $category_details = Catalog::GetCategoryDetails($this->mCategoryId);
        $this->mCategoryName = $category_details['name'];
        foreach ($_POST as $key => $value)
            // Si un bouton de soumission a été cliqué...
            if (substr($key, 0, 6) == 'submit') {
                /* Obtenir la position du dernier '_' (underscore) à partir du nom du bouton de soumission
par exemple strrpos('submit_edit_prod_1', '_') est 17 */
                $last_underscore = strrpos($key, '_');
                /* Obtenir la portée du bouton de soumission
(par exemple 'edit_dep' à partir de 'submit_edit_prod_1') */
                $this->_mAction = substr(
                    $key,
                    strlen('submit_'),
                    $last_underscore - strlen('submit_')
                );
                /* Obtenir l'ID du produit ciblé par le bouton de soumission
(le nombre à la fin du nom du bouton de soumission)
par exemple '1' à partir de 'submit_edit_prod_1' */
                $this->_mActionedProductId = (int)substr($key, $last_underscore + 1);
                break;
            }
        $this->mLinkToDepartmentCategoriesAdmin =
            Link::ToDepartmentCategoriesAdmin($this->mDepartmentId);
        $this->mLinkToCategoryProductsAdmin =
            Link::ToCategoryProductsAdmin($this->mDepartmentId, $this->mCategoryId);
    }
    public function init()
    {
        // Si on ajoute un nouveau produit ...
        if ($this->_mAction == 'add_prod') {
            $product_name = $_POST['product_name'];
            $product_description = $_POST['product_description'];
            $product_price = $_POST['product_price'];
            if ($product_name == null)
                $this->mErrorMessage = 'Le nom du produit est vide';
            if ($product_description == null)
                $this->mErrorMessage = 'La description du produit est vide';
            if ($product_price == null || !is_numeric($product_price))
                $this->mErrorMessage = 'Le prix du produit doit être un nombre !';
            if ($this->mErrorMessage == null) {
                Catalog::AddProductToCategory(
                    $this->mCategoryId,
                    $product_name,
                    $product_description,
                    $product_price
                );
                header('Location: ' .
                    htmlspecialchars_decode(
                        $this->mLinkToCategoryProductsAdmin
                    ));
            }
        }
        // Si l'on veut voir les détails d'un produit
        if ($this->_mAction == 'edit_prod') {
            header('Location: ' .
                htmlspecialchars_decode(
                    Link::ToProductAdmin(
                        $this->mDepartmentId,
                        $this->mCategoryId,
                        $this->_mActionedProductId
                    )
                ));
            exit();
        }
        $this->mProducts = Catalog::GetCategoryProducts($this->mCategoryId);
        $this->mProductsCount = count($this->mProducts);
    }
}
