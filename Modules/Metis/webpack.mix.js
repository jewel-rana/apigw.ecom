const dotenvExpand = require('dotenv-expand');
dotenvExpand(require('dotenv').config({ path: '../../.env'/*, debug: true*/}));

const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public').mergeManifest();

mix.js(__dirname + '/Resources/assets/js/app.js', 'js/metis.js')
    .sass( __dirname + '/Resources/assets/sass/app.scss', 'css/metis.css');

mix.js(__dirname + '/Resources/assets/js/datatable.js', 'js/metis-datatable.js')
    .sass( __dirname + '/Resources/assets/sass/datatable.scss', 'css/metis-datatable.css');

if (mix.inProduction()) {
    mix.version();
}
