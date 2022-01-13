<?php
    require_once("views/layouts/header.php") ;
?>
    <link rel="stylesheet" href="public/css/detail.css">
    <p class="alert-success bg-green"><?php echo isset($_SESSION['user']['loginFB-success']) ? LOGIN_FB_SUCCESSFUL : ""; unset($_SESSION['user']['loginFB-success']); ?></p>
    <title>User - Detail</title>
        <div id="wrapper-detail">
            <div class="form-group row">
                <label for="avatar" class="col-sm-2 col-form-label">ID</label>
                <?php echo $data['facebook_id']; ?>
            </div>

            <div class="form-group row">
                <label for="avatar" class="col-sm-2 col-form-label">Avatar</label>
                <img src="<?php echo UPLOADS_USER . $data['avatar']; ?>">
            </div>

            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $data['name']; ?>">
            </div>

            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" value="<?php echo $data['email']; ?>">
            </div>
        </div>
<?php
    require_once("views/layouts/footer.php") ;
?>