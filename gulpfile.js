var elixir = require('laravel-elixir');
require('laravel-elixir-vueify');
require('gulp');
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
	mix.browserify('site.js', 'public/js/site_es6.js');
	mix.browserify('seats.js', 'public/js/compiled/seats_es6.js');
	mix.browserify('checkout.js', 'public/js/compiled/checkout_es6.js');
	mix.browserify('cancellation.js', 'public/js/compiled/cancellation_es6.js');
	mix.browserify('routes.js', 'public/js/compiled/routes_es6.js');
});

elixir(mix => {
	mix.browserSync({
		proxy: 'localhost:8000'
	})
})