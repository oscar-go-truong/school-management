const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/jquery-1.10.2.js", "public/js")
    .js("resources/js/bootstrap.min.js", "public/js")
    .js("resources/js/jquery.metisMenu.js", "public/js")
    .js("resources/js/custom.js", "public/js")
    .postCss("resources/css/bootstrap.css", "public/css", [
        //
    ])
    .postCss("resources/css/font-awesome.css", "public/css", [
        //
    ])
    .postCss("resources/css/custom.css", "public/css", [
        //
    ]);
