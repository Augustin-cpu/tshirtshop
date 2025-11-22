<?php
// Classe qui gère l'administration des valeurs d'attribut
class AdminAttributeValues
{
    // Variables publiques disponibles dans le gabarit Smarty
    public $mAttributeValuesCount;
    public $mAttributeValues;
    public $mErrorMessage;
    public $mEditItem;
    public $mAttributeId;
    public $mAttributeName;
    public $mLinkToAttributeAdmin;
    public $mLinkToAttributeValuesAdmin;
    public $mLinkToAttributesAdmin;
    // Membres privés
    private $_mAction;
    private $_mActionedAttributeValueId;
    // Constructeur de la classe
    public function __construct()
    {
        if (isset($_GET['AttributeId']))
            $this->mAttributeId = (int)$_GET['AttributeId'];
        else
            trigger_error('AttributeId non défini');
        $attribute_details = Catalog::GetAttributeDetails($this->mAttributeId);
        $this->mAttributeName = $attribute_details['name'];
        foreach ($_POST as $key => $value)
            // Si un bouton de soumission a été cliqué ...
            if (substr($key, 0, 6) == 'submit') {
                /* Obtenir la position du dernier '_' (underscore) à partir du nom du
bouton de soumission, par exemple strrpos('submit_edit_val_1', '_') est 16 */
                $last_underscore = strrpos($key, '_');
                /* Obtenir la portée du bouton de soumission
(par exemple 'edit_cat' à partir de 'submit_edit_val_1') */
                $this->_mAction = substr(
                    $key,
                    strlen('submit_'),
                    $last_underscore - strlen('submit_')
                );
                /* Obtenir l'ID de la valeur d'attribut ciblée par le bouton de soumission
(le nombre à la fin du nom du bouton de soumission)
par exemple '1' à partir de 'submit_edit_val_1' */
                $this->_mActionedAttributeValueId =
                    (int)substr($key, $last_underscore + 1);
                break;
            }
        $this->mLinkToAttributesAdmin = Link::ToAttributesAdmin();
        $this->mLinkToAttributeValuesAdmin = Link::ToAttributeValuesAdmin($this->mAttributeId);
    }
    public function init()
    {
        // Si on ajoute une nouvelle valeur d'attribut ...
        if ($this->_mAction == 'add_val') {
            $attribute_value = $_POST['attribute_value'];
            if ($attribute_value == null)
                $this->mErrorMessage = 'La valeur d\'attribut est vide';
            if ($this->mErrorMessage == null) {
                Catalog::AddAttributeValue($this->mAttributeId, $attribute_value);
                header('Location: ' .
                    htmlspecialchars_decode(
                        $this->mLinkToAttributeValuesAdmin
                    ));
            }
        }
        // Si on modifie une valeur d'attribut existante ...
        if ($this->_mAction == 'edit_val') {
            $this->mEditItem = $this->_mActionedAttributeValueId;
        }
        // Si on met à jour une valeur d'attribut ...
        if ($this->_mAction == 'update_val') {
            $attribute_value = $_POST['value'];
            if ($attribute_value == null)
                $this->mErrorMessage = 'La valeur d\'attribut est vide';
            if ($this->mErrorMessage == null) {
                Catalog::UpdateAttributeValue(
                    $this->_mActionedAttributeValueId,
                    $attribute_value
                );
                header('Location: ' .
                    htmlspecialchars_decode(
                        $this->mLinkToAttributeValuesAdmin
                    ));
            }
        }
        // Si on supprime une valeur d'attribut ...
        if ($this->_mAction == 'delete_val') {
            $status =
                Catalog::DeleteAttributeValue($this->_mActionedAttributeValueId);
            if ($status < 0)
                $this->mErrorMessage = 'Impossible de supprimer cette valeur d\'attribut. ' .
                    'Un ou plusieurs produits l\'utilisent !';
            else
                header('Location: ' .
                    htmlspecialchars_decode(
                        $this->mLinkToAttributeValuesAdmin
                    ));
        }
        // Charger la liste des valeurs d'attribut
        $this->mAttributeValues =
            Catalog::GetAttributeValues($this->mAttributeId);
        $this->mAttributeValuesCount = count($this->mAttributeValues);
    }
}
