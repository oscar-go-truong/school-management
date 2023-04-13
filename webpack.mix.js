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

mix.postCss("resources/css/bootstrap.css", "public/css", [])
    .postCss("resources/css/font-awesome.css", "public/css", [
        //
    ])
    .postCss("resources/css/custom.css", "public/css", [
        //
    ]);
