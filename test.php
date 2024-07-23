<?php
    require './class/Database.php';

    $db = new Database();
    
    $q="SELECT * FROM tbUsers where ID=? AND UserName=?";

    $id=1;
    $name='mn';

    $paramsUser = array($id,$name);

    $users=$db->GetTable($q,$paramsUser);
    echo "salam\n";

    $row_count = sqlsrv_num_rows($users);
   
        if ($row_count === false)
        echo "Error in retrieveing row count.";
        else
        echo $row_count;


        $rows = sqlsrv_has_rows( $users );
   if ($rows === true)
      echo "There are rows. <br />";
   else 
      echo "There are no rows. <br />";

    echo sqlsrv_fetch_array($users)["UserName"];
    


?>


if ($users === false) 
            {
                die(print_r(sqlsrv_errors(), true));
                exit();
            }


            /*
            راهنمای نقشه نشان
            https://platform.neshan.org/sdk/web-sdk-getting-started-v1/
            */
            <!-- https://platform.neshan.org/panel/login
             ورود به نشان
             
             -->

