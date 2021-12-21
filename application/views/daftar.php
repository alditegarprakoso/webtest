<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <title>Webtest Coralis Studio</title>
</head>

<body>
    <div class="row d-flex justify-content-center my-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('home/daftar') ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <h3 class="text-center">Register - Coralis Studio</h3>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <?php
                            $nama = '';
                            if ($this->session->flashdata('nama')) {
                                $nama = $this->session->flashdata('nama');
                            } else {
                                $nama =  set_value('nama');
                            }
                            ?>
                            <input type="text" name="nama" id="nama" class="form-control" value="<?= $nama; ?>" required autofocus>
                            <?php echo form_error('nama', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <?php
                            $email = '';
                            if ($this->session->flashdata('email')) {
                                $email = $this->session->flashdata('email');
                            } else {
                                $email =  set_value('email');
                            }
                            ?>
                            <input type="email" name="email" id="email" class="form-control" value="<?= $email; ?>" required>
                            <?php echo form_error('email', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                            <?php echo form_error('password', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        <div class="mb-3">
                            <label for="password_2" class="form-label">Ulangi Password</label>
                            <input type="password" name="password_2" id="password_2" class="form-control" required>
                            <?php echo form_error('password_2', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        <div class="mb-5">
                            <label for="foto" class="form-label">Profile</label>
                            <input class="form-control" type="file" name="foto" id="foto" onchange="tampilkanPreview(this,'preview')" style="cursor: pointer;" required>
                            <?= $this->session->flashdata('error_image'); ?>
                            <img id="preview" width="200px" class="mt-3" />
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mb-4">Register</button>
                            <p><a href="<?= base_url('home'); ?>">Already have an account? Login now</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        // Preview Image
        function tampilkanPreview(userfile, idpreview) {
            var gb = userfile.files;
            for (var i = 0; i < gb.length; i++) {
                var gbPreview = gb[i];
                var imageType = /image.*/;
                var preview = document.getElementById(idpreview);
                var reader = new FileReader();
                if (gbPreview.type.match(imageType)) {
                    //jika tipe data sesuai
                    preview.file = gbPreview;
                    reader.onload = (function(element) {
                        return function(e) {
                            element.src = e.target.result;
                        };
                    })(preview);
                    //membaca data URL gambar
                    reader.readAsDataURL(gbPreview);
                } else {
                    //jika tipe data tidak sesuai
                    alert("Maaf, Thumbnail tidak akan di upload karena tipe file tidak sesuai. Thumbnail harus bertipe .png / .jpeg / .jpg.");
                }
            }
        }
    </script>
</body>

</html>