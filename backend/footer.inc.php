<?php
   include_once("../config/connection.php");
   include_once("../lib/Incfunctions.php");
   
   // Initialize database connection
   $database = new Database();
   $db = $database->getConnection();

   (isset($_SESSION['Admin_Login']) && $_SESSION['Admin_Login']!='') ?  : redirect("login.php");
?>
         <div class="clearfix"></div>
         <footer class="site-footer">
            <div class="footer-inner bg-white">
               <div class="row">
                  <div class="col-sm-6">
                     <?php getCopyRight(); ?>
                  </div>
                  <div class="col-sm-6 text-right">
                     <?php getDesignerCredit(); ?>
                  </div>
               </div>
            </div>
         </footer>
      </div>
   </body>
</html>