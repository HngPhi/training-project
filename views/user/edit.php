<?php
    getHeader();
?>
    <title>User - Edit</title>
    <link rel="stylesheet" href='public/css/create.css'>

    <div id="wrapper-create">
        <h4>User - Edit</h4>
        <form method="POST" action="<?php echo URL_EDIT_USER."&id={$data['id']}"; ?>" enctype="multipart/form-data">
            <div id="wrapper-create-sub">
                <div id="wrapper-create-form">
                    <div class="form-group row">
                        <label for="avatar" class="col-sm-2 col-form-label">ID</label>
                        <?php echo $data['id']; ?>
                    </div>

                    <div class="form-group row">
                        <label for="avatar" class="col-sm-2 col-form-label">Avatar*</label>
                        <label class="file-upload"><input class="avatar" type="file" name="avatar" value="<?php echo $data['avatar'] ?>" onchange="readURL(this);">File Upload</label>
                        <label class="file-name ml-2"></label>
                        <?php if(isset($error['error-avatar'])) echo "<p class='error ml-4'>{$error['error-avatar']}</p>"; ?>
                    </div>

                    <div class="form-group row">
                        <label for="avatar" class="col-sm-2 col-form-label"></label>
                        <img id="upload-file" src="<?php echo UPLOADS_ADMIN.$data['avatar']; ?>">
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Name*</label>
                        <input type="text" maxlength="255" class="form-control" id="name" name="name" value="<?php echo $data['name']; ?>">
                        <?php echo isset($error['error-name']) ? "<p class='error ml-4'>{$error['error-name']}</p>" : (isset($error['error-length-name']) ? "<p class='error ml-4'>{$error['error-length-name']}</p>" : ""); ?>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email*</label>
                        <input type="text" maxlength="255" class="form-control" id="email" name="email" value="<?php echo $data['email']; ?>">
                        <?php echo isset($error['error-email']) ? "<p class='error ml-4'>{$error['error-email']}</p>" : (isset($error['error-length-email']) ? "<p class='error ml-4'>{$error['error-length-email']}</p>" : ""); ?>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="">
                        <?php echo isset($error['error-password']) ? "<p class='error ml-4'>{$error['error-password']}</p>" : (isset($error['error-length-password']) ? "<p class='error ml-4'>{$error['error-length-password']}</p>" : ""); ?>
                    </div>

                    <div class="form-group row">
                        <label for="confirm-password" class="col-sm-2 col-form-label">Password Verify</label>
                        <input type="password" name="confirm-password" class="form-control" id="confirm-password" value="">
                        <?php if(isset($error['error-confirm-password'])) echo "<p class='error ml-4'>{$error['error-confirm-password']}</p>"; ?>
                    </div>

                    <div class="form-group row">
                        <label for="role" class="col-sm-2 col-form-label">Status*</label>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="status" value="1" <?php if($data['status'] == 1) echo "checked"; ?>>Active
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="status" value="2" <?php if($data['status'] == 2) echo "checked"; ?>>Banned
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