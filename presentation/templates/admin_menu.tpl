{* admin_menu.tpl *}
{load_presentation_object filename="admin_menu" assign="obj"}
<h1>TShirtShop Admin</h1>
<p> |
    <a href="{$obj->mLinkToStoreAdmin}">ADMINISTRATION DU CATALOGUE</a> |
    <a href="{$obj->mLinkToStoreFront}">BOUTIQUE</a> |
    <a href="{$obj->mLinkToLogout}">DÉCONNEXION</a> |
</p>