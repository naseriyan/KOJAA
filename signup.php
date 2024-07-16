<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <title>کجا داره؟!</title>
    <link rel="icon" type="image/x-icon" href="./images/favicon.ico">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="./css/fonts.css" rel="stylesheet">
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/jquery.min.js"></script>
</head>

<body>

    <?php
    require './class/Database.php';
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        unset($_SESSION['form_error_signup']);

        $title = $_POST['title'];
        $userName = $_POST['userName'];
        $mobile = $_POST['mobile'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        if ($password != $password2) 
        {
            $_SESSION['form_error_signup'] = "تکرار کلمه عبور معتبر نمی باشد";
        } 
        else 
        {
            $db = new Database();
            //نام کاربری از قبل موجود است؟
            $qExits="SELECT * FROM tbUsers where UserName=?";
            $paramsUser = array($userName);
            $users=$db->GetTable($qExits,$paramsUser);

            if ($users === false) 
            {
                die(print_r(sqlsrv_errors(), true));
                exit();
            }

            if(sqlsrv_num_rows($users) > 0)
            {
                $_SESSION['form_error_signup'] = "نام کاربری وارد شده از قبل موجود می باشد";
            }
            else
            {
                $query = "INSERT INTO tbUsers([Title],[Mobile],[UserName],[Password]) 
                    values(?,?,?,?)";
        
                $paramsInfo = array($title,$mobile,$userName,md5($password.'SALT123'));

                $result= $db->ExecuteQuery($query,$paramsInfo);
                if ($result === false) 
                {
                    die(print_r(sqlsrv_errors(), true));
                    exit();
                }

                header('Location: signup-completed.php');
            }
        }

    }
    ?>

    <!-- Registration 6 - Bootstrap Brain Component -->
    <div class="bg-primary p-3 p-md-4 p-xl-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-9 col-lg-7 col-xl-6 col-xxl-5">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-5">
                                    <h2 class="h2 text-center text-primary">
                                        <a href="./Index.php" style="text-decoration:none;">
                                        کجا داره؟!
                                        </a>
                                    </h2>
                                    <h2 class="h3">ثبت نام</h2>
                                    <h3 class="fs-6 fw-normal text-secondary m-0">اطلاعات مورد نیاز را جهت ثبت نام
                                        وارد نمایید</h3>
                                    </div>
                                </div>
                            </div>
                            <form action="signup.php" method="POST">
                                <div class="row gy-3 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="userName" id="userName"
                                                placeholder="نام کاربری" required value="<?php if(isset($userName)) echo $userName ?>">
                                            <label for="userName" class="form-label">نام کاربری</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="title" id="title"
                                                placeholder="عنوان" required value="<?php if(isset($userName)) echo $title ?>">
                                            <label for="title" class="form-label">عنوان</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="mobile" id="mobile"
                                                placeholder="موبایل" required maxlength="11" value="<?php if(isset($userName)) echo $mobile ?>">
                                            <label for="mobile" class="form-label">موبایل</label>
                                        </div>
                                    </div>
                                    <div class="col-12">    
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" name="password" id="password"
                                                value="" placeholder="کلمه عبور" required>
                                            <label for="password" class="form-label">کلمه عبور</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" name="password2" id="password2"
                                                value="" placeholder="تکرار کلمه عبور" required>
                                            <label for="password2" class="form-label">تکرار کلمه عبور</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn bsb-btn-2xl btn-primary" type="submit">ثبت نام</button>
                                        </div>
                                    </div>

                                    <?php

                                    if (isset($_SESSION['form_error_signup'])) {
                                        echo '<div class="col-12 bg-warning">
                  <div class="d-grid">
                    <p>';
                                        echo $_SESSION['form_error_signup'];
                                        echo '</p>
                  </div>
                </div>';

                                        unset($_SESSION['form_error_signup']);
                                    }
                                    ?>

                                </div>
                            </form>
                            <div class="row">
                                <div class="col-12">
                                    <hr class="mt-5 mb-4 border-secondary-subtle">
                                    <p class="m-0 text-secondary text-center">از قبل حساب کاربری دارید؟ <a
                                            href="login.php" class="link-primary text-decoration-none">ورود به سیستم</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>