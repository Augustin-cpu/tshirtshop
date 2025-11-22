{* admin_menu.tpl *}
{load_presentation_object filename="admin_menu" assign="obj"}
<h1>TShirtShop Admin</h1>
<p class="menu"> |
    <a href="{$obj->mLinkToStoreAdmin}">ADMINISTRATION DU CATALOGUE</a> |
    <a href="{$obj->mLinkToAttributesAdmin}">ADMINISTRATION DES ATTRIBUTS DE PRODUIT</a> |
    <a href="{$obj->mLinkToCartsAdmin}">ADMIN PANIERS</a> |
    <a href="{$obj->mLinkToOrdersAdmin}">ORDERS ADMIN</a> |
    <a href="{$obj->mLinkToStoreFront}">BOUTIQUE</a> |
    <a href="{$obj->mLinkToLogout}">DÉCONNEXION</a> |
</p>