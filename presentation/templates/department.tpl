{* department.tpl *}
{load_presentation_object filename="department" assign="obj"}
<h1 class="display-1">{$obj->mName}</h1>
<p class="lead">{$obj->mDescription}</p>
{include file="products_list.tpl"}