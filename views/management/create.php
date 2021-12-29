<?php
    require_once("views/layouts/header.php") ;
?>

<title>Admin - Create</title>
<link rel="stylesheet" href='public/css/create.css'>


<div id="wrapper-create">
    <h4>Admin - Create</h4>
    <form method="POST" action="<?php base_url("index.php?controller=management&action=create"); ?>" >
        <?php
            if(isset($data['alert-error'])) echo "<p class='alert-error'>{$data['alert-error']}</p>";
            if(isset($data['alert-success'])) echo "<p class='alert-success'>{$data['alert-success']}</p>";
        ?>
        <div id="wrapper-create-sub">
            <div id="wrapper-create-form">
                <div class="form-group row">
                    <label for="avatar" class="col-sm-2 col-form-label">Avatar*</label>
                    <label class="file-upload"><input type="file" name="avatar" value="<?php if(isset($_POST['avatar'])) echo $_POST['avatar']; ?>">File Upload</label>
                    <?php if(isset($data['error-avatar'])) echo "<p class='error ml-4'>{$data['error-avatar']}</p>"; ?>
                </div>

                <div class="form-group row">
                    <label for="avatar" class="col-sm-2 col-form-label"></label>
                    <img src="#" style="display: none">
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name*</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>">
                    <?php if(isset($data['error-name'])) echo "<p class='error ml-4'>{$data['error-name']}</p>"; ?>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email*</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
                    <?php if(isset($data['error-email'])) echo "<p class='error ml-4'>{$data['error-email']}</p>"; ?>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Password*</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <?php if(isset($data['error-password'])) echo "<p class='error ml-4'>{$data['error-password']}</p>"; ?>
                </div>

                <div class="form-group row">
                    <label for="confirm-password" class="col-sm-2 col-form-label">Password Verify*</label>
                    <input type="password" class="form-control" id="confirm-password" name="confirm-password"">
                    <?php if(isset($data['error-confirm-password'])) echo "<p class='error ml-4'>{$data['error-confirm-password']}</p>"; ?>
                </div>

                <div class="form-group row">
                    <label for="role" class="col-sm-2 col-form-label">Role*</label>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="role-type" value="2" <?php if(isset($_POST['role-type']) && $_POST['role-type'] == 2) echo "checked"; ?>>Super Admin
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="role-type" value="1" <?php if(isset($_POST['role-type']) && $_POST['role-type'] == 1) echo "checked"; ?>>Admin
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group d-flex mt-4" style="justify-content: space-between">
            <button type="submit" class="btn btn-secondary" name="reset">Reset</button>
            <button type="submit" class="btn btn-primary" name="save">Save</button>
        </div>
    </form>
</div>

<?php
    require_once("views/layouts/footer.php") ;
?>