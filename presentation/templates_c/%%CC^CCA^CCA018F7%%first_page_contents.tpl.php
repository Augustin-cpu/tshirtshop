<?php /* Smarty version 2.6.32, created on 2025-11-12 17:57:27
         compiled from first_page_contents.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'load_presentation_object', 'first_page_contents.tpl', 2, false),)), $this); ?>
<?php echo smarty_function_load_presentation_object(array('filename' => 'first_page_contents','assign' => 'obj'), $this);?>

<p class="description">
  Nous espérons que vous vous amuserez à développer TShirtShop, la boutique e-commerce issue de
  Beginning PHP and MySQL E-Commerce: From Novice to Professional!
</p>
<p class="description">
  Nous avons la plus grande collection de t-shirts avec des timbres postaux sur Terre !
  Parcourez nos départements et catégories pour trouver votre favori !
</p>
<p>Accédez à la <a href="<?php echo $this->_tpl_vars['obj']->mLinkToAdmin; ?>
">page d'administration</a>.</p>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "products_list.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>