<?php
// Classe de niveau métier pour lire les informations du catalogue de produits 
class Catalog
{
  // Récupère tous les départements
  public static function GetDepartments()
  {
    // Construit la requête SQL
    $sql = 'CALL catalog_get_departments_list()';
    // Exécute la requête et renvoie les résultats
    return DatabaseHandler::GetAll($sql);
  }
}
?>