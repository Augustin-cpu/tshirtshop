{* first_page_contents.tpl *}
{load_presentation_object filename="first_page_contents" assign="obj"}
<p class="description">
  Nous espérons que vous vous amuserez à développer TShirtShop, la boutique e-commerce issue de
  Beginning PHP and MySQL E-Commerce: From Novice to Professional!
</p>
<p class="description">
  Nous avons la plus grande collection de t-shirts avec des timbres postaux sur Terre !
  Parcourez nos départements et catégories pour trouver votre favori !
</p>
<p>Accédez à la <a href="{$obj->mLinkToAdmin}">page d'administration</a>.</p>
{include file="products_list.tpl"}