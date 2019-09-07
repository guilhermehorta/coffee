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

/*
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');
*/

mix.sass('resources/sass/app.scss', 'public/css')
	.sass('resources/sass/flexbox_container.scss', 'public/css')
	.styles([
		'public/css/app.css',
		'public/css/flexbox_container.css'
	],'public/css/all.css');