const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .extract(['jquery', 'bootstrap', 'select2', 'summernote'])
    .scripts([
        'resources/js/plugins/editor-functions.js',
        'resources/js/plugins/component-functions.js',
        'resources/js/plugins/bootstrap-classes.js',
        'resources/js/plugins/fontAwesomeIconList.js',
        'resources/js/plugins/bootstrap4-components.js',
        'resources/js/plugins/bootstrap-colors.js',
        'resources/js/plugins/bootstrap-columns.js',
        'resources/js/plugins/bootstrap-components.js',
        'resources/js/plugins/gallery.js',
        'resources/js/plugins/icons.js',
        'resources/js/plugins/tags.js',
        'resources/js/plugins/textcomplete.js',
        'resources/js/plugins/summernote-editor-init.js',
    ], 'public/js/bootsum.js')
    .sass('resources/sass/app.scss', 'public/css')
