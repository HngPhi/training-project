<DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>Admin - Login</title>
        <link rel="stylesheet" href="<?php echo getUrl("public/css/all.css") ?>">
        <link rel="stylesheet" href="<?php echo getUrl("public/css/login.css") ?>">
    </head>
    <body>
        <div id="login">
            <form method="POST" action="login">
                <h2 style="text-align: center">Login Admin</h2>
                <span class="input-space">
                    <label for="email">Email</label>
                    <input type="text" name="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} else{echo '';} ?>" id="email" maxlength="50">
                    <p class="error"><?php if(isset($data['error-email'])){echo $data['error-email'];} ?></p>
                 </span>

                <span class="input-space">
                    <label for="password">Password</label>
                    <input type="password" name="password" value="" id="password">
                    <p class="error"><?php if(isset($data['error-password'])){echo $data['error-password'];} ?></p>
                 </span>

                <p class="error error_login"><?php echo isset($data['error-login']) ? $data['error-login'] : ""; ?></p>
                <input type="submit" name="login" value="Login">
            </form>
        </div>
    </body>
</html>