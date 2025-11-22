{* admin_orders.tpl *}
{load_presentation_object filename="admin_orders" assign="obj"}
{if $obj->mErrorMessage}<p class="error">{$obj->mErrorMessage}</p>{/if}
<form method="get" action="{$obj->mLinkToAdmin}">
    <input name="Page" type="hidden" value="Orders" />
    <p>
        <font class="bold-text">Afficher les</font>
        <input name="recordCount" type="text" value="{$obj->mRecordCount}" />
        <font class="bold-text">commandes les plus récentes</font>
        <input type="submit" name="submitMostRecent" value="Go!" />
    </p>
    <p>
        <font class="bold-text">Afficher tous les enregistrements créés entre</font>
        <input name="startDate" type="text" value="{$obj->mStartDate}" />
        <font class="bold-text">et</font>
        <input name="endDate" type="text" value="{$obj->mEndDate}" />
        <input type="submit" name="submitBetweenDates" value="Go!" />
    </p>
    <p>
        <font class="bold-text">Afficher les commandes par statut</font>
        {html_options name="status" options=$obj->mOrderStatusOptions
        selected=$obj->mSelectedStatus}
        <input type="submit" name="submitOrdersByStatus" value="Go!" />
    </p>
</form>
{if $obj->mOrders}
    <table class="tss-table">
        <tr>
            <th>ID Commande</th>
            <th>Date Création</th>
            <th>Date Expédition</th>
            <th>Statut</th>
            <th>Client</th>
            <th> </th>
        </tr>
        {section name=i loop=$obj->mOrders}
            {assign var=status value=$obj->mOrders[i].status}
            <tr>
                <td>{$obj->mOrders[i].order_id}</td>
                <td>{$obj->mOrders[i].created_on|date_format:"%Y-%m-%d %T"}</td>
                <td>{$obj->mOrders[i].shipped_on|date_format:"%Y-%m-%d %T"}</td>
                <td>{$obj->mOrderStatusOptions[$status]}</td>
                <td>{$obj->mOrders[i].customer_name}</td>
                <td align="right">
                    <a href="{$obj->mOrders[i].link_to_order_details_admin}">Voir Détails</a>
                </td>
            </tr>
        {/section}
    </table>
{/if}