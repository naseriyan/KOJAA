<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
  <title>کجا داره؟!</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/x-icon" href="./images/favicon.ico">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="./css/bootstrap.min.css" rel="stylesheet">
  <link href="./css/styles.css" rel="stylesheet">
  <link href="./css/fonts.css" rel="stylesheet">
  <script src="./js/bootstrap.bundle.min.js"></script>
  <script src="./js/jquery.min.js"></script>
</head>

<body>
    
    <?php
        require './class/Database.php';
        session_start();

        $db=new Database();
        $params = array($_SESSION["CurrentUser_ID"]);

        if($_SESSION["CurrentUser_IsAdmin"]==true)
            {
                $query="SELECT u.Title as UserTitle, s.*,format(s.[CreateDateTime],'yyyy/MM/dd','fa') as CreateDate_Fa
                        ,(SELECT count(*) FROM tbStoreItems where StoreRef=s.Id) as ItemCount 
                        FROM tbStores s   
                        inner join tbUsers as u on u.ID= s.UserRef";

            }

            

        else
         {
            $query="SELECT *,format([CreateDateTime],'yyyy/MM/dd','fa') as CreateDate_Fa
            ,(SELECT count(*) FROM tbStoreItems where StoreRef=s.Id) as ItemCount
            FROM tbStores s where UserRef=?  ";
         }
        $stores=$db->GetTable($query,$params);

    ?>

    <div class="content">
        <?php include('header.php'); ?>
        <div class="p-5 ">
            <h2> فروشگاها</h2>
                <p>از طریق این بخش فروشگاه های مرتبط و همچنین کالاهای مرتبط با هر فروشگاه را مدیریت نمایید.</p>
                <hr class="hr" />
                <a class="btn btn-primary mb-3" href="EditStore.php?id=0">فروشگاه جدید</a>
                <table class="table table-bordered">
                <thead>
                    <tr>
                <?php
                     if($_SESSION["CurrentUser_IsAdmin"]==true)
                     {
                        echo' <th> کاربرمر بوطه </th>';
                     }
                ?>
                        <th>عنوان فروشگاه</th>
                        <th>تاریخ ایجاد</th>
                        <th>تعداد کالای مرتبط</th>
                        <th>آدرس</th>
                        <th>تلفن</th>

                        <th>عملیات</th>
                    </tr>
                </thead>
                    <tbody>
                    <?php
                        while ($row = sqlsrv_fetch_array($stores, SQLSRV_FETCH_ASSOC))
                        {
                            echo '<tr>';
                            if($_SESSION["CurrentUser_IsAdmin"]==true)
                            {
                               echo' <th> '.$row["UserTitle"].'</th>';
                            }
                            echo '<td>'.$row["Title"].'</td>';
                            echo '<td>'.$row["CreateDate_Fa"].'</td>';
                            echo '<td>'.$row["ItemCount"].'</td>';
                            echo '<td>'.$row["Address"].'</td>';
                            echo '<td>'.$row["Tell"].'</td>';

                            echo '<td>
                                <a class="btn btn-primary"  href="EditStore.php?id='.$row["ID"].'">ویرایش</a>
                                <a class="btn btn-primary"  href="RelatedItems.php?StoreId='.$row["ID"].'">اقلام مرتبط</a>';
                            if($row["ItemCount"]>0)
                                echo '<a class="btn btn-danger disabled">حذف</a>';
                            else
                                echo '<a class="btn btn-danger">حذف</a>';
                            echo '</td> 
                                    </tr>';
                        }
                        ?>
                    </tbody>
                </table>
                </div>
        </div>

        <script>
            

        </script>

    </body>
</html>
