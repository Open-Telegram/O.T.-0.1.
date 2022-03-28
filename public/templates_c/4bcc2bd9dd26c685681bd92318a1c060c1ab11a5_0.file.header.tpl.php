<?php
/* Smarty version 4.1.0, created on 2022-03-28 18:33:33
  from '/www/wwwroot/ot.vlad-egorov.ru/api/Views/layout/header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.0',
  'unifunc' => 'content_62418efd2c16e4_37514164',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4bcc2bd9dd26c685681bd92318a1c060c1ab11a5' => 
    array (
      0 => '/www/wwwroot/ot.vlad-egorov.ru/api/Views/layout/header.tpl',
      1 => 1648463612,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62418efd2c16e4_37514164 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_125863097062418efd2bda48_72761983', 'header');
}
/* {block 'header'} */
class Block_125863097062418efd2bda48_72761983 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header' => 
  array (
    0 => 'Block_125863097062418efd2bda48_72761983',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand logo" href="#"><img src="public/img/logob.svg" alt=""></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<?php
}
}
/* {/block 'header'} */
}
