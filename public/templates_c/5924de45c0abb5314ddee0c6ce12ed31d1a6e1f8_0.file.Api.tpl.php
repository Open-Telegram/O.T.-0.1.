<?php
/* Smarty version 4.1.0, created on 2022-03-28 18:03:22
  from '/www/wwwroot/ot.vlad-egorov.ru/api/Views/Api.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.0',
  'unifunc' => 'content_624187eac609b2_00623173',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5924de45c0abb5314ddee0c6ce12ed31d1a6e1f8' => 
    array (
      0 => '/www/wwwroot/ot.vlad-egorov.ru/api/Views/Api.tpl',
      1 => 1648461799,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:layout/header.tpl' => 1,
    'file:layout/error.tpl' => 1,
    'file:layout/footer.tpl' => 1,
  ),
),false)) {
function content_624187eac609b2_00623173 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php $_smarty_tpl->_subTemplateRender('file:layout/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
$_smarty_tpl->_subTemplateRender('file:layout/error.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
$_smarty_tpl->_subTemplateRender('file:layout/footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
$_smarty_tpl->inheritance->endChild($_smarty_tpl, 'layout/layout.tpl');
}
}
