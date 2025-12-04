{* checkout_info.tpl *}
{load_presentation_object filename="checkout_info" assign="obj"}
<form method="post" action="{$obj->mLinkToCheckout}">
    <h2>Votre commande se compose des articles suivants :</h2>
    <table class="tss-table">
        <tr>
            <th>Nom du produit</th>
            <th>Prix</th>
            <th>Quantité</th>
            <th>Sous-total</th>
        </tr>
        {section name=i loop=$obj->mCartItems}
            <tr>
                <td>{$obj->mCartItems[i].name} ({$obj->mCartItems[i].attributes})</td>
                <td>{$obj->mCartItems[i].price}</td>
                <td>{$obj->mCartItems[i].quantity}</td>
                <td>{$obj->mCartItems[i].subtotal}</td>
            </tr>
        {/section}
    </table>
    <p>Montant total : <font class="price">${$obj->mTotalAmount}</font></p>

    {if $obj->mNoCreditCard == 'yes'}
        <p class="error">Aucun détail de carte de crédit enregistré.</p>
    {else}
        <p>{$obj->mCreditCardNote}</p>
    {/if}

    {if $obj->mNoShippingAddress == 'yes'}
        <p class="error">Adresse de livraison requise pour passer la commande.</p>
    {else}
        <p>
            Adresse de livraison : <br />
             {$obj->mCustomerData.address_1}<br />
            {if $obj->mCustomerData.address_2}
                 {$obj->mCustomerData.address_2}<br />
            {/if}
             {$obj->mCustomerData.city}<br />
             {$obj->mCustomerData.region}<br />
             {$obj->mCustomerData.postal_code}<br />
             {$obj->mCustomerData.country}<br /><br />
            Région de livraison : {$obj->mShippingRegion}
        </p>
    {/if}

    <input type="submit" name="place_order" value="Passer la commande"
            {$obj->mOrderButtonVisible} /> |
    <a href="{$obj->mLinkToCart}">Modifier le Panier</a> |
    <a href="{$obj->mLinkToContinueShopping}">Continuer les achats</a>
</form>