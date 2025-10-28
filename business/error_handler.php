<?php
class ErrorHandler
{
    // Constructeur privé pour empêcher la création directe d'objet
    private function __construct() {}
    public static function SetHandler($errTypes = ERROR_TYPES)
    {
        return set_error_handler(array('ErrorHandler', 'Handler'), $errTypes);
    }

    // Méthode de gestionnaire d'erreurs
    public static function Handler($errNo, $errStr, $errFile, $errLine)
    {
        /* 
        Les deux premiers éléments du tableau backtrace sont non pertinents :
        - ErrorHandler.GetBacktrace
        - ErrorHandler.Handler 
        */
        $backtrace = ErrorHandler::GetBacktrace(2);

        // Message d'erreur à afficher, journaliser ou envoyer par mail
        $error_message = "\nERRNO: $errNo\nTEXT: $errStr" .
            "\nLOCATION: $errFile, line " .
            "$errLine, at " . date('F j, Y, g:i a') .
            "\nShowing backtrace:\n$backtrace\n\n";

        // Envoyer les détails de l'erreur par mail, si SEND_ERROR_MAIL est vrai
        if (SEND_ERROR_MAIL == true)
            error_log($error_message, 1, ADMIN_ERROR_MAIL, "From: " .
                SENDMAIL_FROM . "\r\nTo: " . ADMIN_ERROR_MAIL);

        // Journaliser l'erreur, si LOG_ERRORS est vrai
        if (LOG_ERRORS == true)
            error_log($error_message, 3, LOG_ERRORS_FILE);

        /* Les avertissements n'interrompent pas l'exécution si IS_WARNING_FATAL est faux
        Les erreurs E_NOTICE et E_USER_NOTICE n'interrompent pas l'exécution */
        if (($errNo == E_WARNING && IS_WARNING_FATAL == false) ||
            ($errNo == E_NOTICE || $errNo == E_USER_NOTICE)
        )
        // Si l'erreur n'est pas fatale ...
        {
            // Afficher le message seulement si DEBUGGING est vrai
            if (DEBUGGING == true)
                echo '<div class="error_box"><pre>' . $error_message . '</pre></div>';
        } else
        // Si l'erreur est fatale ...
        {
            // Afficher le message d'erreur
            if (DEBUGGING == true)
                echo '<div class="error_box"><pre>' . $error_message . '</pre></div>';
            else
                echo SITE_GENERIC_ERROR_MESSAGE; // Afficher le message d'erreur générique du site
            // Arrêter le traitement de la requête
            exit();
        }
    }

    // Construit le message de trace de la pile
    public static function GetBacktrace($irrelevantFirstEntries)
    {
        $s = '';
        $MAXSTRLEN = 64;
        $trace_array = debug_backtrace();

        for ($i = 0; $i < $irrelevantFirstEntries; $i++)
            array_shift($trace_array);

        $tabs = sizeof($trace_array) - 1;
        foreach ($trace_array as $arr) {
            $tabs -= 1;
            if (isset($arr['class']))
                $s .= $arr['class'] . '.';
            $args = array();
            if (!empty($arr['args']))
                foreach ($arr['args'] as $v) {
                    if (is_null($v))
                        $args[] = 'null';
                    elseif (is_array($v))
                        $args[] = 'Array[' . sizeof($v) . ']';
                    elseif (is_object($v))
                        $args[] = 'Object: ' . get_class($v);
                    elseif (is_bool($v))
                        $args[] = $v ? 'true' : 'false';
                    else {
                        $v = (string)@$v;
                        $str = htmlspecialchars(substr($v, 0, $MAXSTRLEN));
                        if (strlen($v) > $MAXSTRLEN)
                            $str .= '...';
                        $args[] = '"' . $str . '"';
                    }
                }
            $s .= $arr['function'] . '(' . implode(', ', $args) . ')';
            $line = (isset($arr['line']) ? $arr['line'] : 'unknown');
            $file = (isset($arr['file']) ? $arr['file'] : 'unknown');
            $s .= sprintf(' # line %4d, file: %s', $line, $file);
            $s .= "\n";
        }
        return $s;
    }
}
