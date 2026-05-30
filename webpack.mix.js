const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Css
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/assets/js')
    .js('resources/js/admin.js', 'public/assets/admin/js')
    .vue()
    .css('resources/css/app.css', 'public/assets/css');

if (require('fs').existsSync('node_modules/@ffmpeg/core/dist/umd/ffmpeg-core.wasm')) {
    mix.copy('node_modules/@ffmpeg/core/dist/umd/ffmpeg-core.js', 'public/vendor/ffmpeg/ffmpeg-core.js')
        .copy('node_modules/@ffmpeg/core/dist/umd/ffmpeg-core.wasm', 'public/vendor/ffmpeg/ffmpeg-core.wasm');
}
