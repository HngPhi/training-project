<?php
require_once("views/layouts/header.php") ;
?>
    <link rel="stylesheet" href="public/css/detail.css">
    <title>User - Detail</title>
    <form method="POST" action="<?php echo URL_DETAIL_USER; ?>" enctype="multipart/form-data">
        <div id="wrapper-create-sub">
            <div id="wrapper-create-form">
                <div class="form-group row">
                    <label for="avatar" class="col-sm-2 col-form-label">ID</label>
                    <?php echo $data['id']; ?>
                </div>

                <div class="form-group row">
                    <label for="avatar" class="col-sm-2 col-form-label">Avatar</label>
                    <label class="file-upload"><input type="file" name="avatar" value="<?php echo $data['avatar'] ?>">File Upload</label>
                    <?php if(isset($error['error-avatar'])) echo "<p class='error ml-4'>{$error['error-avatar']}</p>"; ?>
                </div>

                <div class="form-group row">
                    <label for="avatar" class="col-sm-2 col-form-label"></label>
                    <img src="<?php echo UPLOADS_USER.$data['avatar']; ?>">
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $data['name']; ?>">
                    <?php if(isset($error['error-name'])) echo "<p class='error ml-4'>{$error['error-name']}</p>"; ?>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?php echo $data['email']; ?>">
                    <?php if(isset($error['error-email'])) echo "<p class='error ml-4'>{$error['error-email']}</p>"; ?>
                </div>
    </form>
<?php
require_once("views/layouts/header.php") ;
?>