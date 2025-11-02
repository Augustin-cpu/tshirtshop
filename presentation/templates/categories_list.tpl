{* categories_list.tpl *}
{load_presentation_object filename="categories_list" assign="obj"}
{* Début de la liste des catégories *}
<div class="box">
  <p class="box-title">Choisir une catégorie</p>
  <ul class="list-group">
  {section name=i loop=$obj->mCategories}
    {assign var=selected value=""}
    {if ($obj->mSelectedCategory == $obj->mCategories[i].category_id)}
      {assign var=selected value="class=\"text-decoration-none text-dark\""}
    {/if}
    <li class="list-group-item list-group-item-action">
      <a {$selected} href="{$obj->mCategories[i].link_to_category}" class="text-decoration-none text-dark">
        {$obj->mCategories[i].name}
      </a>
    </li>
  {/section}
  </ul>
</div>
{* Fin de la liste des catégories *}