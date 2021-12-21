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
                    <form action="<?= base_url('home/changePassword'); ?>" method="POST">
                        <div class="mb-3">
                            <h3 class="text-center">Change your password for <?= $email; ?></h3>
                        </div>
                        <div class="mb-3">
                            <label for="password1" class="form-label">Password</label>
                            <input type="password" name="password1" id="password1" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password2" class="form-label">Password</label>
                            <input type="password" name="password2" id="password2" class="form-control" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mb-3">Confirm New Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

</body>

</html>