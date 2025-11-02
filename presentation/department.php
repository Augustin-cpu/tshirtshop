<?php
// Gère la récupération des détails du département
class Department
{
  // Variables publiques pour le gabarit Smarty
  public $mName;
  public $mDescription;

  // Membres privés
  private $_mDepartmentId;
  private $_mCategoryId;

  // Constructeur de classe
  public function __construct()
  {
    // Nous devons avoir DepartmentId dans la chaîne de requête
    if (isset ($_GET['DepartmentId']))
      $this->_mDepartmentId = (int)$_GET['DepartmentId'];
    else
      trigger_error('DepartmentId not set');

    /* Si CategoryId est dans la chaîne de requête, nous le sauvegardons 
    (en le transtypant en entier pour se protéger contre les valeurs invalides) */
    if (isset ($_GET['CategoryId']))
      $this->_mCategoryId = (int)$_GET['CategoryId'];
  }

  public function init()
  {
    // Si l'on visite un département ...
    $department_details =
      Catalog::GetDepartmentDetails($this->_mDepartmentId);
    $this->mName = $department_details['name'];
    $this->mDescription = $department_details['description'];

    // Si l'on visite une catégorie ...
    if (isset ($this->_mCategoryId))
    {
      $category_details =
        Catalog::GetCategoryDetails($this->_mCategoryId); 
      $this->mName = $this->mName . ' » ' .
        $category_details['name'];
      $this->mDescription = $category_details['description'];
    }
  }
}
?>