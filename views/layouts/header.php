<DOCTYPE html>
    <html>
    <head>
        <base href="https://vdhp.com/">
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
                <li>
                    <?php
                        if(isset($_SESSION['admin']['role_type'])){
                            if($_SESSION['admin']['role_type'] == 2){
                    ?>
                                <span>Admin management</span><i class="fas fa-caret-down"></i>
                                <ul id="sub-menu">
                                    <li><a href="management/search">Search</a></li>
                                    <li><a href="management/create">Create</a></li>
                                </ul>
                        <?php
                            }
                        ?>
                </li>
                <li>
                    <?php
                    ?>
                        <span>User management</span><i class="fas fa-caret-down"></i>
                        <ul id="sub-menu">
                            <li><a href="user/search">Search</a></li>
                            <li><a href="user/create">Create</a></li>
                        </ul>
                    <?php
                        }
                    ?>
                </li>
                <?php
                    if(isset($_SESSION['user'])){
                        echo '<li><a href="user/logout">Logout</a></li>';
                    }else{
                        echo '<li><a href="management/logout">Logout</a></li>';
                    }
                ?>
            </ul>
        </div>