<?php
    getHeader();
?>
    <title>Admin - Edit</title>
    <link rel="stylesheet" href='<?php echo getUrl("public/css/create.css") ?>'>

    <div id="wrapper-create">
        <h4>Admin - Edit</h4>
        <form method="POST" action="<?php echo getUrl("management/edit")."/{$data['id']}"; ?>" enctype="multipart/form-data">
            <div id="wrapper-create-sub">
                <div id="wrapper-create-form">
                    <div class="form-group row">
                        <label for="avatar" class="col-sm-2 col-form-label">ID</label>
                        <?php echo isset($data['id']) ? $data['id'] : "..."; ?>
                    </div>

                    <div class="form-group row">
                        <label for="avatar" class="col-sm-2 col-form-label">Avatar*</label>
                        <label class="file-upload"><input class="avatar" type="file" name="avatar" value="" onchange="readURL(this);">File Upload</label>
                        <label class="file-name ml-2"></label>
                    </div>

                    <div class="form-group row">
                        <label for="avatar" class="col-sm-2 col-form-label"></label>
                        <img id="upload-file" src="<?php echo isset($data['avatar']) ? getUrl(UPLOADS_ADMIN) . $data['avatar'] : getUrl(UPLOADS_EMPTY); ?>">
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label"></label>
                        <?php if(isset($error['error-avatar'])) echo "<p class='error'>{$error['error-avatar']}</p>"; ?>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Name*</label>
                        <input type="text" maxlength="<?php echo MAXIMUM_LENGTH_NAME ?>" class="form-control" id="name" name="name" value="<?php echo isset($data['name']) ? $data['name'] : ""; ?>">
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label"></label>
                        <?php echo isset($error['error-name']) ? "<p class='error'>{$error['error-name']}</p>" : ""; ?>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email*</label>
                        <input type="text" maxlength="<?php echo MAXIMUM_LENGTH_EMAIL ?>" class="form-control" id="email" name="email" value="<?php echo isset($data['email']) ? $data['email'] : ""; ?>">
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label"></label>
                        <?php echo isset($error['error-email']) ? "<p class='error'>{$error['error-email']}</p>" : ""; ?>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="">
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label"></label>
                        <?php echo isset($error['error-password']) ? "<p class='error'>{$error['error-password']}</p>" : ""; ?>
                    </div>

                    <div class="form-group row">
                        <label for="confirm-password" class="col-sm-2 col-form-label">Password Verify</label>
                        <input type="password" name="confirm-password" class="form-control" id="confirm-password" value="">
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label"></label>
                        <?php if(isset($error['error-confirm-password'])) echo "<p class='error'>{$error['error-confirm-password']}</p>"; ?>
                    </div>

                    <div class="form-group row">
                        <label for="role" class="col-sm-2 col-form-label">Role*</label>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="role_type" value="<?php echo ROLE_TYPE_SUPERADMIN; ?>" <?php echo (isset($data['role_type']) && $data['role_type'] == ROLE_TYPE_SUPERADMIN) ? "checked" : ""; ?>>Super Admin
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="role_type" value="<?php echo ROLE_TYPE_ADMIN ?>" <?php echo (isset($data['role_type']) && $data['role_type'] == ROLE_TYPE_ADMIN) ? "checked" : ""; ?>>Admin
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group d-flex mt-4" style="justify-content: space-between">
                <a href="<?php echo getUrl("management/edit/".$data['id']); ?>"><button type="button" class="btn btn-secondary">Reset</button></a>
                <button type="submit" class="btn btn-primary" name="save">Save</button>
            </div>
        </form>
    </div>
    <script>
        $(".avatar").change(function(){
            $(".file-name").text(this.files[0].name);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#upload-file')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
<?php
    getFooter();
?>