{load_presentation_object filename="product" assign="obj"}

<div class="container my-5">
    
    <div class="row">
        
        {* Section Titre et Images (Colonne de Gauche) *}
        <div class="col-md-6 mb-4 p-3 gap-2">
            <h1 class="display-5 fw-bold mb-3 text-dark">{$obj->mProduct.name}</h1>
            <hr class="mb-4">

            {* Image principale : Utilise 'img-fluid' et un cadre clair *}
            {if $obj->mProduct.image}
                {*<img class="img-fluid border border-2 rounded shadow-sm mb-3" 
                     src="{$obj->mProduct.image}"
                     alt="{$obj->mProduct.name} image" />*}
                <img src="https://placehold.co/700x200" class="img-fluid card-img-top" alt="T-Shirt Dragon Rouge">

            {/if}

            {* Image secondaire : Miniature à côté de la principale *}
            {if $obj->mProduct.image_2}
                {*<img class="img-fluid border border-1 rounded-sm shadow-sm" style="max-width: 150px;" src="{$obj->mProduct.image_2}" alt="{$obj->mProduct.name} image 2" />*}
                <img src="https://placehold.co/700x200" class="img-fluid card-img-top mt-2" alt="T-Shirt Dragon Rouge">
            {/if}
        </div>

        {* Section Description et Actions (Colonne de Droite) *}
        <div class="col-md-6 mb-4">
            
            {* Description du produit *}
            <h3 class="h5 mb-3 text-muted">Description :</h3>
            <p class="lead mb-4 text-secondary">{$obj->mProduct.description}</p>
            
            <hr>

            {* Section Prix *}
            <div class="mb-4">
                <p class="h4 fw-light text-muted">Prix :</p>
                {if $obj->mProduct.discounted_price != 0}
                    {* Prix barré (Ancien prix) *}
                    <span class="text-muted text-decoration-line-through fs-5 me-3">
                        {$obj->mProduct.price|string_format:"%.2f €"}
                    </span>
                    {* Prix réduit (Nouveau prix en couleur d'accentuation) *}
                    <span class="text-danger fw-bold display-6">
                        {$obj->mProduct.discounted_price|string_format:"%.2f €"}
                    </span>
                    <span class="badge bg-danger ms-2">PROMO</span>
                {else}
                    {* Prix régulier *}
                    <span class="text-success fw-bold display-6">
                        {$obj->mProduct.price|string_format:"%.2f €"}
                    </span>
                {/if}
            </div>

            {* Bouton Action (Ajouter au panier - non implémenté ici mais utile pour le design) *}
            <button class="btn btn-sm btn-warning shadow-sm mb-4 text-white p-2" type="button">
                <i class="bi bi-bag-plus me-2"></i> Ajouter au Panier
            </button>

            {* Lien Continuer mes achats *}
            {if $obj->mLinkToContinueShopping}
                <div class="mb-4">
                    <a href="{$obj->mLinkToContinueShopping}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i> Continuer mes achats
                    </a>
                </div>
            {/if}
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 mt-5">
            <h2 class="h4 mb-3 text-dark">Trouver des produits similaires dans notre catalogue :</h2>
            <nav aria-label="Breadcrumb navigation for similar products">
                <ol class="breadcrumb bg-light p-3 rounded-lg">
                    {section name=i loop=$obj->mLocations}
                    <li class="breadcrumb-item">
                        {* Département *}
                        <a href="{$obj->mLocations[i].link_to_department}" class="text-primary text-decoration-none fw-medium">
                            {$obj->mLocations[i].department_name}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {* Catégorie *}
                        <a href="{$obj->mLocations[i].link_to_category}" class="text-secondary text-decoration-none">
                            {$obj->mLocations[i].category_name}
                        </a>
                    </li>
                    {/section}
                </ol>
            </nav>
        </div>
    </div>

</div>