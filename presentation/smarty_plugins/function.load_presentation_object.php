<?php
// Les fonctions de plug-in dans les fichiers de plug-in doivent être nommées : smarty_type_name
function smarty_function_load_presentation_object($params, $smarty)
{
    require_once PRESENTATION_DIR . $params['filename'] . '.php';

    // Construit le nom de la classe (ex: 'departments_list' devient 'DepartmentsList')
    $className = str_replace(
        ' ',
        '',
        ucfirst(str_replace(
            '_',
            ' ',
            $params['filename']
        ))
    );

    // Crée l'objet de présentation
    $obj = new $className();

    // Appelle la méthode init() si elle existe
    if (method_exists($obj, 'init')) {
        $obj->init();
    }

    // Affecte la variable de template
    $smarty->assign($params['assign'], $obj);
}
