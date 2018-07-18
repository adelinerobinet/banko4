var Encore = require('@symfony/webpack-encore');
var CopyWebpackPlugin = require('copy-webpack-plugin');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')

    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')

    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    // .addEntry('js/app', './assets/js/app.js')
    //.addStyleEntry('css/app', './assets/css/main.scss')
    .addStyleEntry('css/app', [
        './assets/css/global.scss',
        './assets/css/main.css',
        './assets/css/theme.css'
    ])
    .addEntry('js/app', [
        './assets/js/popper.min.js',
        './assets/js/bootstrap.js'
    ])

    // uncomment if you use Sass/SCSS files
    .enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    .autoProvidejQuery()

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // directory
    .addPlugin(new CopyWebpackPlugin([{ from: './assets/img', to: 'img' }]))
;

module.exports = Encore.getWebpackConfig();
