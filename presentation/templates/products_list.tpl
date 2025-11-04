{* products_list.tpl - Version Bootstrap *}
{load_presentation_object filename="products_list" assign="obj"}

{* ---------------------------------------------------- *}
{* SECTION PAGINATION                    *}
{* ---------------------------------------------------- *}
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

{* ---------------------------------------------------- *}
{* SECTION LISTE DES PRODUITS (GRILLE)       *}
{* ---------------------------------------------------- *}
{if $obj->mProducts}
    {* Démarrer la ligne (row) de Bootstrap pour la grille *}
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        
        {section name=k loop=$obj->mProducts}
            {* Chaque itération de boucle est une colonne/carte *}
            <div class="col">
                <div class="card h-100 shadow-sm product-card-custom">
                    
                    {* Conteneur d'image et badge *}
                    <div class="position-relative">
                        {if $obj->mProducts[k].thumbnail neq ""}
                            <a href="{$obj->mProducts[k].link_to_product}">
                                {* Utiliser la variable Smarty pour l'image réelle, au lieu du placeholder *}
                                {* <img src="{$obj->mProducts[k].thumbnail}" class="card-img-top" alt="{$obj->mProducts[k].name|escape:'html'}"> *}
                                <img src="https://placehold.co/600x500" class="card-img-top" alt="T-Shirt Placeholder">
                            </a>
                            
                            {* Logique du badge Solde *}
                            {if $obj->mProducts[k].discounted_price != 0 && $obj->mProducts[k].price > $obj->mProducts[k].discounted_price}
                                <span class="badge bg-danger position-absolute top-0 end-0 mt-2 me-2">
                                    PROMO!
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
                        
                        <p class="card-text text-muted small text-truncate m-0">
                            {$obj->mProducts[k].description}
                        </p>
                        
                        {* ------------------ Attributs (CORRIGÉ) ------------------ *}
                        <p class="attributes text-start small mt-2">
                        {* Analyser la liste des attributs et des valeurs d'attributs *}
                        {section name=l loop=$obj->mProducts[k].attributes}
                            {* Générer une nouvelle balise select ? *}
                            {if $smarty.section.l.first ||
                            $obj->mProducts[k].attributes[l].attribute_name !==
                            $obj->mProducts[k].attributes[l.index_prev].attribute_name}
                                **{$obj->mProducts[k].attributes[l].attribute_name}**:
                                <select name="attr_{$obj->mProducts[k].attributes[l].attribute_name}" class="form-select form-select-sm mb-1">
                            {/if}
                            {* Générer une nouvelle balise option *}
                            <option value="{$obj->mProducts[k].attributes[l].attribute_value}">
                                {$obj->mProducts[k].attributes[l].attribute_value}
                            </option>
                            {* Fermer la balise select ? *} 
                            {if $smarty.section.l.last ||
                            $obj->mProducts[k].attributes[l].attribute_name !==
                            $obj->mProducts[k].attributes[l.index_next].attribute_name}
                                </select>
                            {/if}
                        {/section}
                        </p>
                        {* ------------------ FIN Attributs ------------------ *}

                        {* Section Prix et Bouton *}
                        <div class="mt-auto">
                            <div class="price-section d-flex justify-content-center mb-1">
                                {if $obj->mProducts[k].discounted_price != 0}
                                    {* Prix barré *}
                                    <span class="text-muted text-decoration-line-through me-2 fs-6">{$obj->mProducts[k].price|string_format:"%.2f €"}</span>
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
        
    </div> {* Fermeture de <div class="row"> *}
{/if} {* Fermeture de {if $obj->mProducts} *}