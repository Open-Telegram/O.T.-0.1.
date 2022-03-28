<?php
/* Smarty version 4.1.0, created on 2022-03-28 18:22:01
  from '/www/wwwroot/ot.vlad-egorov.ru/api/Views/Login.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.0',
  'unifunc' => 'content_62418c49120d32_26863413',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8c9f857f106148febdf4197793a7bc50f2b65c9c' => 
    array (
      0 => '/www/wwwroot/ot.vlad-egorov.ru/api/Views/Login.tpl',
      1 => 1648462919,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62418c49120d32_26863413 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_178266392062418c4911ed68_59037605', 'body');
$_smarty_tpl->inheritance->endChild($_smarty_tpl, 'Api.tpl');
}
/* {block 'body'} */
class Block_178266392062418c4911ed68_59037605 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_178266392062418c4911ed68_59037605',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <form method="post">
        <div class="row">
            <div class="col-sm-5">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Login" aria-label="Username" aria-describedby="basic-addon1">
                    <span class="input-group-text" id="basic-addon1">Login</span>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Pasword" aria-label="Username" aria-describedby="basic-addon2">
                    <span class="input-group-text" id="basic-addon2">Password</span>
                </div>
                <button type="submit" class="btn btn-success">Success</button>
            </div>
        </div>
    </form>
<?php
}
}
/* {/block 'body'} */
}
