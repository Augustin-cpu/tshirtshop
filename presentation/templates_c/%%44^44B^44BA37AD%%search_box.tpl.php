<?php /* Smarty version 2.6.32, created on 2025-11-09 18:38:04
         compiled from search_box.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'load_presentation_object', 'search_box.tpl', 2, false),)), $this); ?>
<?php echo smarty_function_load_presentation_object(array('filename' => 'search_box','assign' => 'obj'), $this);?>


<div class="card mb-3">
    
        <div class="card-header">
        <h5 class="card-title mb-0">Rechercher dans le Catalogue</h5>
    </div>

        <div class="card-body">
        
        <form class="search_form" method="post" action="<?php echo $this->_tpl_vars['obj']->mLinkToSearch; ?>
">
            
                        <div class="input-group mb-3">
                <input 
                    type="text" 
                    class="form-control" 
                    maxlength="100" 
                    id="search_string" 
                    name="search_string"
                    value="<?php echo $this->_tpl_vars['obj']->mSearchString; ?>
" 
                    placeholder="Entrez vos mots-clés"
                    aria-label="Champ de recherche"
                />
                <button class="btn btn-primary" type="submit">
                    Go!
                </button>
            </div>
            
                        <div class="form-check">
                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    id="all_words" 
                    name="all_words"
                    <?php if ($this->_tpl_vars['obj']->mAllWords == 'on'): ?> checked="checked" <?php endif; ?>
                />
                <label class="form-check-label" for="all_words">
                    Rechercher tous les mots
                </label>
            </div>
            
        </form>
    </div>
</div>