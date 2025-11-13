<?php
// Classe qui prend en charge la fonctionnalité d'administration des départements
class AdminDepartments
{
    // Variables publiques disponibles dans le gabarit Smarty
    public $mDepartmentsCount;
    public $mDepartments;
    public $mErrorMessage;
    public $mEditItem;
    public $mLinkToDepartmentsAdmin;
    // Membres privés
    private $_mAction;
    private $_mActionedDepartmentId;
    // Constructeur de classe
    public function __construct()
    {
        // Analyser la liste avec les variables POSTées
        foreach ($_POST as $key => $value)
            // Si un bouton de soumission a été cliqué ...
            if (substr($key, 0, 6) == 'submit') {
                /* Obtenir la position du dernier '_' (underscore) à partir du nom du bouton de soumission
par exemple strrpos('submit_edit_dept_1', '_') est 17 */
                $last_underscore = strrpos($key, '_');
                /* Obtenir la portée du bouton de soumission
(par exemple 'edit_dept' à partir de 'submit_edit_dept_1') */
                $this->_mAction = substr(
                    $key,
                    strlen('submit_'),
                    $last_underscore - strlen('submit_')
                );
                /* Obtenir l'ID du département ciblé par le bouton de soumission
(le nombre à la fin du nom du bouton de soumission)
par exemple '1' à partir de 'submit_edit_dept_1' */
                $this->_mActionedDepartmentId = substr($key, $last_underscore + 1);
                break;
            }
        $this->mLinkToDepartmentsAdmin = Link::ToDepartmentsAdmin();
    }
    public function init()
    {
        // Si un nouveau département est ajouté ...
        if ($this->_mAction == 'add_dept') {
            $department_name = $_POST['department_name'];
            $department_description = $_POST['department_description'];
            if ($department_name == null)
                $this->mErrorMessage = 'Le nom du département est requis';
            if ($this->mErrorMessage == null) {
                Catalog::AddDepartment($department_name, $department_description);
                header('Location: ' . $this->mLinkToDepartmentsAdmin);
            }
        }
        // Si un département existant est en cours d'édition ...
        if ($this->_mAction == 'edit_dept')
            $this->mEditItem = $this->_mActionedDepartmentId;
        // Si un département est mis à jour ...
        if ($this->_mAction == 'update_dept') {
            $department_name = $_POST['name'];
            $department_description = $_POST['description'];
            if ($department_name == null)
                $this->mErrorMessage = 'Le nom du département est requis';
            if ($this->mErrorMessage == null) {
                Catalog::UpdateDepartment(
                    $this->_mActionedDepartmentId,
                    $department_name,
                    $department_description
                );
                header('Location: ' . $this->mLinkToDepartmentsAdmin);
            }
        }
        // Si un département est supprimé ...
        if ($this->_mAction == 'delete_dept') {
            $status = Catalog::DeleteDepartment($this->_mActionedDepartmentId);
            if ($status < 0)
                $this->mErrorMessage = 'Le département n\'est pas vide';
            else
                header('Location: ' . $this->mLinkToDepartmentsAdmin);
        }
        // Si les catégories du département sont modifiées ...
        if ($this->_mAction == 'edit_cat') {
            header('Location: ' .
                htmlspecialchars_decode(
                    Link::ToDepartmentCategoriesAdmin(
                        $this->_mActionedDepartmentId
                    )
                ));
            exit();
        }
        // Charger la liste des départements
        $this->mDepartments = Catalog::GetDepartmentsWithDescriptions();
        $this->mDepartmentsCount = count($this->mDepartments);
    }
}
