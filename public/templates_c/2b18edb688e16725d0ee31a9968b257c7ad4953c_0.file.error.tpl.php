<?php
/* Smarty version 4.1.0, created on 2022-03-28 19:07:47
  from '/www/wwwroot/ot.vlad-egorov.ru/api/Views/layout/error.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.0',
  'unifunc' => 'content_624197034e8022_54729471',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2b18edb688e16725d0ee31a9968b257c7ad4953c' => 
    array (
      0 => '/www/wwwroot/ot.vlad-egorov.ru/api/Views/layout/error.tpl',
      1 => 1648465666,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_624197034e8022_54729471 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
if ($_SESSION['errors']) {?>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2118987488624197034d47c8_00508613', 'error');
?>

<?php }
}
/* {block 'error'} */
class Block_2118987488624197034d47c8_00508613 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'error' => 
  array (
    0 => 'Block_2118987488624197034d47c8_00508613',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_SESSION['errors'], 'error');
$_smarty_tpl->tpl_vars['error']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['error']->value) {
$_smarty_tpl->tpl_vars['error']->do_else = false;
?>
        <?php if ($_smarty_tpl->tpl_vars['error']->value['type'] == 'error') {?>
            <div class="alert alert-danger text-center" role="alert">
                <?php echo $_smarty_tpl->tpl_vars['error']->value['text'];?>

            </div>
        <?php } elseif ($_smarty_tpl->tpl_vars['error']->value['type'] == 'notify') {?>
            <div class="alert alert-warning  text-center" role="alert">
                <?php echo $_smarty_tpl->tpl_vars['error']->value['text'];?>

            </div>
        <?php }?>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <?php
}
}
/* {/block 'error'} */
}
