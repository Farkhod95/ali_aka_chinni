<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8" />
    <title>Авторизация</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    
    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="/web/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
    <link href="/web/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/web/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/web/css/animate.min.css" rel="stylesheet" />
    <link href="/web/css/style.min.css" rel="stylesheet" />
    <link href="/web/css/style-responsive.min.css" rel="stylesheet" />
    <!-- ================== END BASE CSS STYLE ================== -->
    
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="/web/plugins/pace/pace.min.js"></script>
    <!-- ================== END BASE JS ================== -->
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        /* Animated Background Shapes */
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -250px;
            right: -100px;
            animation: float 20s infinite ease-in-out;
        }
        
        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            bottom: -200px;
            left: -100px;
            animation: float 15s infinite ease-in-out reverse;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }
        
        #page-loader {
            display: none;
        }
        
        #page-container {
            width: 100%;
            max-width: 1100px;
            position: relative;
            z-index: 1;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
        
        .login-wrapper {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.25);
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-height: 90vh;
            width: 100%;
            backdrop-filter: blur(10px);
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Left Side - Gradient Section */
        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }
        
        .login-left::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
        }
        
        .login-left-content {
            position: relative;
            z-index: 2;
            color: white;
        }
        
        .login-left-content .logo-area {
            margin-bottom: 30px;
        }
        
        .login-left-content .logo-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }
        
        .login-left-content .logo-icon i {
            font-size: 32px;
            color: white;
        }
        
        .login-left-content h1 {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 12px;
            line-height: 1.2;
        }
        
        .login-left-content p {
            font-size: 15px;
            line-height: 1.6;
            opacity: 0.95;
            margin-bottom: 25px;
        }
        
        .features-list {
            list-style: none;
        }
        
        .features-list li {
            padding: 8px 0;
            display: flex;
            align-items: center;
            font-size: 14px;
            opacity: 0.9;
        }
        
        .features-list li i {
            margin-right: 12px;
            width: 24px;
            height: 24px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        
        /* Right Side - Form Section */
        .login-right {
            padding: 40px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-header {
            margin-bottom: 30px;
        }
        
        .login-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 6px;
        }
        
        .login-header p {
            color: #6b7280;
            font-size: 15px;
        }
        
        .form-group {
            margin-bottom: 18px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 18px;
            z-index: 1;
        }
        
        .form-control {
            width: 100%;
            height: 50px;
            padding: 0 20px 0 52px;
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f9fafb;
            color: #1f2937;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .form-control::placeholder {
            color: #9ca3af;
        }
        
        .btn-login {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 14px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 6px;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .helper-text {
            text-align: center;
            margin-top: 18px;
            color: #6b7280;
            font-size: 13px;
        }
        
        .helper-text i {
            margin-right: 6px;
            color: #667eea;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }
            
            .login-left {
                padding: 40px 30px;
                min-height: 350px;
            }
            
            .login-left-content h1 {
                font-size: 32px;
            }
            
            .features-list {
                display: none;
            }
            
            .login-right {
                padding: 40px 30px;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 10px;
            }
            
            .login-wrapper {
                border-radius: 16px;
            }
            
            .login-left {
                padding: 30px 24px;
                min-height: 280px;
            }
            
            .login-left-content h1 {
                font-size: 28px;
            }
            
            .login-right {
                padding: 30px 24px;
            }
            
            .login-header h2 {
                font-size: 26px;
            }
        }
        
        /* Loading Animation */
        .pace {
            pointer-events: none;
            user-select: none;
        }
        
        .pace .pace-progress {
            background: #667eea;
            position: fixed;
            z-index: 2000;
            top: 0;
            right: 100%;
            width: 100%;
            height: 3px;
        }
    </style>
</head>
<body>
    <div id="page-container">
        <?=$content?>
    </div>

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="/web/plugins/jquery/jquery-1.9.1.min.js"></script>
    <script src="/web/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
    <script src="/web/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
    <script src="/web/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
        <script src="/crossbrowserjs/html5shiv.js"></script>
        <script src="/crossbrowserjs/respond.min.js"></script>
        <script src="/crossbrowserjs/excanvas.min.js"></script>
    <![endif]-->
    <script src="/web/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/web/plugins/jquery-cookie/jquery.cookie.js"></script>
    <!-- ================== END BASE JS ================== -->
    
    <script>
        $(document).ready(function() {
            // Form animation
            $('.form-control').on('focus', function() {
                $(this).parent().addClass('focused');
            }).on('blur', function() {
                if (!$(this).val()) {
                    $(this).parent().removeClass('focused');
                }
            });
            
            // Add ripple effect to button
            $('.btn-login').on('click', function(e) {
                var ripple = $('<span class="ripple"></span>');
                $(this).append(ripple);
                
                setTimeout(function() {
                    ripple.remove();
                }, 600);
            });
        });
    </script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-53034621-1', 'auto');
        ga('send', 'pageview');
    </script>
</body>
</html>
