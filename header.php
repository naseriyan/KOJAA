<nav class="navbar navbar-expand nav_top"> 
    <ul class="navbar-nav mr-auto">
        <?php
            if(isset($_SESSION["CurrentUser_ID"]))
            {
                echo '<li class="nav-item">';
                echo '<a class="nav-link" id="nav-link btn bg-primary text-white" href="./Index.php">جستجو...</a>';
                echo '</li>';

                echo '<li class="nav-item">';
                echo '<a class="nav-link" id="nav-link btn bg-primary text-white" href="#">'.$_SESSION["CurrentUser_Title"].' خوش آمدید!</a>';
                echo '</li>';

                echo '<li class="nav-item">';
                echo '<a class="nav-link store" href="./stores.php">فروشگاه های من</a>';
                echo '</li>';

                echo '<li class="nav-item">';
                echo '<a class="nav-link store" href="./login.php">خروج</a>';
                echo '</li>';

            }
            else
            {
                echo '<li class="nav-item">';
                echo '<a class="nav-link" id="nav-link btn bg-primary text-white" href="./Index.php">جستجو...</a>';
                echo '</li>';

                echo '<li class="nav-item">';
                echo '<a class="nav-link btn bg-primary text-white" href="login.php">ورود به سیستم</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a class="nav-link btn bg-primary text-white" style="margin-right: 3px;" href="signup.php">ثبت نام</a>';
                echo '</li>';
            }
        ?>
    </ul>
</nav>
