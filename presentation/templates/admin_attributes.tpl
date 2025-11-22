{* admin_attributes.tpl *}
{load_presentation_object filename="admin_attributes" assign="obj"}
<form method="post" action="{$obj->mLinkToAttributesAdmin}">
    <h3>Modifier les attributs de produit de TShirtShop :</h3>
    {if $obj->mErrorMessage}<p class="error">{$obj->mErrorMessage}</p>{/if}
    {if $obj->mAttributesCount eq 0}
        <p class="no-items-found">
        Il n'y a aucun attribut de produit dans votre base de données !
    </p>
    {else}
    <table class="tss-table">
        <tr>
            <th>Nom de l'attribut</th>
                <th width="240"> </th>
            </tr>
            {section name=i loop=$obj->mAttributes}
                {if $obj->mEditItem == $obj->mAttributes[i].attribute_id}
                    <tr>
                        <td>
                            <input type="text" name="name" value="{$obj->mAttributes[i].name}" size="30" />
                        </td>
                        <td>
                            <input type="submit" name="submit_edit_attr_val_{$obj->mAttributes[i].attribute_id}"
                                value="Modifier les valeurs d'attribut" />
                            <input type="submit" name="submit_update_attr_{$obj->mAttributes[i].attribute_id}"
                                value="Mettre à jour" />
                            <input type="submit" name="cancel" value="Annuler" />
                            <input type="submit" name="submit_delete_attr_{$obj->mAttributes[i].attribute_id}" value="Supprimer" />
                        </td>
                    </tr>
                {else}
                    <tr>
                        <td>{$obj->mAttributes[i].name}</td>
                        <td> <input type="submit" name="submit_edit_val_{$obj->mAttributes[i].attribute_id}"
                                value="Modifier les valeurs d'attribut" />
                            <input type="submit" name="submit_edit_attr_{$obj->mAttributes[i].attribute_id}" value="Modifier" />
                            <input type="submit" name="submit_delete_attr_{$obj->mAttributes[i].attribute_id}" value="Supprimer" />
                        </td>
                    </tr>
                {/if}
            {/section}
        </table>
    {/if}
    <h3>Ajouter un nouvel attribut :</h3>
    <p>
        <input type="text" name="attribute_name" value="[nom]" size="30" />
        <input type="submit" name="submit_add_attr_0" value="Ajouter" />
    </p>
</form>