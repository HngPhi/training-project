<?php
getHeader();
?>
    <link rel="stylesheet" href="<?php echo getUrl( "public/css/detail.css") ?>">
    <?php echo isset($_SESSION['user']['loginFB-success']) ? "<p class='alert-success bg-green'>" . LOGIN_FB_SUCCESSFUL . "</p>" : "";
    unset($_SESSION['user']['loginFB-success']); ?>
    <title>User - Detail</title>
    <div id="wrapper-detail">
        <div class="form-group row">
            <label for="avatar" class="col-sm-2 col-form-label">ID</label>
            <?php echo $data['id']; ?>
        </div>

        <div class="form-group row">
            <label for="avatar" class="col-sm-2 col-form-label">Avatar</label>
            <img src="<?php echo getUrl(UPLOADS_USER) . $data['avatar']; ?>">
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
getFooter();
?>