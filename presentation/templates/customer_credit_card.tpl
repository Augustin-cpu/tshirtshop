{* customer_credit_card.tpl *}
{load_presentation_object filename="customer_credit_card" assign="obj"}
<form method="post" action="{$obj->mLinkToCreditCardDetails}">
    <h2>Veuillez saisir les détails de votre carte de crédit :</h2>
    <table class="customer-table">
        <tr>
            <td>Titulaire de la carte :</td>
            <td>
                <input type="text" name="cardHolder" size="32"
                       value="{$obj->mPlainCreditCard.card_holder}" />
                {if $obj->mCardHolderError}
                    <p class="error">Vous devez saisir le titulaire de la carte.</p>
                {/if}
            </td>
        </tr>
        <tr>
            <td>Numéro de carte (chiffres uniquement) :</td>
            <td>
                <input type="text" name="cardNumber" size="32"
                       value="{$obj->mPlainCreditCard.card_number}" />
                {if $obj->mCardNumberError}
                    <p class="error">Vous devez saisir un numéro de carte.</p>
                {/if}
            </td>
        </tr>
        <tr>
            <td>Date d'expiration (MM/AA) :</td>
            <td>
                <input type="text" name="expDate" size="32"
                       value="{$obj->mPlainCreditCard.expiry_date}" />
                {if $obj->mExpDateError}
                    <p class="error">Vous devez saisir une date d'expiration</p>
                {/if}
            </td>
        </tr>
        <tr>
            <td>Date d'émission (MM/AA si applicable) :</td>
            <td>
                <input type="text" name="issueDate" size="32"
                       value="{$obj->mPlainCreditCard.issue_date}" />
            </td>
        </tr>
        <tr>
            <td>Numéro d'émission (si applicable) :</td>
            <td>
                <input type="text" name="issueNumber" size="32"
                       value="{$obj->mPlainCreditCard.issue_number}" />
            </td>
        </tr>
        <tr>
            <td>Type de carte :</td>
            <td>
                <select name="cardType">
                    {html_options options=$obj->mCardTypes
                    selected=$obj->mPlainCreditCard.card_type}
                </select>
                {if $obj->mCardTypesError}
                    <p class="error">Vous devez sélectionner un type de carte.</p>
                {/if}
            </td>
        </tr>
    </table>
    <input type="submit" name="sended" value="Confirmer" /> |
    <a href="{$obj->mLinkToCancelPage}">Annuler</a>
</form>