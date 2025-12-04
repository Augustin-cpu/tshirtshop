{* customer_address.tpl *}
{load_presentation_object filename="customer_address" assign="obj"}
<form method="post" action="{$obj->mLinkToAddressDetails}">
    <h2>Veuillez saisir les détails de votre adresse :</h2>
    <table class="customer-table">
        <tr>
            <td>Adresse 1 :</td>
            <td>
                <input type="text" name="address1" value="{$obj->mAddress1}"
                       size="32" />
                {if $obj->mAddress1Error}
                    <p class="error">Vous devez saisir une adresse.</p>
                {/if}
            </td>
        </tr>
        <tr>
            <td>Adresse 2 :</td>
            <td>
                <input type="text" name="address2" value="{$obj->mAddress2}"
                       size="32" />
            </td>
        </tr>
        <tr>
            <td>Ville :</td>
            <td>
                <input type="text" name="city" value="{$obj->mCity}"
                       size="32" />
                {if $obj->mCityError}
                    <p class="error">Vous devez saisir une ville.</p>
                {/if}
            </td>
        </tr>
        <tr>
            <td>Région/État :</td>
            <td>
                <input type="text" name="region" value="{$obj->mRegion}"
                       size="32" />
                {if $obj->mRegionError}
                    <p class="error">Vous devez saisir une région/un état.</p>
                {/if}
            </td>
        </tr>
        <tr>
            <td>Code Postal/ZIP :</td>
            <td>
                <input type="text" name="postalCode" value="{$obj->mPostalCode}"
                       size="32" />
                {if $obj->mPostalCodeError}
                    <p class="error">Vous devez saisir un code postal/ZIP.</p>
                {/if}
            </td>
        </tr>
        <tr>
            <td>Pays :</td>
            <td>
                <input type="text" name="country" value="{$obj->mCountry}"
                       size="32" />
                {if $obj->mCountryError}
                    <p class="error">Vous devez saisir un pays.</p>
                {/if}
            </td>
        </tr>
        <tr>
            <td>Région de livraison :</td>
            <td>
                <select name="shippingRegion">
                    {html_options options=$obj->mShippingRegions
                    selected=$obj->mShippingRegion}
                </select>
                {if $obj->mShippingRegionError}
                    <p class="error">Vous devez sélectionner une région de livraison.</p>
                {/if}
            </td>
        </tr>
    </table>
    <input type="submit" name="sended" value="Confirmer" /> |
    <a href="{$obj->mLinkToCancelPage}">Annuler</a>
</form>