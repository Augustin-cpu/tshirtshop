{* admin_product_details.tpl *}
{load_presentation_object filename="admin_product_details" assign="obj"}
<form enctype="multipart/form-data" method="post" action="{$obj->mLinkToProductDetailsAdmin}">
    <h3>
        Modification du produit : ID #{$obj->mProduct.product_id} —
        {$obj->mProduct.name} [
        <a href="{$obj->mLinkToCategoryProductsAdmin}">
            retour aux produits ...</a> ]
    </h3>
    {if $obj->mErrorMessage}<p class="error">{$obj->mErrorMessage}</p>{/if}
    <table class="borderless-table">
        <tbody>
            <tr>
                <td valign="top">
                    <p class="bold-text">
                        Nom du produit :
                    </p>
                    <p>
                        <input type="text" name="name" value="{$obj->mProduct.name}" size="30" />
                    </p>
                    <p class="bold-text">
                        Description du produit :
                    </p>
                    <p>
                        {strip}
                            <textarea name="description" rows="3" cols="60">
    {$obj->mProduct.description}
    </textarea>
                        {/strip}
                    </p>
                    <p class="bold-text">
                        Prix du produit :
                    </p>
                    <p>
                        <input type="text" name="price" value="{$obj->mProduct.price}" size="5" />
                    </p>
                    <p class="bold-text">
                        Prix réduit du produit :
                    </p>
                    <p>
                        <input type="text" name="discounted_price" value="{$obj->mProduct.discounted_price}" size="5" />
                    </p>
                    <p>
                        <input type="submit" name="UpdateProductInfo" value="Mettre à jour les informations" />
                    </p>
                </td>
                <td valign="top">
                    <p>
                        <font class="bold-text">Le produit appartient à ces catégories :</font>
                        {$obj->mProductCategoriesString}
                    </p>
                    <p class="bold-text">
                        Retirer ce produit de :
                    </p>
                    <p>
                        {html_options name="TargetCategoryIdRemove"
options=$obj->mRemoveFromCategories}
                        <input type="submit" name="RemoveFromCategory" value="Retirer"
                            {if $obj->mRemoveFromCategoryButtonDisabled} disabled="disabled" {/if} />
                    </p>
                    <p class="bold-text">
                        Assigner le produit à cette catégorie : </p>
                    <p>
                        {html_options name="TargetCategoryIdAssign"
options=$obj->mAssignOrMoveTo}
                        <input type="submit" name="Assign" value="Assigner" />
                    </p>
                    <p class="bold-text">
                        Déplacer le produit vers cette catégorie :
                    </p>
                    <p>
                        {html_options name="TargetCategoryIdMove"
options=$obj->mAssignOrMoveTo}
                        <input type="submit" name="Move" value="Déplacer" />
                        <input type="submit" name="RemoveFromCatalog" value="Retirer le produit du catalogue"
                            {if !$obj->mRemoveFromCategoryButtonDisabled} disabled="disabled" {/if} />
                    </p>
                    {if $obj->mProductAttributes}
                        <p class="bold-text">
                            Attributs du produit :
                        </p>
                        <p>
                            {html_options name="TargetAttributeValueIdRemove"
    options=$obj->mProductAttributes}
                            <input type="submit" name="RemoveAttributeValue" value="Retirer" />
                        </p>
                    {/if}
                    {if $obj->mCatalogAttributes}
                        <p class="bold-text">
                            Assigner un attribut au produit :
                        </p>
                        <p>
                            {html_options name="TargetAttributeValueIdAssign"
    options=$obj->mCatalogAttributes}
                            <input type="submit" name="AssignAttributeValue" value="Assigner" />
                        </p>
                    {/if}
                    <p class="bold-text">
                        Définir l'option d'affichage pour ce produit :
                    </p>
                    <p>
                        {html_options name="ProductDisplay"
options=$obj->mProductDisplayOptions
selected=$obj->mProduct.display}<input type="submit" name="SetProductDisplayOption" value="Définir" />
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
    <p>
        <font class="bold-text">Nom de l'image :</font> {$obj->mProduct.image}
        <input name="ImageUpload" type="file" value="Télécharger" />
        <input type="submit" name="Upload" value="Télécharger" />
    </p>
    {if $obj->mProduct.image}
    <p>
        <img src="product_images/{$obj->mProduct.image}" border="0" alt="Image {$obj->mProduct.name}" />
    </p>
    {/if}
    <p>
        <font class="bold-text">Nom de l'image 2 :</font> {$obj->mProduct.image_2}
        <input name="Image2Upload" type="file" value="Télécharger" />
        <input type="submit" name="Upload" value="Télécharger" />
    </p>
    {if $obj->mProduct.image_2}
        <p>
            <img src="product_images/{$obj->mProduct.image_2}" border="0" alt="Image 2 {$obj->mProduct.name}" />
        </p>
    {/if}
    <p>
        <font class="bold-text">Nom de la miniature :</font> {$obj->mProduct.thumbnail}
        <input name="ThumbnailUpload" type="file" value="Télécharger" />
        <input type="submit" name="Upload" value="Télécharger" />
    </p>
    {if $obj->mProduct.thumbnail}
        <p>
            <img src="product_images/{$obj->mProduct.thumbnail}" border="0" alt="Miniature {$obj->mProduct.name}" />
        </p>
    {/if}
</form>