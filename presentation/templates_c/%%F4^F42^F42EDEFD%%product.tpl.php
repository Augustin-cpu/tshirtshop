<?php /* Smarty version 2.6.32, created on 2025-11-12 16:48:10
         compiled from product.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'load_presentation_object', 'product.tpl', 1, false),array('modifier', 'string_format', 'product.tpl', 43, false),)), $this); ?>
<?php echo smarty_function_load_presentation_object(array('filename' => 'product','assign' => 'obj'), $this);?>


<div class="container my-5">

    <div class="row">

                <div class="col-md-6 mb-4 p-3 gap-2">
            <h1 class="display-5 fw-bold mb-3 text-dark"><?php echo $this->_tpl_vars['obj']->mProduct['name']; ?>
</h1>
            <hr class="mb-4">

                        <?php if ($this->_tpl_vars['obj']->mProduct['image']): ?>
                <img class="img-fluid border border-2 rounded shadow-sm mb-3" src="<?php echo $this->_tpl_vars['obj']->mProduct['image_2']; ?>
"
                    alt="<?php echo $this->_tpl_vars['obj']->mProduct['name']; ?>
 image" />
                
            <?php endif; ?>

                        <?php if ($this->_tpl_vars['obj']->mProduct['image_2']): ?>
                <img class="img-fluid border border-1 rounded-sm shadow-sm" style="max-width: 150px;"
                    src="<?php echo $this->_tpl_vars['obj']->mProduct['image_2']; ?>
" alt="<?php echo $this->_tpl_vars['obj']->mProduct['name']; ?>
 image 2" />
                            <?php endif; ?>
        </div>

                <div class="col-md-6 mb-4">

                        <h3 class="h5 mb-3 text-muted">Description :</h3>
            <p class="lead mb-4 text-secondary"><?php echo $this->_tpl_vars['obj']->mProduct['description']; ?>
</p>

            <hr>

                        <div class="mb-4">
                <p class="h4 fw-light text-muted">Prix :</p>
                <?php if ($this->_tpl_vars['obj']->mProduct['discounted_price'] != 0): ?>
                                        <span class="text-muted text-decoration-line-through fs-5 me-3">
                        <?php echo ((is_array($_tmp=$this->_tpl_vars['obj']->mProduct['price'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f €") : smarty_modifier_string_format($_tmp, "%.2f €")); ?>

                    </span>
                                        <span class="text-danger fw-bold display-6">
                        <?php echo ((is_array($_tmp=$this->_tpl_vars['obj']->mProduct['discounted_price'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f €") : smarty_modifier_string_format($_tmp, "%.2f €")); ?>

                    </span>
                    <span class="badge bg-danger ms-2">PROMO</span>
                <?php else: ?>
                                        <span class="text-success fw-bold display-6">
                        <?php echo ((is_array($_tmp=$this->_tpl_vars['obj']->mProduct['price'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f €") : smarty_modifier_string_format($_tmp, "%.2f €")); ?>

                    </span>
                <?php endif; ?>
            </div>
                        <form class="add-product-form" target="_self" method="post" action="<?php echo $this->_tpl_vars['obj']->mProduct['link_to_add_product']; ?>
">
                                <p class="attributes">
                                        <?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['obj']->mProduct['attributes']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                                                <?php if ($this->_sections['k']['first'] || $this->_tpl_vars['obj']->mProduct['attributes'][$this->_sections['k']['index']]['attribute_name'] !== $this->_tpl_vars['obj']->mProduct['attributes'][$this->_sections['k']['index_prev']]['attribute_name']): ?>
                        <?php echo $this->_tpl_vars['obj']->mProduct['attributes'][$this->_sections['k']['index']]['attribute_name']; ?>
:
                        <select name="attr_<?php echo $this->_tpl_vars['obj']->mProduct['attributes'][$this->_sections['k']['index']]['attribute_name']; ?>
">
                        <?php endif; ?>
                                                <option value="<?php echo $this->_tpl_vars['obj']->mProduct['attributes'][$this->_sections['k']['index']]['attribute_value']; ?>
">
                            <?php echo $this->_tpl_vars['obj']->mProduct['attributes'][$this->_sections['k']['index']]['attribute_value']; ?>

                        </option>
                                                <?php if ($this->_sections['k']['last'] || $this->_tpl_vars['obj']->mProduct['attributes'][$this->_sections['k']['index']]['attribute_name'] !== $this->_tpl_vars['obj']->mProduct['attributes'][$this->_sections['k']['index_next']]['attribute_name']): ?>
                    </select>
                    <?php endif; ?>
                    <?php endfor; endif; ?>
                </p>
                                <button type="submit" class="btn btn-sm btn-warning shadow-sm mb-4 text-white p-2" name="submit">
                    <i class="bi bi-bag-plus me-2"></i> Add to Cart
                </button>
            </form>
                        <?php if ($this->_tpl_vars['obj']->mLinkToContinueShopping): ?>
                <div class="mb-4">
                    <a href="<?php echo $this->_tpl_vars['obj']->mLinkToContinueShopping; ?>
" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i> Continuer mes achats
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mt-5">
            <h2 class="h4 mb-3 text-dark">Trouver des produits similaires dans notre catalogue :</h2>
            <nav aria-label="Breadcrumb navigation for similar products">
                <ol class="breadcrumb bg-light p-3 rounded-lg">
                    <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['obj']->mLocations) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                        <li class="breadcrumb-item">
                                                        <a href="<?php echo $this->_tpl_vars['obj']->mLocations[$this->_sections['i']['index']]['link_to_department']; ?>
"
                                class="text-primary text-decoration-none fw-medium">
                                <?php echo $this->_tpl_vars['obj']->mLocations[$this->_sections['i']['index']]['department_name']; ?>

                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                                                        <a href="<?php echo $this->_tpl_vars['obj']->mLocations[$this->_sections['i']['index']]['link_to_category']; ?>
" class="text-secondary text-decoration-none">
                                <?php echo $this->_tpl_vars['obj']->mLocations[$this->_sections['i']['index']]['category_name']; ?>

                            </a>
                        </li>
                    <?php endfor; endif; ?>
                </ol>
            </nav>
        </div>
    </div>

</div>