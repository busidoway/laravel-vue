<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Primary Meta Tags -->
    <title>Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="Admin">
    <meta name="author" content="">
    <meta name="description" content="">
    <meta name="keywords" content="" />
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="/assets/img/favicon/safari-pinned-tab.svg" color="#ffffff">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <!-- Sweet Alert -->
    <link type="text/css" href="/vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Notyf -->
    <link type="text/css" href="/vendor/notyf/notyf.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/vendor/fontawesome/css/all.min.css">
    <!-- Volt CSS -->
    <link type="text/css" href="/css/volt.css" rel="stylesheet">
    <link type="text/css" href="/css/datepicker.min.css" rel="stylesheet">
    <link type="text/css" href="/css/admin_app.css" rel="stylesheet">
</head>

<body>
    <div id="app">

        @include('admin.includes.navbar')

        <main class="content">

            @include('admin.includes.header')

            <!-- <router-view></router-view> -->

            @yield('content')

        </main>

    </div>

    <script src="/js/jquery-3.6.0.min.js"></script>
    <!-- Core -->
    <script src="/vendor/@popperjs/core/dist/umd/popper.min.js"></script>
    <!-- <script src="/vendor/bootstrap/dist/js/bootstrap.min.js"></script> -->

    <!-- Vendor JS -->
    <script src="/vendor/onscreen/dist/on-screen.umd.min.js"></script>

    <!-- Slider -->
    <script src="/vendor/nouislider/distribute/nouislider.min.js"></script>

    <!-- Smooth scroll -->
    <script src="/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

    <!-- Charts -->
    <script src="/vendor/chartist/dist/chartist.min.js"></script>
    <script src="/vendor/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>

    <!-- Datepicker -->
    <script src="/vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>

    <!-- Sweet Alerts 2 -->
    <script src="/vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>

    <!-- Moment JS -->
    <script src="/vendor/moment/moment.min.js"></script>

    <!-- Vanilla JS Datepicker -->
    <!-- <script src="/vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script> -->


    <!-- Notyf -->
    <script src="/vendor/notyf/notyf.min.js"></script>

    <!-- Simplebar -->
    <script src="/vendor/simplebar/dist/simplebar.min.js"></script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script src="/vendor/fontawesome/js/all.min.js"></script>

    <script src="/js/datepicker.min.js"></script>

    <!-- Volt JS -->
    <script src="/assets/js/volt.js"></script>

    <script src="{{ asset('js/tinymce_7.0.1/tinymce.min.js') }}"></script>

{{--    <script src="/js/app.js"></script>--}}
    <script src="{{ mix('js/app.js') }}"></script>

    <script>
        tinymce.init({
            license_key: "gpl",
            promotion: false,
            selector: '.tinymce-editor',
            language: "ru",
            plugins: [
                "autolink", "lists", "link", "charmap", "preview", "code", "image", "fullscreen",
                "table", "advlist", "visualblocks", "searchreplace", "autosave"
            ],
            toolbar: [
                "newdocument cut copy paste bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist | link unlink anchor | removeformat code | preview fullscreen",
                "customBtnName customBtnEmail"
            ],
            toolbar_mode: 'sliding',
        });

        tinymce.init({
            license_key: "gpl",
            promotion: false,
            selector: '.tinymce-editor-simple',
            language: "ru",
            height: 200,
            menubar: false,
            plugins: [
                "autolink", "lists", "link", "preview", "fullscreen",
                "advlist", "visualblocks", "autosave", "code"
            ],
            toolbar: [
                "cut copy paste bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | fontsize | link unlink anchor | removeformat code | preview fullscreen"
            ],
            toolbar_mode: 'sliding',
        });
    </script>

</body>
</html>
