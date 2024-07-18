<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
  <title>کجا داره؟!</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="./images/favicon.ico">
  <link href="./css/bootstrap.min.css" rel="stylesheet">
  <link href="./css/fonts.css" rel="stylesheet">
  <script src="./js/bootstrap.bundle.min.js"></script>
  <script src="./js/jquery.min.js"></script>
</head>
<body>

  <?php
      require './class/Database.php';
      session_start();
      session_destroy();
      session_start();

      if ($_SERVER["REQUEST_METHOD"] == "POST") 
      {
        unset($_SESSION['form_error_login']);
        $userName = $_POST['userName'];
        $password = $_POST['password'];

        if ($userName=='' || $password=='') {
            $_SESSION['form_error_login'] = "نام کاربری یا کلمه عبور نمی تواند خالی باشد";

        } else {
            //check username
            $db=new Database();
            $paramsUser = array($userName,md5($password.'SALT123'));

            $user=$db->GetTable("SELECT * FROM tbUsers where lower(UserName) = lower(?) and password=?",$paramsUser);


            if(sqlsrv_num_rows($user)==1)
            {
                $data=sqlsrv_fetch_array($user);
                $_SESSION["CurrentUser_ID"]=$data["ID"];
                $_SESSION["CurrentUser_Title"]=$data["Title"];
                header('Location: Index.php');
            }
            else
            {
                $_SESSION['form_error_login'] = "نام کاربری یا کلمه عبور صحیح نمی باشد";

              }
            


        }

      }
  ?>
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
                    <h2 class="h3">ورود به سیستم</h2>
                    <h3 class="fs-6 fw-normal text-secondary m-0">نام کاربری و کلمه عبور خود را وارد نمایید</h3>
                  </div>
                </div>
              </div>
              <form action="login.php" method="POST">
                <div class="row gy-3 overflow-hidden">
                  <div class="col-12">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" name="userName" id="userName" placeholder="نام کاربری" required>
                      <label for="userName" class="form-label">نام کاربری</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-floating mb-3">
                      <input type="password" class="form-control" name="password" id="password" value="" placeholder="کلمه عبور" required>
                      <label for="password" class="form-label">کلمه عبور</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-grid">
                      <button class="btn bsb-btn-2xl btn-primary" type="submit">ورود به سیستم</button>
                    </div>
                  </div>

                  <?php
          if(isset($_SESSION['form_error_login'])){
            echo '<div class="col-12 bg-warning">
              <div class="d-grid">
                <p>';
                        echo $_SESSION['form_error_login'];
                echo '</p>
              </div>
            </div>';
            unset($_SESSION['form_error_login']);
            }
            ?>

                </div>
              </form>
              <div class="row">
                <div class="col-12">
                  <hr class="mt-5 mb-4 border-secondary-subtle">
                  <p class="m-0 text-secondary text-center">حساب کاربری ندارید؟ <a href="signup.php" class="link-primary text-decoration-none">ثبت نام</a></p>
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

