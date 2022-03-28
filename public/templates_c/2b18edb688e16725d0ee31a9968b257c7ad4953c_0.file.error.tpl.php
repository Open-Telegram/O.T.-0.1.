<?php
/* Smarty version 4.1.0, created on 2022-03-28 18:03:22
  from '/www/wwwroot/ot.vlad-egorov.ru/api/Views/layout/error.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.0',
  'unifunc' => 'content_624187eac8ca11_53692192',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2b18edb688e16725d0ee31a9968b257c7ad4953c' => 
    array (
      0 => '/www/wwwroot/ot.vlad-egorov.ru/api/Views/layout/error.tpl',
      1 => 1648461790,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_624187eac8ca11_53692192 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
if ($_SESSION['errors']) {?>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_122939123624187eac75cc9_96811862', 'error');
?>

<?php }
}
/* {block 'error'} */
class Block_122939123624187eac75cc9_96811862 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'error' => 
  array (
    0 => 'Block_122939123624187eac75cc9_96811862',
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
            error - <?php echo $_smarty_tpl->tpl_vars['error']->value['text'];?>

        <?php } elseif ($_smarty_tpl->tpl_vars['error']->value['type'] == 'notify') {?>
            notify - <?php echo $_smarty_tpl->tpl_vars['error']->value['text'];?>

        <?php }?>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <?php
}
}
/* {/block 'error'} */
}
