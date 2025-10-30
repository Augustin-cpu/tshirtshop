<?php
// Gère la liste des départements
class DepartmentsList
{
    /* Variables publiques disponibles dans le template Smarty departments_list.tpl */
    public $mSelectedDepartment = 0;
    public $mDepartments;

    // Le constructeur lit le paramètre de la chaîne de requête
    public function __construct()
    {
        /* Si DepartmentId existe dans la chaîne de requête, nous visitons un département */
        if (isset($_GET['DepartmentId']))
            $this->mSelectedDepartment = (int)$_GET['DepartmentId'];
    }

    /* Appelle la méthode du niveau métier pour lire la liste des départements et créer leurs liens */
    public function init()
    {
        // Obtient la liste des départements depuis le niveau métier
        $this->mDepartments = Catalog::GetDepartments();

        // Crée les liens de département (Mise en évidence)
        for ($i = 0; $i < count($this->mDepartments); $i++)
            $this->mDepartments[$i]['link_to_department'] =
                Link::ToDepartment($this->mDepartments[$i]['department_id']);
    }
}
