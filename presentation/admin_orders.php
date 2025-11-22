<?php
/* Classe de la couche présentation qui supporte la fonctionnalité
d'administration des commandes */
class AdminOrders
{
// Variables publiques disponibles dans le modèle Smarty
    public $mOrders;
    public $mStartDate;
    public $mEndDate;
    public $mRecordCount = 20;
    public $mOrderStatusOptions;
    public $mSelectedStatus = 0;
    public $mErrorMessage = '';
    public $mLinkToAdmin;

// Constructeur de la classe
    public function __construct()
    {
        /* Sauvegarder le lien vers la page actuelle dans la variable de session
        link_to_orders_admin ; il sera utilisé pour créer le lien
        "retour aux commandes admin..." dans les pages de détails de commande admin */
        $_SESSION['link_to_orders_admin'] =
            Link::Build(str_replace(VIRTUAL_LOCATION, '', getenv('REQUEST_URI')));
        $this->mLinkToAdmin = Link::ToAdmin();
        $this->mOrderStatusOptions = Orders::$mOrderStatusOptions;
    }

    public function init()
    {
        $this->mOrders = array();
// Si le filtre "Afficher les x commandes les plus récentes" est actif...
        if (isset ($_GET['submitMostRecent']))
        {
// Si la valeur du nombre d'enregistrements n'est pas un entier valide, afficher l'erreur
            if ((string)(int)$_GET['recordCount'] == (string)$_GET['recordCount'])
            {
                $this->mRecordCount = (int)$_GET['recordCount'];
                $this->mOrders = Orders::GetMostRecentOrders($this->mRecordCount);
            }
            else
                $this->mErrorMessage = $_GET['recordCount'] . ' n\'est pas un nombre.';
        }

        /* Si le filtre "Afficher tous les enregistrements créés entre date_1 et date_2"
        est actif... */
        if (isset ($_GET['submitBetweenDates']))
        {
            $this->mStartDate = $_GET['startDate'];
            $this->mEndDate = $_GET['endDate'];
// Vérifier si la date de début est dans un format accepté
            if (($this->mStartDate == '') ||
                ($timestamp = strtotime($this->mStartDate)) == -1)
                $this->mErrorMessage = 'La date de début est invalide. ';
            else
// Transformer la date au format AAAA/MM/JJ HH:MM:SS
                $this->mStartDate =
                    strftime('%Y/%m/%d %H:%M:%S', strtotime($this->mStartDate));
// Vérifier si la date de fin est dans un format accepté
            if (($this->mEndDate == '') ||
                ($timestamp = strtotime($this->mEndDate)) == -1)
                $this->mErrorMessage .= 'La date de fin est invalide.';
            else
// Transformer la date au format AAAA/MM/JJ HH:MM:SS
                $this->mEndDate =
                    strftime('%Y/%m/%d %H:%M:%S', strtotime($this->mEndDate));

// Vérifier si la date de début est plus récente que la date de fin
            if ((empty ($this->mErrorMessage)) &&
                (strtotime($this->mStartDate) > strtotime($this->mEndDate)))
                $this->mErrorMessage .=
                    'La date de début doit être antérieure à la date de fin.';

// S'il n'y a pas d'erreurs, obtenir les commandes entre les deux dates
            if (empty($this->mErrorMessage))
                $this->mOrders = Orders::GetOrdersBetweenDates(
                    $this->mStartDate, $this->mEndDate);
        }

// Si le filtre "Afficher les commandes par statut" est actif...
        if (isset ($_GET['submitOrdersByStatus']))
        {
            $this->mSelectedStatus = $_GET['status'];
            $this->mOrders = Orders::GetOrdersByStatus($this->mSelectedStatus);
        }

        if (is_array($this->mOrders) && count($this->mOrders) == 0)
            $this->mErrorMessage =
                'Aucune commande trouvée correspondant à vos critères de recherche !';

        for ($i = 0; $i < count($this->mOrders); $i++)
        {
            $this->mOrders[$i]['link_to_order_details_admin'] =
                Link::ToOrderDetailsAdmin($this->mOrders[$i]['order_id']);
        }
    }
}
?>