<?php
// Classe de la couche présentation qui gère l'administration des détails de commande
class AdminOrderDetails
{
// Variables publiques disponibles dans le modèle Smarty
    public $mOrderId;
    public $mOrderInfo;
    public $mOrderDetails;
    public $mEditEnabled;
    public $mOrderStatusOptions;
    public $mLinkToAdmin;
    public $mLinkToOrdersAdmin;

// Constructeur de la classe
    public function __construct()
    {
// Obtenir le lien de retour de la session
        $this->mLinkToOrdersAdmin = $_SESSION['link_to_orders_admin'];
        $this->mLinkToAdmin = Link::ToAdmin();
// Nous recevons l'ID de commande dans la chaîne de requête
        if (isset ($_GET['OrderId']))
            $this->mOrderId = (int) $_GET['OrderId'];
        else
            trigger_error('Le paramètre OrderId est requis');
        $this->mOrderStatusOptions = Orders::$mOrderStatusOptions;
    }

// Initialise les membres de la classe
    public function init()
    {
        if (isset ($_GET['submitUpdate']))
        {
            Orders::UpdateOrder($this->mOrderId, $_GET['status'],
                $_GET['comments'], $_GET['customerName'], $_GET['shippingAddress'],
                $_GET['customerEmail']);
        }

        $this->mOrderInfo = Orders::GetOrderInfo($this->mOrderId);
        $this->mOrderDetails = Orders::GetOrderDetails($this->mOrderId);

// Valeur qui spécifie s'il faut activer ou désactiver le mode édition
        if (isset ($_GET['submitEdit']))
            $this->mEditEnabled = true;
        else
            $this->mEditEnabled = false;
    }
}
?>