{* search_box.tpl - Avec les classes Bootstrap 5 *}
{load_presentation_object filename="search_box" assign="obj"}

{* Début boîte de recherche - Utilisation de la composante Card de Bootstrap *}
<div class="card mb-3">
    
    {* Titre de la boîte (Card Header) *}
    <div class="card-header">
        <h5 class="card-title mb-0">Rechercher dans le Catalogue</h5>
    </div>

    {* Contenu de la boîte (Card Body) *}
    <div class="card-body">
        
        <form class="search_form" method="post" action="{$obj->mLinkToSearch}">
            
            {* Champ de saisie et bouton Go! *}
            <div class="input-group mb-3">
                <input 
                    type="text" 
                    class="form-control" 
                    maxlength="100" 
                    id="search_string" 
                    name="search_string"
                    value="{$obj->mSearchString}" 
                    placeholder="Entrez vos mots-clés"
                    aria-label="Champ de recherche"
                />
                <button class="btn btn-primary" type="submit">
                    Go!
                </button>
            </div>
            
            {* Case à cocher "Rechercher tous les mots" *}
            <div class="form-check">
                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    id="all_words" 
                    name="all_words"
                    {if $obj->mAllWords == "on"} checked="checked" {/if}
                />
                <label class="form-check-label" for="all_words">
                    Rechercher tous les mots
                </label>
            </div>
            
        </form>
    </div>
</div>
{* Fin boîte de recherche *}