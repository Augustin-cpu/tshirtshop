<?php
class Link
{
    public static function Build($link)
    {
        $base = 'http://' . getenv('SERVER_NAME');

        // Si HTTP_SERVER_PORT est défini et différent de la valeur par défaut
        if (defined('HTTP_SERVER_PORT') && HTTP_SERVER_PORT != '80') {
            // Ajoute le port du serveur
            $base .= ':' . HTTP_SERVER_PORT;
        }

        // Construit le lien absolu
        $link = $base . VIRTUAL_LOCATION . $link;

        // Échappe le HTML (pour la sécurité)
        return htmlspecialchars($link, ENT_QUOTES);
    }

    public static function ToDepartment($departmentId)
    {
        // Construit le lien relatif pour un département
        $link = 'index.php?DepartmentId=' . $departmentId;

        // Utilise la méthode Build pour le rendre absolu et sécurisé
        return self::Build($link);
    }
    public static function ToCategory($departmentId, $categoryId)
    {
        $link = 'index.php?DepartmentId=' . $departmentId .
            '&CategoryId=' . $categoryId;
        return self::Build($link);
    }
}
