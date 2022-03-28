<?php
/* Smarty version 4.1.0, created on 2022-03-28 18:23:50
  from '/www/wwwroot/ot.vlad-egorov.ru/api/Views/layout/layout.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.0',
  'unifunc' => 'content_62418cb66ab4c3_41218802',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '64e281792347f2b10080ad40a21b09ea4d204c47' => 
    array (
      0 => '/www/wwwroot/ot.vlad-egorov.ru/api/Views/layout/layout.tpl',
      1 => 1648463016,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62418cb66ab4c3_41218802 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<!DOCTYPE html>

<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link href="public/css/main.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <?php echo '<script'; ?>
 src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"><?php echo '</script'; ?>
>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_33640430562418cb66a2f48_33855475', 'head');
?>

</head>

<body>
<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_15858100862418cb66a6454_33608620', 'header');
?>

<div class="container">
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2131501462418cb66a7e55_75831110', 'error');
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_138128184762418cb66a9345_07364329', 'body');
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_160963143662418cb66aa369_83842183', 'footer');
?>

</div>
</body>

</html><?php }
/* {block 'head'} */
class Block_33640430562418cb66a2f48_33855475 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'head' => 
  array (
    0 => 'Block_33640430562418cb66a2f48_33855475',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'head'} */
/* {block 'header'} */
class Block_15858100862418cb66a6454_33608620 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header' => 
  array (
    0 => 'Block_15858100862418cb66a6454_33608620',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'header'} */
/* {block 'error'} */
class Block_2131501462418cb66a7e55_75831110 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'error' => 
  array (
    0 => 'Block_2131501462418cb66a7e55_75831110',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'error'} */
/* {block 'body'} */
class Block_138128184762418cb66a9345_07364329 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_138128184762418cb66a9345_07364329',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'body'} */
/* {block 'footer'} */
class Block_160963143662418cb66aa369_83842183 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'footer' => 
  array (
    0 => 'Block_160963143662418cb66aa369_83842183',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'footer'} */
}
