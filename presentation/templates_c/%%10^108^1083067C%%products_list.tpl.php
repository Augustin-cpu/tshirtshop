<?php /* Smarty version 2.6.32, created on 2025-11-04 19:26:10
         compiled from products_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'load_presentation_object', 'products_list.tpl', 2, false),array('modifier', 'string_format', 'products_list.tpl', 101, false),)), $this); ?>
<?php echo smarty_function_load_presentation_object(array('filename' => 'products_list','assign' => 'obj'), $this);?>


<?php if ($this->_tpl_vars['obj']->mrTotalPages > 1): ?>
    <p class="d-flex justify-content-center align-items-center my-3">
        <span class="me-3">Page <?php echo $this->_tpl_vars['obj']->mPage; ?>
 de <?php echo $this->_tpl_vars['obj']->mrTotalPages; ?>
</span>
        
        <span class="mx-2">
            <?php if ($this->_tpl_vars['obj']->mLinkToPreviousPage): ?>
                <a href="<?php echo $this->_tpl_vars['obj']->mLinkToPreviousPage; ?>
" class="btn btn-outline-secondary btn-sm">Précédent</a>
            <?php else: ?>
                <span class="btn btn-outline-secondary btn-sm disabled">Précédent</span>
            <?php endif; ?>
        </span>
        
        <span class="mx-2">
            <?php if ($this->_tpl_vars['obj']->mLinkToNextPage): ?>
                <a href="<?php echo $this->_tpl_vars['obj']->mLinkToNextPage; ?>
" class="btn btn-outline-secondary btn-sm">Suivant</a>
            <?php else: ?>
                <span class="btn btn-outline-secondary btn-sm disabled">Suivant</span>
            <?php endif; ?>
        </span>
    </p>
<?php endif; ?>

<?php if ($this->_tpl_vars['obj']->mProducts): ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        
        <?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['obj']->mProducts) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
$this->_sections['k']['step'] = 1;
$this->_sections['k']['start'] = $this->_sections['k']['step'] > 0 ? 0 : $this->_sections['k']['loop']-1;
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = $this->_sections['k']['loop'];
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?>
                        <div class="col">
                <div class="card h-100 shadow-sm product-card-custom">
                    
                                        <div class="position-relative">
                        <?php if ($this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['thumbnail'] != ""): ?>
                            <a href="<?php echo $this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['link_to_product']; ?>
">
                                                                                                <img src="https://placehold.co/600x500" class="card-img-top" alt="T-Shirt Placeholder">
                            </a>
                            
                                                        <?php if ($this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['discounted_price'] != 0 && $this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['price'] > $this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['discounted_price']): ?>
                                <span class="badge bg-danger position-absolute top-0 end-0 mt-2 me-2">
                                    PROMO!
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    
                                        <div class="card-body d-flex flex-column text-center m-0 p-1">
                        <h5 class="card-title fs-5 m-1">
                            <a href="<?php echo $this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['link_to_product']; ?>
" class="text-decoration-none text-dark">
                                <?php echo $this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['name']; ?>

                            </a>
                        </h5>
                        
                        <p class="card-text text-muted small text-truncate m-0">
                            <?php echo $this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['description']; ?>

                        </p>
                        
                                                <p class="attributes text-start small mt-2">
                                                <?php unset($this->_sections['l']);
$this->_sections['l']['name'] = 'l';
$this->_sections['l']['loop'] = is_array($_loop=$this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['attributes']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['l']['show'] = true;
$this->_sections['l']['max'] = $this->_sections['l']['loop'];
$this->_sections['l']['step'] = 1;
$this->_sections['l']['start'] = $this->_sections['l']['step'] > 0 ? 0 : $this->_sections['l']['loop']-1;
if ($this->_sections['l']['show']) {
    $this->_sections['l']['total'] = $this->_sections['l']['loop'];
    if ($this->_sections['l']['total'] == 0)
        $this->_sections['l']['show'] = false;
} else
    $this->_sections['l']['total'] = 0;
if ($this->_sections['l']['show']):

            for ($this->_sections['l']['index'] = $this->_sections['l']['start'], $this->_sections['l']['iteration'] = 1;
                 $this->_sections['l']['iteration'] <= $this->_sections['l']['total'];
                 $this->_sections['l']['index'] += $this->_sections['l']['step'], $this->_sections['l']['iteration']++):
$this->_sections['l']['rownum'] = $this->_sections['l']['iteration'];
$this->_sections['l']['index_prev'] = $this->_sections['l']['index'] - $this->_sections['l']['step'];
$this->_sections['l']['index_next'] = $this->_sections['l']['index'] + $this->_sections['l']['step'];
$this->_sections['l']['first']      = ($this->_sections['l']['iteration'] == 1);
$this->_sections['l']['last']       = ($this->_sections['l']['iteration'] == $this->_sections['l']['total']);
?>
                                                        <?php if ($this->_sections['l']['first'] || $this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['attributes'][$this->_sections['l']['index']]['attribute_name'] !== $this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['attributes'][$this->_sections['l']['index_prev']]['attribute_name']): ?>
                                **<?php echo $this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['attributes'][$this->_sections['l']['index']]['attribute_name']; ?>
**:
                                <select name="attr_<?php echo $this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['attributes'][$this->_sections['l']['index']]['attribute_name']; ?>
" class="form-select form-select-sm mb-1">
                            <?php endif; ?>
                                                        <option value="<?php echo $this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['attributes'][$this->_sections['l']['index']]['attribute_value']; ?>
">
                                <?php echo $this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['attributes'][$this->_sections['l']['index']]['attribute_value']; ?>

                            </option>
                             
                            <?php if ($this->_sections['l']['last'] || $this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['attributes'][$this->_sections['l']['index']]['attribute_name'] !== $this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['attributes'][$this->_sections['l']['index_next']]['attribute_name']): ?>
                                </select>
                            <?php endif; ?>
                        <?php endfor; endif; ?>
                        </p>
                        
                                                <div class="mt-auto">
                            <div class="price-section d-flex justify-content-center mb-1">
                                <?php if ($this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['discounted_price'] != 0): ?>
                                                                        <span class="text-muted text-decoration-line-through me-2 fs-6"><?php echo ((is_array($_tmp=$this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['price'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f €") : smarty_modifier_string_format($_tmp, "%.2f €")); ?>
</span>
                                                                        <span class="h5 text-primary fs-5"><?php echo ((is_array($_tmp=$this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['discounted_price'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f €") : smarty_modifier_string_format($_tmp, "%.2f €")); ?>
</span>
                                <?php else: ?>
                                                                        <span class="h5 text-primary fs-5"><?php echo ((is_array($_tmp=$this->_tpl_vars['obj']->mProducts[$this->_sections['k']['index']]['price'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f €") : smarty_modifier_string_format($_tmp, "%.2f €")); ?>
</span>
                                <?php endif; ?>
                            </div>
                            
                            <button type="button" class="btn btn-warning btn-sm w-100 p-0">
                                <i class="bi bi-cart-plus-fill me-1"></i> Ajouter au panier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endfor; endif; ?>
        
    </div> <?php endif; ?> 