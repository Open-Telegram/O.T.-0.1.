<?php
/* Smarty version 4.1.0, created on 2022-03-28 18:18:30
  from '/www/wwwroot/ot.vlad-egorov.ru/api/Views/layout/footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.0',
  'unifunc' => 'content_62418b76c58782_44211150',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '91e4db20105e9a78603dae5a9328482a6885486b' => 
    array (
      0 => '/www/wwwroot/ot.vlad-egorov.ru/api/Views/layout/footer.tpl',
      1 => 1648462709,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62418b76c58782_44211150 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_196616188862418b76c552f6_06043649', 'footer');
}
/* {block 'footer'} */
class Block_196616188862418b76c552f6_06043649 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'footer' => 
  array (
    0 => 'Block_196616188862418b76c552f6_06043649',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <footer class="py-3 my-4 position-absolute bottom-0 start-50 translate-middle-x">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Home</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Features</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Pricing</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">FAQs</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">About</a></li>
        </ul>
        <p class="text-center text-muted">© 2021 Company, Inc</p>
    </footer>
<?php
}
}
/* {/block 'footer'} */
}
