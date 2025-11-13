{* admin_categories.tpl *}
{load_presentation_object filename="admin_categories" assign="obj"}
<form method="post" action="{$obj->mLinkToDepartmentCategoriesAdmin}">
    <h3>
        Modification des catégories pour le département : {$obj->mDepartmentName} [
        <a href="{$obj->mLinkToDepartmentsAdmin}">retour aux départements ...</a> ]
    </h3>
    {if $obj->mErrorMessage}<p class="error">{$obj->mErrorMessage}</p>{/if}
    {if $obj->mCategoriesCount eq 0}
        <p class="no-items-found">Il n'y a aucune catégorie dans ce département !</p>
    {else}
        <table class="tss-table">
            <tr>
                <th width="200">Nom de la Catégorie</th>
                <th>Description de la Catégorie</th>
                <th width="240"> </th>
            </tr>
            {section name=i loop=$obj->mCategories}
                {if $obj->mEditItem == $obj->mCategories[i].category_id}
                    <tr>
                        <td>
                            <input type="text" name="name" value="{$obj->mCategories[i].name}" size="30" />
                        </td>
                        <td>
                            {strip}
                                <textarea name="description" rows="3" cols="60">
                {$obj->mCategories[i].description}
                </textarea>
                            {/strip}
                        </td>
                        <td>
                            <input type="submit" name="submit_edit_prod_{$obj->mCategories[i].category_id}"
                                value="Modifier Produits" />
                            <input type="submit" name="submit_update_cat_{$obj->mCategories[i].category_id}"
                                value="Mettre à jour" />
                            <input type="submit" name="cancel" value="Annuler" />
                            <input type="submit" name="submit_delete_cat_{$obj->mCategories[i].category_id}" value="Supprimer" />
                        </td>
                    </tr>
                {else}
                    <tr>
                        <td>{$obj->mCategories[i].name}</td>
                        <td>{$obj->mCategories[i].description}</td>
                        <td>
                            <input type="submit" name="submit_edit_prod_{$obj->mCategories[i].category_id}"
                                value="Modifier Produits" />
                            <input type="submit" name="submit_edit_cat_{$obj->mCategories[i].category_id}" value="Modifier" />
                            <input type="submit" name="submit_delete_cat_{$obj->mCategories[i].category_id}" value="Supprimer" />
                        </td>
                    </tr>
                {/if}
            {/section}
        </table>
    {/if}
    <h3>Ajouter une nouvelle catégorie :</h3>
    <input type="text" name="category_name" value="[nom]" size="30" />
    <input type="text" name="category_description" value="[description]" size="60" />
    <input type="submit" name="submit_add_cat_0" value="Ajouter" />
</form>