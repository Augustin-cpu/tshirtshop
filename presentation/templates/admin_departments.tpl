{* admin_departments.tpl *}
{load_presentation_object filename="admin_departments" assign="obj"}
<form method="post" action="{$obj->mLinkToDepartmentsAdmin}">
    <h3>Modifier les départements de TShirtShop :</h3>
    {if $obj->mErrorMessage}<p class="error">{$obj->mErrorMessage}</p>{/if}
    {if $obj->mDepartmentsCount eq 0}
        <p class="no-items-found">Il n'y a aucun département dans votre base de données !</p>
    {else}
        <table class="tss-table">
            <tr>
                <th width="200">Nom du Département</th>
                <th>Description du Département</th>
                <th width="240"> </th>
            </tr>
            {section name=i loop=$obj->mDepartments}
                {if $obj->mEditItem == $obj->mDepartments[i].department_id}
                    <tr>
                        <td>
                            <input type="text" name="name" value="{$obj->mDepartments[i].name}" size="30" />
                        </td>
                        <td>
                            {strip}
                                <textarea name="description" rows="3" cols="60">
                {$obj->mDepartments[i].description}
                </textarea>
                            {/strip}
                        </td>
                        <td>
                            <input type="submit" name="submit_edit_cat_{$obj->mDepartments[i].department_id}"
                                value="Modifier Catégories" />
                            <input type="submit" name="submit_update_dept_{$obj->mDepartments[i].department_id}"
                                value="Mettre à jour" />
                            <input type="submit" name="cancel" value="Annuler" />
                            <input type="submit" name="submit_delete_dept_{$obj->mDepartments[i].department_id}"
                                value="Supprimer" />
                        </td>
                    </tr>
                {else}
                    <tr>
                        <td>{$obj->mDepartments[i].name}</td>
                        <td>{$obj->mDepartments[i].description}</td>
                        <td>
                            <input type="submit" name="submit_edit_cat_{$obj->mDepartments[i].department_id}"
                                value="Modifier Catégories" />
                            <input type="submit" name="submit_edit_dept_{$obj->mDepartments[i].department_id}" value="Modifier" />
                            <input type="submit" name="submit_delete_dept_{$obj->mDepartments[i].department_id}"
                                value="Supprimer" />
                        </td>
                    </tr>
                {/if}
            {/section}
        </table>
    {/if}
    <h3>Ajouter un nouveau département :</h3>
    <p>
        <input type="text" name="department_name" value="[nom]" size="30" />
        <input type="text" name="department_description" value="[description]" size="60" />
        <input type="submit" name="submit_add_dept_0" value="Ajouter" />
    </p>
</form>