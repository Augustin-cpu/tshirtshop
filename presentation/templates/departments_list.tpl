{* departments_list.tpl *}
{load_presentation_object filename="departments_list" assign="obj"}
{* Début de la liste des départements *}
<div class="box">
  <p class="box-title">Choose a Department</p>
  <ul class="list-group">
    {* Boucle à travers la liste des départements *}
    {section name=i loop=$obj->mDepartments}
      {assign var=selected value=""}
      {* Vérifie si le département est sélectionné pour décider quel style CSS utiliser *}
      {if ($obj->mSelectedDepartment == $obj->mDepartments[i].department_id)}
        {assign var=selected value="class=\"text-warning text-decoration-none\""}
      {/if}
      <li class="list-group-item list-group-item-action">
        {* Génère un lien pour un nouveau département dans la liste *}
        <a {$selected} href="{$obj->mDepartments[i].link_to_department}" class="text-decoration-none text-dark">
          {$obj->mDepartments[i].name}
        </a>
      </li>
    {/section}
  </ul>
</div>
{* Fin de la liste des départements *}