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

mix.js(['resources/assets/js/app.js'], 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .browserSync(process.env.MIX_SENTRY_DSN_PUBLIC);
   //.browserSync('http://st13-bookkeeping/');

// mix.setPublicPath('public');
// mix.webpackConfig({
//     devServer: {
//         // contentBase: SRC_DIR,
//         contentBase: 'public',
//         //help tools, we will see errors on overlay screen
//         watchContentBase: true
//     }
// });