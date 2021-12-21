<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <title>Webtest Coralis Studio</title>
</head>

<body>
    <div class="row d-flex justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('home/forgotPassword'); ?>" method="POST">
                        <div class="mt-3 mb-3">
                            <h3 class="text-center">Forgot Password ?</h3>
                        </div>
                        <div class="mb-3">
                            <input type="text" value="<?php echo set_value('email') ?>" name="email" id="email" class="form-control" placeholder="Insert Email . . .">
                            <?php echo form_error('email', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary my-3">Send Email</button>
                            <p><a href="<?= base_url('home'); ?>">Back</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

</body>

</html>