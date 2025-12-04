{* customer_logged.tpl *}
{load_presentation_object filename="customer_logged" assign="obj"}
<div class="box">
    <p class="box-title">Bienvenue, {$obj->mCustomerName}</p>
    <ul>
        <li>
            <a {if $obj->mSelectedMenuItem eq 'account'} class="selected" {/if}
                    href="{$obj->mLinkToAccountDetails}">
                Modifier le compte
            </a>
        </li>
        <li>
            <a {if $obj->mSelectedMenuItem eq 'credit-card'} class="selected" {/if}
                    href="{$obj->mLinkToCreditCardDetails}">
                {$obj->mCreditCardAction} Détails CC
            </a>
        </li>
        <li>
            <a {if $obj->mSelectedMenuItem eq 'address'} class="selected" {/if}
                    href="{$obj->mLinkToAddressDetails}">
                {$obj->mAddressAction} Adresse
            </a>
        </li>
        <li>
            <a href="{$obj->mLinkToLogout}">
                Déconnexion
            </a>
        </li>
        </ul>
</div>