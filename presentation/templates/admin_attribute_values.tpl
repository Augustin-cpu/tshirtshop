{* admin_attribute_values.tpl *}
{load_presentation_object filename="admin_attribute_values" assign="obj"}
<form method="post" action="{$obj->mLinkToAttributeValuesAdmin}">
    <h3>
        Modification des valeurs pour l'attribut : {$obj->mAttributeName} [
        <a href="{$obj->mLinkToAttributesAdmin}">retour aux attributs ...</a> ]
    </h3>
    {if $obj->mErrorMessage}<p class="error">{$obj->mErrorMessage}</p>{/if}
    {if $obj->mAttributeValuesCount eq 0}
    <p class="no-items-found">Il n'y a aucune valeur pour cet attribut !</p>
    {else}
    <table class="tss-table">
        <tr>
            <th>Valeur de l'attribut</th>
                <th width="170"> </th>
            </tr>
            {section name=i loop=$obj->mAttributeValues}

                    {if $obj->mEditItem == $obj->mAttributeValues[i].attribute_value_id}
                    <tr>
                        <td>
                            <input type="text" name="value" value="{$obj->mAttributeValues[i].value}" size="30" />
                        </td>
                        <td>
                            <input type="submit" name="submit_update_val_{$obj->mAttributeValues[i].attribute_value_id}"
                                value="Mettre à jour" />
                            <input type="submit" name="cancel" value="Annuler" />
                            <input type="submit" name="submit_delete_val_{$obj->mAttributeValues[i].attribute_value_id}"
                                value="Supprimer" />
                        </td>
                    </tr>

                    {else}
                    <tr>
                        <td>{$obj->mAttributeValues[i].value}</td>
                        <td>
                            <input type="submit" name="submit_edit_val_{$obj->mAttributeValues[i].attribute_value_id}"
                                value="Modifier" />
                            <input type="submit" name="submit_delete_val_{$obj->mAttributeValues[i].attribute_value_id}"
                                value="Supprimer" />
                        </td>
                    </tr>

                    {/if}

                {/section}
        </table>

            {/if}
    <h3>Ajouter une nouvelle valeur d'attribut :</h3>
    <input type="text" name="attribute_value" value="[valeur]" size="30" />
    <input type="submit" name="submit_add_val_0" value="Ajouter" />
</form>