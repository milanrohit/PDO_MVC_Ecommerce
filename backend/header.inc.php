<?php
   include_once("../config/connection.php");
   include_once("../lib/function.inc.php");
   (isset($_SESSION['Admin_Login']) && $_SESSION['Admin_Login']!='') ?  : redirect("categoriemaster.php");
?>
<!doctype html>
<html class="no-js" lang="">
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <?php include_once("headscript.php"); ?>
   <body>
      <aside id="left-panel" class="left-panel">
         <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
               <ul class="nav navbar-nav">
                  <li class="menu-title">Menu</li>
                  <li class="menu-item-has-children dropdown">
                     <a href="<?php echo BackendPath; ?>categoriemaster.php" > Categorie Master</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="<?php echo BackendPath; ?>productmaster.php" > Product Master</a>
                  </li>
				      <li class="menu-item-has-children dropdown">
                     <a href="<?php echo BackendPath; ?>ordermaster.php" > Order Master</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="<?php echo BackendPath; ?>usermaster.php" > User Master</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="<?php echo BackendPath; ?>contact.php" > Contact Us</a>
                  </li>
               </ul>
            </div>
         </nav>
      </aside>
      <div id="right-panel" class="right-panel">
         <header id="header" class="header">
            <div class="top-left">
               <div class="navbar-header">
                  <a class="navbar-brand" href="index.html"><img src="images/logo.png" alt="Logo"></a>
                  <a class="navbar-brand hidden" href="index.html"><img src="images/logo2.png" alt="Logo"></a>
                  <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
               </div>
            </div>
            <div class="top-right">
               <div class="header-menu">
                  <div class="user-area dropdown float-right">
                     <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Welcome Admin</a>
                     <div class="user-menu dropdown-menu">
                        <a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i>Logout</a>
                     </div>
                  </div>
               </div>
            </div>
         </header>