<?php
if (!$this->session->userdata('email')) {
    $this->session->set_flashdata('message', '<div class="alert alert-danger">You are not logged in</div>');
    redirect('home');
}
?>
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
                    <div class="mb-3">
                        <h3 class="text-center">Your Data - Coralis Studio</h3>
                    </div>
                    <div class="mb-5 text-center">
                        <img src="<?= base_url('assets/images/foto/' . $user['foto']); ?>" class="img-thumbnail rounded mb-3" width="200">
                        <h3><?= $user['name']; ?></h3>
                        <p><?= $user['email']; ?></p>
                    </div>
                    <div class="text-center">
                        <a href="<?= base_url('home/logout'); ?>" class="btn btn-sm btn-primary">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>