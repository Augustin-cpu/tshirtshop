<?php /* Smarty version 2.6.32, created on 2025-10-27 19:33:37
         compiled from store_front.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'store_front.tpl', 2, false),)), $this); ?>
<?php echo smarty_function_config_load(array('file' => "site.conf"), $this);?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?php echo $this->_config[0]['vars']['site_title']; ?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link type="text/css" rel="stylesheet" href="styles/tshirtshop.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<div id="doc" class="container-lg">
<div id="bd">
<div id="container-lg main">
<div class="yui-b">
<div id="header" class="header py-3">
<a href="index.php" class="text-decoration-none text-dark">
<i class="fas fa-tshirt me-2 fs-2"></i>  <span class="fs-3">Tshirt</span><span class="text-warning fs-3">Shop</span>
</a>
</div>
<div class="d-flex flex-coloumn">
Place list of departments here
</div>
<div id="contents" class="container-lg d-flex justify-content-center">
Place contents here
</div>
</div>
</div>

</div>
</div>
</body>
</html>