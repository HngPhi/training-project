<?php
//    session_start();
//    ob_start();
//?>
<DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href='public/css/all.css'>
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
    <div id="wrapper">
        <!--            Menu-->
        <div id="menu">
            <ul id="main-menu">
<!--                --><?php // print_r($_SESSION); ?>
                <li>
                    <?php
//                        if(!isset($_SESSION['loginUser'])){
                            if(isset($_SESSION['admin']['role_type'])){
                                if($_SESSION['admin']['role_type'] == 2){
                    ?>
                                    <a href="">Admin management</a><i class="fas fa-caret-down"></i>
                                    <ul id="sub-menu">
                                        <li><a href="<?php echo URL_SEARCH_ADMIN; ?>">Search</a></li>
                                        <li><a href="<?php echo URL_CREATE_ADMIN; ?>">Create</a></li>
                                    </ul>
                            <?php
                                }
                            ?>
                </li>
                <li>
                    <?php
                    ?>
                        <a href="">User management</a><i class="fas fa-caret-down"></i>
                        <ul id="sub-menu">
                            <li><a href="<?php echo URL_SEARCH_USER; ?>">Search</a></li>
                        </ul>
                    <?php
//                            }
                        }
                    ?>
                </li>
                <?php
                    if(isset($_SESSION['loginUser'])){
                        echo '<li><a href='. URL_LOGOUT_USER .'>Logout</a></li>';
                    }else{
                        echo '<li><a href='. URL_LOGOUT_ADMIN .'>Logout</a></li>';
                    }
                ?>
            </ul>
        </div>