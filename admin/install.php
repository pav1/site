    <?php 

        error_reporting( E_ALL );
        ini_set( "display_errors", 1 );

        require_once ($_SERVER['DOCUMENT_ROOT']."/config/dbconfig.php"); 
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");         
     ?>


    <div id="main">
    <h3>Charity for clarity installation</h3>

    <ol class="round">
        <li class="one">
            <h5>Login as admin </h5> 
        </li>
        <li class="two">
            <h5>Customize</h5>
         </li>
        <li class="asterisk">
            <div class="visit">
                Install DB
            </div>
         </li>
    </ol>
    </div>

</div> <!-- End of outer-wrapper which opens in header.php -->

<?php 
    include ("Includes/footer.php");
 ?>