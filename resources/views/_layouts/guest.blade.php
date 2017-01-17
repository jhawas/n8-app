<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>{{ config('app.site_title') }} - Login</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="A dating app admin dashboard" name="description" />
    <meta content="Rosemale-John" name="author" />

    <!-- CDN -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Uniform.js/3.0.0/css/default.css" rel="stylesheet" type="text/css" />
    <!-- End CDN -->

    <link href="/assets/pages/css/login.css" rel="stylesheet" type="text/css" />
    <link href="/css/global.css" id="style_components" rel="stylesheet" type="text/css" />

    <link href="/css/themes.css" rel="stylesheet" type="text/css" />

    <link rel="icon" href="http://n8core.com/wp-content/uploads/2015/10/favicon.png" type="image/png">
</head>

<body class="page-md login">
    <div class="menu-toggler sidebar-toggler">
    </div>
    <div class="logo">
        <a href="/">
            <img src="/img/logo.png" alt="Logo" />
        </a>
    </div>
    <div class="content">
        @yield('content')
    </div>
    <div class="copyright">
        2016 © Mach1 Online. Admin Dashboard.
    </div>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Uniform.js/3.0.0/js/jquery.uniform.standalone.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js" type="text/javascript"></script>
    <script src="/assets/globals/js/metronic.js" type="text/javascript"></script>
    <script src="/assets/admin/js/layout.js" type="text/javascript"></script>
    <script src="/assets/pages/js/login.js" type="text/javascript"></script>

    <script>
    jQuery(document).ready(function() {
        Metronic.init();
        Layout.init();
        Login.init();
    });
    </script>
</body>

</html>
