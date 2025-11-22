{* admin_products.tpl *}
{load_presentation_object filename="admin_products" assign="obj"}
<form method="post" action="{$obj->mLinkToCategoryProductsAdmin}">
    <h3>
        Modification des produits pour la catégorie : {$obj->mCategoryName} [
        <a href="{$obj->mLinkToDepartmentCategoriesAdmin}"> retour aux catégories ...</a> ]
    </h3>
    {if $obj->mErrorMessage}<p class="error">{$obj->mErrorMessage}</p>{/if}
    {if $obj->mProductsCount eq 0}
        <p class="no-items-found">Il n'y a aucun produit dans cette catégorie !</p>
    {else}
        <table class="tss-table">
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Prix</th>
                <th>Prix Réduit</th>
                <th width="80"> </th>
            </tr>
            {section name=i loop=$obj->mProducts}
                <tr>
                    <td>{$obj->mProducts[i].name}</td>
                    <td>{$obj->mProducts[i].description}</td>
                    <td>{$obj->mProducts[i].price}</td>
                    <td>{$obj->mProducts[i].discounted_price}</td>
                    <td>
                        <input type="submit" name="submit_edit_prod_{$obj->mProducts[i].product_id}" value="Modifier" />
                    </td>
                </tr>
            {/section}
        </table>
    {/if}
    <h3>Ajouter un nouveau produit :</h3>
    <input type="text" name="product_name" value="[nom]" size="30" />
    <input type="text" name="product_description" value="[description]" size="60" />
    <input type="text" name="product_price" value="[prix]" size="10" />
    <input type="submit" name="submit_add_prod_0" value="Ajouter" />
</form>