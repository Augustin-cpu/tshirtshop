{* admin_carts.tpl *}
{load_presentation_object filename="admin_carts" assign="obj"}
<form action="{$obj->mLinkToCartsAdmin}" method="post">
    <h3>Administrer les paniers d'achat des utilisateurs :</h3>
    {if $obj->mMessage}<p>{$obj->mMessage}</p>{/if}
    <p>
        Sélectionner les paniers :
        {html_options name="days" options=$obj->mDaysOptions
        selected=$obj->mSelectedDaysNumber}
        <input type="submit" name="submit_count" value="Compter les anciens paniers" />
        <input type="submit" name="submit_delete"
               value="Supprimer les anciens paniers" />
    </p>
</form>