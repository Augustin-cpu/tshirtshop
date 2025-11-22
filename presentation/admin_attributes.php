<?php
// Classe qui prend en charge la fonctionnalité d'administration des attributs
class AdminAttributes
{
    // Variables publiques disponibles dans le gabarit Smarty
    public $mAttributesCount;
    public $mAttributes;
    public $mErrorMessage;
    public $mEditItem;
    public $mLinkToAttributesAdmin;

    public $mLinkToAttributeValuesAdmin;
    // Membres privés
    private $_mAction;
    private $_mActionedAttributeId;
    // Constructeur de la classe
    public function __construct()
    {
        // Analyser la liste avec les variables POST
        foreach ($_POST as $key => $value)
            // Si un bouton de soumission a été cliqué ...
            if (substr($key, 0, 6) == 'submit') { /* Obtenir la position du dernier '_' (underscore) à partir du nom du
bouton de soumission, par exemple strrpos('submit_edit_attr_1', '_') est 17 */
                $last_underscore = strrpos($key, '_');
                /* Obtenir la portée du bouton de soumission
(par exemple 'edit_dep' à partir de 'submit_edit_attr_1') */
                $this->_mAction = substr(
                    $key,
                    strlen('submit_'),
                    $last_underscore - strlen('submit_')
                );
                /* Obtenir l'ID de l'attribut ciblé par le bouton de soumission
(le nombre à la fin du nom du bouton de soumission)
par exemple '1' à partir de 'submit_edit_attr_1' */
                $this->_mActionedAttributeId = substr($key, $last_underscore + 1);
                break;
            }
        $this->mLinkToAttributesAdmin = Link::ToAttributesAdmin();
    }
    public function init()
    {
        // Si on ajoute un nouvel attribut ...
        if ($this->_mAction == 'add_attr') {
            $attribute_name = $_POST['attribute_name'];
            if ($attribute_name == null)
                $this->mErrorMessage = 'Nom de l\'attribut requis';
            if ($this->mErrorMessage == null) {
                Catalog::AddAttribute($attribute_name);
                header('Location: ' . $this->mLinkToAttributesAdmin);
            }
        }
        // Si on modifie un attribut existant ...
        if ($this->_mAction == 'edit_attr')
            $this->mEditItem = $this->_mActionedAttributeId;
        // Si on met à jour un attribut ...
        if ($this->_mAction == 'update_attr') {
            $attribute_name = $_POST['name'];
            if ($attribute_name == null)
                $this->mErrorMessage = 'Nom de l\'attribut requis';
            if ($this->mErrorMessage == null) {
                Catalog::UpdateAttribute(
                    $this->_mActionedAttributeId,
                    $attribute_name
                );
                header('Location: ' . $this->mLinkToAttributesAdmin);
            }
        }
        // Si on supprime un attribut ...
        if ($this->_mAction == 'delete_attr') {
            $status = Catalog::DeleteAttribute($this->_mActionedAttributeId);
            if ($status < 0)
                $this->mErrorMessage =
                    'L\'attribut a une ou plusieurs valeurs et ne peut être supprimé';
            else
                header('Location: ' . $this->mLinkToAttributesAdmin);
        }
        // Si on modifie une valeur d'attribut ...
        if ($this->_mAction == 'edit_val') {
            header('Location: ' .
                htmlspecialchars_decode(
                    Link::ToAttributeValuesAdmin(
                        $this->_mActionedAttributeId
                    )
                ));
            exit();
        }
        // Charger la liste des attributs
        $this->mAttributes = Catalog::GetAttributes();
        $this->mAttributesCount = count($this->mAttributes);
    }
}
