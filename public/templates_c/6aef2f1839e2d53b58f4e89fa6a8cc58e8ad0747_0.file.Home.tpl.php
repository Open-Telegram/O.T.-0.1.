<?php
/* Smarty version 4.1.0, created on 2022-03-28 17:37:28
  from '/www/wwwroot/ot.vlad-egorov.ru/api/Views/Home.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.0',
  'unifunc' => 'content_624181d89231a8_15871799',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6aef2f1839e2d53b58f4e89fa6a8cc58e8ad0747' => 
    array (
      0 => '/www/wwwroot/ot.vlad-egorov.ru/api/Views/Home.tpl',
      1 => 1648460245,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_624181d89231a8_15871799 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_386870565624181d89202e2_56573441', 'body');
$_smarty_tpl->inheritance->endChild($_smarty_tpl, 'Api.tpl');
}
/* {block 'body'} */
class Block_386870565624181d89202e2_56573441 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_386870565624181d89202e2_56573441',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
My HTML Page Body goes here<?php
}
}
/* {/block 'body'} */
}
