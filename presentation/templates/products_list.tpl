{* products_list.tpl - Version Bootstrap *}
{load_presentation_object filename="products_list" assign="obj"}

{* ------------------ SECTION PAGINATION ------------------ *}
{if $obj->mrTotalPages > 1}
<p class="d-flex justify-content-center align-items-center my-3">
    <span class="me-3">Page {$obj->mPage} de {$obj->mrTotalPages}</span>
    
    <span class="mx-2">
    {if $obj->mLinkToPreviousPage}
        <a href="{$obj->mLinkToPreviousPage}" class="btn btn-outline-secondary btn-sm">Précédent</a>
    {else}
        <span class="btn btn-outline-secondary btn-sm disabled">Précédent</span>
    {/if}
    </span>
    
    <span class="mx-2">
    {if $obj->mLinkToNextPage}
        <a href="{$obj->mLinkToNextPage}" class="btn btn-outline-secondary btn-sm">Suivant</a>
    {else}
        <span class="btn btn-outline-secondary btn-sm disabled">Suivant</span>
    {/if}
    </span>
</p>
{/if}

{* ------------------ SECTION LISTE DES PRODUITS (GRILLE) ------------------ *}
{if $obj->mProducts}
{* Démarrer la ligne (row) de Bootstrap pour la grille. Utiliser row-cols-md-3 pour 3 produits par ligne (ex.) *}
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    
    {section name=k loop=$obj->mProducts}
        {* Chaque itération de boucle est une colonne/carte *}
        <div class="col">
            <div class="card h-100 shadow-sm product-card-custom">
                
                {* Conteneur d'image et badge *}
                <div class="position-relative">
                    {if $obj->mProducts[k].thumbnail neq ""}
                        <a href="{$obj->mProducts[k].link_to_product}">
                            {* Utiliser la variable Smarty pour l'image réelle, pas le placeholder *}
                            {*<img src="{$obj->mProducts[k].thumbnail}" class="card-img-top" alt="{$obj->mProducts[k].name|escape:'html'}">*}
                            <img src="https://placehold.co/600x500" class="card-img-top" alt="T-Shirt Dragon Rouge">
                        </a>
                        
                        {* Logique du badge Solde (ajustée pour un pourcentage simple) *}
                        {if $obj->mProducts[k].discounted_price != 0 && $obj->mProducts[k].price > $obj->mProducts[k].discounted_price}
                            <span class="badge bg-danger position-absolute top-0 end-0 mt-2 me-2">
                                SOLDE!
                            </span>
                        {/if}
                    {/if}
                </div>
                
                {* Corps de la carte (informations) *}
                <div class="card-body d-flex flex-column text-center m-0 p-1">
                    <h5 class="card-title fs-5 m-1">
                        <a href="{$obj->mProducts[k].link_to_product}" class="text-decoration-none text-dark">
                            {$obj->mProducts[k].name}
                        </a>
                    </h5>
                    
                    {* La description est souvent trop longue pour la carte - la remplacer par la catégorie serait mieux *}
                    <p class="card-text text-muted small text-truncate m-0">
                        {$obj->mProducts[k].description}
                    </p>
                    
                    {* Section Prix et Bouton *}
                    <div class="mt-auto">
                        <div class="price-section d-flex">
                            {if $obj->mProducts[k].discounted_price != 0}
                                {* Prix barré *}
                                <span class="text-muted text-decoration-line-through me-2 fs-5">{$obj->mProducts[k].price|string_format:"%.2f €"}</span>
                                {* Nouveau prix *}
                                <span class="h5 text-primary fs-5">{$obj->mProducts[k].discounted_price|string_format:"%.2f €"}</span>
                            {else}
                                {* Prix régulier *}
                                <span class="h5 text-primary fs-5">{$obj->mProducts[k].price|string_format:"%.2f €"}</span>
                            {/if}
                        </div>
                        
                        <button type="button" class="btn btn-warning btn-sm w-100 p-0">
                            <i class="bi bi-cart-plus-fill me-1"></i> Ajouter au panier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    {/section}
    
</div>
{/if}