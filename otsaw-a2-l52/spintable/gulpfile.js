var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */


elixir(function(mix) {
    mix.sass([
        /* SASS */
            "app.scss",
        ])
        .styles([
            "plugins/datapicker/datepicker3.css",
            "plugins/daterangepicker/daterangepicker-bs3.css",
        ],
        "public/assets/css"
        )
        .copy( "public/css", "public/assets/css")
        .version('public/assets/css');
        mix.scripts(
        [
            "jquery-2.1.1.js",
            "bootstrap.js",
            "plugins/metisMenu/jquery.metisMenu.js",
            "plugins/slimscroll/jquery.slimscroll.min.js",
            "plugins/fullcalendar/moment.min.js",
            "plugins/datapicker/bootstrap-datepicker.js",
            "plugins/daterangepicker/daterangepicker.js",
            "custom.js",

            //"plugins/iCheck/icheck.min.js"
        ],
        "public/assets/js"
        )
        .copy( "resources/assets/patterns", "public/assets/css/patterns")
        .copy( "node_modules/bootstrap-sass/assets/fonts/bootstrap", "public/assets/fonts")
        .copy( "node_modules/font-awesome/fonts", "public/assets/fonts")
    ;
});
