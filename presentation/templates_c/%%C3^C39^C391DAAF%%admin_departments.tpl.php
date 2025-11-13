<?php /* Smarty version 2.6.32, created on 2025-11-13 16:53:06
         compiled from admin_departments.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'load_presentation_object', 'admin_departments.tpl', 2, false),)), $this); ?>
<?php echo smarty_function_load_presentation_object(array('filename' => 'admin_departments','assign' => 'obj'), $this);?>

<form method="post" action="<?php echo $this->_tpl_vars['obj']->mLinkToDepartmentsAdmin; ?>
">
    <h3>Modifier les départements de TShirtShop :</h3>
    <?php if ($this->_tpl_vars['obj']->mErrorMessage): ?><p class="error"><?php echo $this->_tpl_vars['obj']->mErrorMessage; ?>
</p><?php endif; ?>
    <?php if ($this->_tpl_vars['obj']->mDepartmentsCount == 0): ?>
        <p class="no-items-found">Il n'y a aucun département dans votre base de données !</p>
    <?php else: ?>
        <table class="tss-table">
            <tr>
                <th width="200">Nom du Département</th>
                <th>Description du Département</th>
                <th width="240"> </th>
            </tr>
            <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['obj']->mDepartments) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
                <?php if ($this->_tpl_vars['obj']->mEditItem == $this->_tpl_vars['obj']->mDepartments[$this->_sections['i']['index']]['department_id']): ?>
                    <tr>
                        <td>
                            <input type="text" name="name" value="<?php echo $this->_tpl_vars['obj']->mDepartments[$this->_sections['i']['index']]['name']; ?>
" size="30" />
                        </td>
                        <td>
                            <?php echo '<textarea name="description" rows="3" cols="60">'; ?><?php echo $this->_tpl_vars['obj']->mDepartments[$this->_sections['i']['index']]['description']; ?><?php echo '</textarea>'; ?>

                        </td>
                        <td>
                            <input type="submit" name="submit_edit_cat_<?php echo $this->_tpl_vars['obj']->mDepartments[$this->_sections['i']['index']]['department_id']; ?>
"
                                value="Modifier Catégories" />
                            <input type="submit" name="submit_update_dept_<?php echo $this->_tpl_vars['obj']->mDepartments[$this->_sections['i']['index']]['department_id']; ?>
"
                                value="Mettre à jour" />
                            <input type="submit" name="cancel" value="Annuler" />
                            <input type="submit" name="submit_delete_dept_<?php echo $this->_tpl_vars['obj']->mDepartments[$this->_sections['i']['index']]['department_id']; ?>
"
                                value="Supprimer" />
                        </td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td><?php echo $this->_tpl_vars['obj']->mDepartments[$this->_sections['i']['index']]['name']; ?>
</td>
                        <td><?php echo $this->_tpl_vars['obj']->mDepartments[$this->_sections['i']['index']]['description']; ?>
</td>
                        <td>
                            <input type="submit" name="submit_edit_cat_<?php echo $this->_tpl_vars['obj']->mDepartments[$this->_sections['i']['index']]['department_id']; ?>
"
                                value="Modifier Catégories" />
                            <input type="submit" name="submit_edit_dept_<?php echo $this->_tpl_vars['obj']->mDepartments[$this->_sections['i']['index']]['department_id']; ?>
" value="Modifier" />
                            <input type="submit" name="submit_delete_dept_<?php echo $this->_tpl_vars['obj']->mDepartments[$this->_sections['i']['index']]['department_id']; ?>
"
                                value="Supprimer" />
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endfor; endif; ?>
        </table>
    <?php endif; ?>
    <h3>Ajouter un nouveau département :</h3>
    <p>
        <input type="text" name="department_name" value="[nom]" size="30" />
        <input type="text" name="department_description" value="[description]" size="60" />
        <input type="submit" name="submit_add_dept_0" value="Ajouter" />
    </p>
</form>