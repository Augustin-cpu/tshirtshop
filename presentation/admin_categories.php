<?php
// Classe qui gère l'administration des catégories
class AdminCategories
{
    // Variables publiques disponibles dans le gabarit Smarty
    public $mCategoriesCount;
    public $mCategories;
    public $mErrorMessage;
    public $mEditItem;
    public $mDepartmentId;
    public $mDepartmentName;
    public $mLinkToDepartmentsAdmin;
    public $mLinkToDepartmentCategoriesAdmin;
    // Membres privés
    private $_mAction;
    private $_mActionedCategoryId;
    // Constructeur de classe
    public function __construct()
    {
        if (isset($_GET['DepartmentId']))
            $this->mDepartmentId = (int)$_GET['DepartmentId'];
        else
            trigger_error('DepartmentId non défini');
        $department_details = Catalog::GetDepartmentDetails($this->mDepartmentId);
        $this->mDepartmentName = $department_details['name'];
        foreach ($_POST as $key => $value)
            // Si un bouton de soumission a été cliqué ...
            if (substr($key, 0, 6) == 'submit') {
                /* Obtenir la position du dernier '_' (underscore) à partir du nom du bouton de soumission
par exemple strrpos('submit_edit_cat_1', '_') est 16 */
                $last_underscore = strrpos($key, '_');
                /* Obtenir la portée du bouton de soumission
(par exemple 'edit_cat' à partir de 'submit_edit_cat_1') */
                $this->_mAction = substr(
                    $key,
                    strlen('submit_'),
                    $last_underscore - strlen('submit_')
                );
                /* Obtenir l'ID de la catégorie ciblée par le bouton de soumission
(le nombre à la fin du nom du bouton de soumission)
par exemple '1' à partir de 'submit_edit_cat_1' */
                $this->_mActionedCategoryId = (int)substr($key, $last_underscore + 1);
                break;
            }
        $this->mLinkToDepartmentsAdmin = Link::ToDepartmentsAdmin();
        $this->mLinkToDepartmentCategoriesAdmin =
            Link::ToDepartmentCategoriesAdmin($this->mDepartmentId);
    }
    public function init()
    {
        // Si une nouvelle catégorie est ajoutée ...
        if ($this->_mAction == 'add_cat') {
            $category_name = $_POST['category_name'];
            $category_description = $_POST['category_description'];
            if ($category_name == null)
                $this->mErrorMessage = 'Le nom de la catégorie est vide';
            if ($this->mErrorMessage == null) {
                Catalog::AddCategory(
                    $this->mDepartmentId,
                    $category_name,
                    $category_description
                );
                header('Location: ' .
                    htmlspecialchars_decode(
                        $this->mLinkToDepartmentCategoriesAdmin
                    ));
            }
        }
        // Si une catégorie existante est en cours d'édition ...
        if ($this->_mAction == 'edit_cat') {
            $this->mEditItem = $this->_mActionedCategoryId;
        }
        // Si une catégorie est mise à jour ...
        if ($this->_mAction == 'update_cat') {
            $category_name = $_POST['name'];
            $category_description = $_POST['description'];
            if ($category_name == null)
                $this->mErrorMessage = 'Le nom de la catégorie est vide';
            if ($this->mErrorMessage == null) {
                Catalog::UpdateCategory(
                    $this->_mActionedCategoryId,
                    $category_name,
                    $category_description
                );
                header('Location: ' .
                    htmlspecialchars_decode(
                        $this->mLinkToDepartmentCategoriesAdmin
                    ));
            }
        }
        // Si une catégorie est supprimée ...
        if ($this->_mAction == 'delete_cat') {
            $status = Catalog::DeleteCategory($this->_mActionedCategoryId);
            if ($status < 0)
                $this->mErrorMessage = 'La catégorie n\'est pas vide';
            else
                header('Location: ' .
                    htmlspecialchars_decode(
                        $this->mLinkToDepartmentCategoriesAdmin
                    ));
        }
        // Si les produits de la catégorie sont modifiés ...
        if ($this->_mAction == 'edit_prod') {
            header('Location: ' .
                htmlspecialchars_decode(
                    Link::ToCategoryProductsAdmin(
                        $this->mDepartmentId,
                        $this->_mActionedCategoryId
                    )
                ));
            exit();
        }
        // Charger la liste des catégories
        $this->mCategories =
            Catalog::GetDepartmentCategories($this->mDepartmentId);
        $this->mCategoriesCount = count($this->mCategories);
    }
}
