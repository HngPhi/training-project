<?php
    getHeader();
?>

    <title>User - Create</title>
    <link rel="stylesheet" href='<?php echo getUrl("public/css/create.css") ?>'>

    <div id="wrapper-create">
        <h4>User - Create</h4>
        <form method="POST" action="<?php echo getUrl("user/create");?>" enctype="multipart/form-data">
            <?php if(isset($data['alert-success'])) echo "<p class='alert-success bg-green'>{$data['alert-success']}</p>"; ?>
            <div id="wrapper-create-sub">
                <div id="wrapper-create-form">
                    <div class="form-group row">
                        <label for="avatar" class="col-sm-2 col-form-label">Avatar*</label>
                        <label class="file-upload"><input class="avatar" type="file" name="avatar" onchange="readURL(this);" value="<?php if(isset($_FILES['avatar']['name'])) echo $_FILES['avatar']['name']; ?>">File Upload</label>
                        <label class="file-name ml-2"></label>
                        <?php if(isset($data['error-avatar'])) echo "<p class='error ml-4'>{$data['error-avatar']}</p>"; ?>
                    </div>

                    <div class="form-group row">
                        <label for="avatar" class="col-sm-2 col-form-label"></label>
                        <label><img id="upload-file" src="<?php echo isset($_SESSION['user']['upload']) ? $_SESSION['user']['upload'] : 'public/uploads/empty.jpg'; ?>"></label>
                        <?php unset($_SESSION['user']['upload']); ?>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Name*</label>
                        <input type="text" maxlength="255" class="form-control" id="name" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>">
                        <?php echo isset($data['error-name']) ? "<p class='error ml-4'>{$data['error-name']}</p>" : (isset($data['error-length-name']) ? "<p class='error ml-4'>{$data['error-length-name']}</p>" : ""); ?>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email*</label>
                        <input type="text" maxlength="255" class="form-control" id="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
                        <?php echo isset($data['error-email']) ? "<p class='error ml-4'>{$data['error-email']}</p>" : (isset($data['error-length-email']) ? "<p class='error ml-4'>{$data['error-length-email']}</p>" : ""); ?>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password*</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <?php echo isset($data['error-password']) ? "<p class='error ml-4'>{$data['error-password']}</p>" : (isset($data['error-length-password']) ? "<p class='error ml-4'>{$data['error-length-password']}</p>" : ""); ?>
                    </div>

                    <div class="form-group row">
                        <label for="confirm-password" class="col-sm-2 col-form-label">Password Verify*</label>
                        <input type="password" class="form-control" id="confirm-password" name="confirm-password"">
                        <?php if(isset($data['error-confirm-password'])) echo "<p class='error ml-4'>{$data['error-confirm-password']}</p>"; ?>
                    </div>

                    <div class="form-group row">
                        <label for="role" class="col-sm-2 col-form-label">Status*</label>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="status" value="1" <?php if(isset($_POST['status']) && $_POST['status'] == 1) echo "checked"; if(empty($_POST['status'])) echo "checked"; ?>>Active
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="status" value="2" <?php if(isset($_POST['status']) && $_POST['status'] == 2) echo "checked"; ?>>Banned
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