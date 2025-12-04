{* customer_details.tpl *}
{load_presentation_object filename="customer_details" assign="obj"}
<form method="post" action="{$obj->mLinkToAccountDetails}" class="card p-4 shadow-sm">
    <h2 class="mb-4 text-center">Veuillez saisir vos coordonnées :</h2>

    {* Groupe Email *}
    <div class="mb-3">
        <label for="email" class="form-label">Adresse e-mail :</label>
        <input type="text" class="form-control" id="email" name="email" value="{$obj->mEmail}"
               {if $obj->mEditMode}readonly="readonly"{/if} />
        {if $obj->mEmailAlreadyTaken}
            <div class="text-danger small mt-1">Un utilisateur avec cette adresse e-mail existe déjà.</div>
        {/if}
        {if $obj->mEmailError}
            <div class="text-danger small mt-1">Vous devez saisir une adresse e-mail.</div>
        {/if}
    </div>

    {* Groupe Nom *}
    <div class="mb-3">
        <label for="name" class="form-label">Nom :</label>
        <input type="text" class="form-control" id="name" name="name" value="{$obj->mName}" />
        {if $obj->mNameError}
            <div class="text-danger small mt-1">Vous devez saisir votre nom.</div>
        {/if}
    </div>

    {* Groupe Mot de passe *}
    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe :</label>
        <input type="password" class="form-control" id="password" name="password" />
        {if $obj->mPasswordError}
            <div class="text-danger small mt-1">Vous devez saisir un mot de passe.</div>
        {/if}
    </div>

    {* Groupe Confirmation Mot de passe *}
    <div class="mb-3">
        <label for="passwordConfirm" class="form-label">Retapez le mot de passe :</label>
        <input type="password" class="form-control" id="passwordConfirm" name="passwordConfirm" />
        {if $obj->mPasswordConfirmError}
            <div class="text-danger small mt-1">Vous devez retaper votre mot de passe.</div>
        {elseif $obj->mPasswordMatchError}
            <div class="text-danger small mt-1">Vous devez retaper le même mot de passe.</div>
        {/if}
    </div>

    {* Champs supplémentaires en mode édition *}
    {if $obj->mEditMode}
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="dayPhone" class="form-label">Téléphone jour :</label>
                <input type="text" class="form-control" id="dayPhone" name="dayPhone" value="{$obj->mDayPhone}" />
            </div>
            <div class="col-md-4 mb-3">
                <label for="evePhone" class="form-label">Téléphone soir :</label>
                <input type="text" class="form-control" id="evePhone" name="evePhone" value="{$obj->mEvePhone}" />
            </div>
            <div class="col-md-4 mb-3">
                <label for="mobPhone" class="form-label">Téléphone mobile :</label>
                <input type="text" class="form-control" id="mobPhone" name="mobPhone" value="{$obj->mMobPhone}" />
            </div>
        </div>
    {/if}

    <div class="d-flex gap-2 mt-4">
        <button type="submit" name="sended" class="btn btn-primary" value="Confirmer">Confirmer</button>
        <a href="{$obj->mLinkToCancelPage}" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>