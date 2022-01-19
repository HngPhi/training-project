<DOCTYPE html>
    <html>
    <head>
        <base href="https://vdhp.com/">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href='<?php echo getUrl("public/css/all.css") ?>'>
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
                <li>
                    <?php
                        if(isset($_SESSION['admin']['role_type'])){
                            if($_SESSION['admin']['role_type'] == ROLE_TYPE_SUPERADMIN){
                    ?>
                                <input type="checkbox" id="adminManagement">
                                <label for="adminManagement">Admin management<i class="fas fa-caret-down"></i></label>
                                <ul id="sub-menu">
                                    <li><a href="<?php echo getUrl("management/search"); ?>">Search</a></li>
                                    <li><a href="<?php echo getUrl("management/create"); ?>">Create</a></li>
                                </ul>
                        <?php
                            }
                        ?>
                </li>
                <li>
                        <input type="checkbox" id="userManagement">
                        <label for="userManagement">User management<i class="fas fa-caret-down"></i></label>
                        <ul id="sub-menu">
                            <li><a href="<?php echo getUrl("user/search"); ?>">Search</a></li>
                            <li><a href="<?php echo getUrl("user/create"); ?>">Create</a></li>
                        </ul>
                    <?php
                        }
                    ?>
                </li>
                <?php
                    $urlLogout = (isset($_SESSION['admin']) && $_SESSION['admin']['login']['checkLogin'] == 'adminLogin') ? getUrl("management/logout") : getUrl("user/logout");
                ?>
                <li><a href="<?php echo $urlLogout; ?>">Logout</a></li>
            </ul>
        </div>