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
//

mix.js(
    [
        "resources/js/app.js",
        "resources/js/vendors/config.js",
        "resources/js/vendors/form-repeater.min.js",
        "resources/js/vendors/jquery.repeater.min.js",
    ], "public/js")
    .vue(3)
    .sass("resources/sass/app.scss", "public/css")
    .version();

mix.webpackConfig({
    output: {
        chunkFilename: "js/[name].js?id=[chunkhash]",
    },
    stats: {
        children: false
   },
    // optimization: {
    //     minimize: true, // Enable minification
    // }
}).disableNotifications();


