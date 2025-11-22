<?php
// Classe qui gère l'administration des produits
class AdminProductDetails
{
    // Attributs publics
    public $mProduct;
    public $mErrorMessage;
    public $mProductCategoriesString;
    public $mProductDisplayOptions;
    public $mProductAttributes;
    public $mCatalogAttributes;
    public $mAssignOrMoveTo;
    public $mRemoveFromCategories;
    public $mRemoveFromCategoryButtonDisabled = false;
    public $mLinkToCategoryProductsAdmin;
    public $mLinkToProductDetailsAdmin;
    // Attributs privés
    private $_mProductId;
    private $_mCategoryId;
    private $_mDepartmentId;
    // Constructeur de la classe
    public function __construct()
    {
        // Doit avoir DepartmentId dans la chaîne de requête
        if (!isset($_GET['DepartmentId']))
            trigger_error('DepartmentId non défini');
        else
            $this->_mDepartmentId = (int)$_GET['DepartmentId'];
        // Doit avoir CategoryId dans la chaîne de requête
        if (!isset($_GET['CategoryId']))
            trigger_error('CategoryId non défini');
        else
            $this->_mCategoryId = (int)$_GET['CategoryId'];
        // Doit avoir ProductId dans la chaîne de requête
        if (!isset($_GET['ProductId']))
            trigger_error('ProductId non défini');
        else
            $this->_mProductId = (int)$_GET['ProductId'];
        $this->mProductDisplayOptions = Catalog::$mProductDisplayOptions;
        $this->mLinkToCategoryProductsAdmin =
            Link::ToCategoryProductsAdmin($this->_mDepartmentId, $this->_mCategoryId);
        $this->mLinkToProductDetailsAdmin =
            Link::ToProductAdmin(
                $this->_mDepartmentId,
                $this->_mCategoryId,
                $this->_mProductId
            );
    }
    public function init()
    {
        // Si on télécharge une image de produit ...
        if (isset($_POST['Upload'])) {
            /* Vérifier si nous avons la permission d'écriture sur le
dossier product_images */
            if (!is_writeable(SITE_ROOT . '/product_images/')) {
                echo "Impossible d'écrire dans le dossier product_images";
                exit();
            }
            // Si le code d'erreur est 0, le fichier a été téléchargé correctement
            if ($_FILES['ImageUpload']['error'] == 0) {
                /* Utiliser la fonction PHP move_uploaded_file pour déplacer le fichier
de son emplacement temporaire vers le dossier product_images */
                move_uploaded_file(
                    $_FILES['ImageUpload']['tmp_name'],
                    SITE_ROOT . '/product_images/' .
                        $_FILES['ImageUpload']['name']
                );
                // Mettre à jour les informations du produit dans la base de données
                Catalog::SetImage(
                    $this->_mProductId,
                    $_FILES['ImageUpload']['name']
                );
            }
            // Si le code d'erreur est 0, le fichier a été téléchargé correctement
            if ($_FILES['Image2Upload']['error'] == 0) {
                /* Utiliser la fonction PHP move_uploaded_file pour déplacer le fichier
de son emplacement temporaire vers le dossier product_images */
                move_uploaded_file(
                    $_FILES['Image2Upload']['tmp_name'],
                    SITE_ROOT . '/product_images/' .
                        $_FILES['Image2Upload']['name']
                );
                // Mettre à jour les informations du produit dans la base de données
                Catalog::SetImage2(
                    $this->_mProductId,
                    $_FILES['Image2Upload']['name']
                );
            }
            // Si le code d'erreur est 0, le fichier a été téléchargé correctement
            if ($_FILES['ThumbnailUpload']['error'] == 0) {
                // Déplacer le fichier téléchargé vers le dossier product_images
                move_uploaded_file(
                    $_FILES['ThumbnailUpload']['tmp_name'],
                    SITE_ROOT . '/product_images/' .
                        $_FILES['ThumbnailUpload']['name']
                );
                // Mettre à jour les informations du produit dans la base de données
                Catalog::SetThumbnail(
                    $this->_mProductId,
                    $_FILES['ThumbnailUpload']['name']
                );
            }
        }
        // Si on met à jour les informations du produit ...
        if (isset($_POST['UpdateProductInfo'])) {
            $product_name = $_POST['name'];
            $product_description = $_POST['description'];
            $product_price = $_POST['price'];
            $product_discounted_price = $_POST['discounted_price'];
            if ($product_name == null)
                $this->mErrorMessage = 'Le nom du produit est vide';
            if ($product_description == null)
                $this->mErrorMessage = 'La description du produit est vide';
            if ($product_price == null || !is_numeric($product_price))
                $this->mErrorMessage = 'Le prix du produit doit être un nombre !';
            if (
                $product_discounted_price == null ||
                !is_numeric($product_discounted_price)
            )
                $this->mErrorMessage = 'Le prix réduit du produit doit être un nombre !';
            if ($this->mErrorMessage == null)
                Catalog::UpdateProduct(
                    $this->_mProductId,
                    $product_name,
                    $product_description,
                    $product_price,
                    $product_discounted_price
                );
        }
        // Si on retire le produit d'une catégorie ...
        if (isset($_POST['RemoveFromCategory'])) {
            $target_category_id = $_POST['TargetCategoryIdRemove'];
            $still_exists = Catalog::RemoveProductFromCategory(
                $this->_mProductId,
                $target_category_id
            );
            if ($still_exists == 0) {
                header('Location: ' .
                    htmlspecialchars_decode(
                        $this->mLinkToCategoryProductsAdmin
                    ));
                exit();
            }
        }
        // Si on définit l'option d'affichage du produit ...
        if (isset($_POST['SetProductDisplayOption'])) {
            $product_display = $_POST['ProductDisplay'];
            Catalog::SetProductDisplayOption($this->_mProductId, $product_display);
        }
        // Si on retire le produit du catalogue ...
        if (isset($_POST['RemoveFromCatalog'])) {
            Catalog::DeleteProduct($this->_mProductId);
            header('Location: ' .
                htmlspecialchars_decode(
                    $this->mLinkToCategoryProductsAdmin
                ));
            exit();
        }
        // Si on assigne le produit à une autre catégorie ...
        if (isset($_POST['Assign'])) {
            $target_category_id = $_POST['TargetCategoryIdAssign'];
            Catalog::AssignProductToCategory(
                $this->_mProductId,
                $target_category_id
            );
        }
        // Si on déplace le produit vers une autre catégorie ...
        if (isset($_POST['Move'])) {
            $target_category_id = $_POST['TargetCategoryIdMove'];
            Catalog::MoveProductToCategory(
                $this->_mProductId,
                $this->_mCategoryId,
                $target_category_id
            );
            header('Location: ' .
                htmlspecialchars_decode(
                    Link::ToProductAdmin(
                        $this->_mDepartmentId,
                        $target_category_id,
                        $this->_mProductId
                    )
                ));
            exit();
        }
        // Si on assigne une valeur d'attribut au produit ...
        if (isset($_POST['AssignAttributeValue'])) {
            $target_attribute_value_id = $_POST['TargetAttributeValueIdAssign'];
            Catalog::AssignAttributeValueToProduct(
                $this->_mProductId,
                $target_attribute_value_id
            );
        }
        // Si on retire une valeur d'attribut du produit ...
        if (isset($_POST['RemoveAttributeValue'])) {
            $target_attribute_value_id = $_POST['TargetAttributeValueIdRemove'];
            Catalog::RemoveProductAttributeValue(
                $this->_mProductId,
                $target_attribute_value_id
            );
        }
        // Si on déplace le produit vers une autre catégorie (répété, mais inclus pour la fidélité) ...
        if (isset($_POST['Move'])) {
            $target_category_id = $_POST['TargetCategoryIdMove'];
            Catalog::MoveProductToCategory(
                $this->_mProductId,
                $this->_mCategoryId,
                $target_category_id
            );
            header('Location: ' .
                htmlspecialchars_decode(
                    Link::ToProductAdmin(
                        $this->_mDepartmentId,
                        $target_category_id,
                        $this->_mProductId
                    )
                ));
            exit();
        }
        // Obtenir les informations du produit
        $this->mProduct = Catalog::GetProductInfo($this->_mProductId);
        $product_categories = Catalog::GetCategoriesForProduct($this->_mProductId);
        $product_attributes =
            Catalog::GetProductAttributes($this->_mProductId);
        for ($i = 0; $i < count($product_attributes); $i++)
            $this->mProductAttributes[$product_attributes[$i]['attribute_value_id']] =
                $product_attributes[$i]['attribute_name'] . ': ' .
                $product_attributes[$i]['attribute_value'];
        $catalog_attributes =
            Catalog::GetAttributesNotAssignedToProduct($this->_mProductId);
        for ($i = 0; $i < count($catalog_attributes); $i++)
            $this->mCatalogAttributes[$catalog_attributes[$i]['attribute_value_id']] =
                $catalog_attributes[$i]['attribute_name'] . ': ' .
                $catalog_attributes[$i]['attribute_value'];
        if (count($product_categories) == 1)
            $this->mRemoveFromCategoryButtonDisabled = true;
        // Afficher les catégories auxquelles le produit appartient
        for ($i = 0; $i < count($product_categories); $i++)
            $temp1[$product_categories[$i]['category_id']] =
                $product_categories[$i]['name'];
        $this->mRemoveFromCategories = $temp1;
        $this->mProductCategoriesString = implode(', ', $temp1);
        $all_categories = Catalog::GetCategories();
        for ($i = 0; $i < count($all_categories); $i++)
            $temp2[$all_categories[$i]['category_id']] =
                $all_categories[$i]['name'];
        $this->mAssignOrMoveTo = array_diff($temp2, $temp1);
    }
}
