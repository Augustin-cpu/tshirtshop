{* products_list.tpl *}
{load_presentation_object filename="products_list" assign="obj"}

{if $obj->mrTotalPages > 1}
<p class="d-flex">
Page {$obj->mPage} of {$obj->mrTotalPages}
{if $obj->mLinkToPreviousPage}
<a href="{$obj->mLinkToPreviousPage}">Précédent</a>
{else}
Précédent
{/if}
{if $obj->mLinkToNextPage}
<a href="{$obj->mLinkToNextPage}">Suivant</a>
{else}
Suivant
{/if}
</p>
{/if}

{if $obj->mProducts}
<div class="row gap-1">
{section name=k loop=$obj->mProducts}
{if $smarty.section.k.index % 2 == 0}
{/if}
<div class="card col-lg-5">
  <div>
    <h3 class="display-6 fs-4">
    <a href="{$obj->mProducts[k].link_to_product}" class="text-decoration-none text-secondary">
      {$obj->mProducts[k].name}
    </a>
  </h3>
  </div>
  <div class="d-flex gap-2">
    {if $obj->mProducts[k].thumbnail neq ""}
    <a href="{$obj->mProducts[k].link_to_product}">
      {* <img src="{$obj->mProducts[k].thumbnail}" alt="{$obj->mProducts[k].name}" />*}
    <img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 109px; height: 150px; margin-right: 16px;" />
    </a>
    {/if}
    <div class="d-flex flex-column">
        <p>{$obj->mProducts[k].description}</p>
        <p class="section">
            Prix:
            {if $obj->mProducts[k].discounted_price != 0}
            <span class="old-price">{$obj->mProducts[k].price}</span>
            <span class="price">{$obj->mProducts[k].discounted_price}</span>
            {else}
            <span class="price">{$obj->mProducts[k].price}</span>
            {/if}
        </p>
    </div>
  </div>
</div>
{if $smarty.section.k.index % 2 != 0 && !$smarty.section.k.first ||
    $smarty.section.k.last}
{/if}
{/section}
</div>
</div>
{/if}