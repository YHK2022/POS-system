<?php $general_setting = DB::table('general_settings')->find(1); ?>
<!doctype html>
<html lang="en">

<head>
    <title><?php echo e($general_setting->site_title); ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    
    <link rel="stylesheet" href="<?php echo asset('/application/css/style.css'); ?>" id="theme-stylesheet" type="text/css">
    <link rel="icon" type="image/png" href="<?php echo e(url('/logo', $general_setting->site_logo)); ?>" />
</head>

<body>
    <section class="ftco-section">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="login-wrap p-4 p-md-5">
                        <div class=" d-flex align-items-center justify-content-center">
                            <img src="<?php echo e(url('/logo', $general_setting->site_logo)); ?>" style="  width: 150px;">
                        </div>

                        <h3 class="text-center mb-4">Have an account?</h3>
                        <form method="POST" action="<?php echo e(route('login')); ?>" id="login-form">
                            <?php echo csrf_field(); ?> <div class="form-group">
                                <input id="login-username" type="text" name="name" required
                                    class="form-control rounded-left" value="" placeholder="Username">
                                
                                <?php if($errors->has('name')): ?>
                                    <p>
                                        <strong><?php echo e($errors->first('name')); ?></strong>
                                    </p>
                                <?php endif; ?>

                                
                            </div>
                            <div class="form-group d-flex">

                                <input id="login-password" type="password" name="password" required
                                    class="form-control rounded-left" value="" placeholder="Password">
                                <?php if($errors->has('name')): ?>
                                    <p>
                                        <strong><?php echo e($errors->first('name')); ?></strong>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group d-md-flex">
                                <div class="w-50">
                                    <label class="checkbox-wrap checkbox-primary">Remember Me
                                        <input type="checkbox" checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="w-50 text-md-right">
                                    <a href="#">Forgot Password</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary rounded submit p-3 px-5">Get
                                    Started</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript" src="<?php echo asset('/application/js/jquery.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('/application/js/proper.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('/application/js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('/application/js/main.js'); ?>"></script>
</body>

</html>
<?php /**PATH /home/heri/Downloads/Inventory-Management-System-with-POS-HRM-Accounting-main/resources/views/auth/login.blade.php ENDPATH**/ ?>