<div class="main_menu">
  <a href="/bot/admin/" <?php if($route == ''){echo 'class="active_item"';}?>>
    <img src="/bot/admin/src/img/1.svg"> 
    Dashboard
  </a>
  <a href="/bot/admin/?route=buttons/" <?php if( $route == 'buttons/' ){echo 'class="active_item"';}?>>
    <img src="/bot/admin/src/img/5.svg"> 
    Buttons
  </a>
  <a href="/bot/admin/?route=commands/" <?php if( $route == 'commands/=' ){echo 'class="active_item"';}?>>
    <img src="/bot/admin/src/img/3.svg"> 
    Commands
  </a>
  <a href="/bot/admin/?route=modules/" <?php if( $route == 'modules/' ){echo 'class="active_item"';}?>>
    <img src="/bot/admin/src/img/4.svg"> 
    My modules 
  </a>
  <a href="/bot/admin/?route=modules/install" <?php if( $route == 'modules/install' ){echo 'class="active_item"';}?>>
    <img src="/bot/admin/src/img/4.svg"> 
    Module install
  </a>
  <a href="/bot/admin/?route=users/" <?php if( $route == 'users/' ){echo 'class="active_item"';}?>>
    <img src="/bot/admin/src/img/2.svg">  
    Users
  </a>
</div> 