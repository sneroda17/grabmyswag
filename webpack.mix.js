let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('resources/assets/sass/app.scss', '../resources/assets/css/app.css')
    .styles(['resources/assets/css/app.css', 'resources/assets/css/font-awesome.min.css'], 'public/css/app.css');

mix.copyDirectory('resources/assets/css', 'public/css');
mix.copyDirectory('resources/assets/sass/images', 'public/images');
mix.copyDirectory('resources/assets/js', 'public/js');
mix.copyDirectory('resources/assets/fonts', 'public/fonts');
mix.copy('node_modules/jquery-editable-select/dist/jquery-editable-select.css', 'public/css/jquery-editable-select.css');
mix.copy('node_modules/jquery-editable-select/dist/jquery-editable-select.js', 'public/js/jquery-editable-select.js');
mix.copy('node_modules/jquery/dist/jquery.slim.js', 'public/js/jquery.slim.js');