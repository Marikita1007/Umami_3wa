const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or subdirectory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')
    .addEntry('recipes-ingredients-form', './assets/js/recipes-ingredients-form.js')
    .addEntry('cuisines-form-ajax', './assets/js/cuisines-form-ajax.js')

    // Added scss files
    .addStyleEntry('base-styles', './assets/styles/base.scss')
    .addStyleEntry('header-styles', './assets/styles/header.scss')
    .addStyleEntry('footer-styles', './assets/styles/footer.scss')
    .addStyleEntry('home-styles', './assets/styles/home.scss')
    .addStyleEntry('notification-styles', './assets/styles/notification.scss')
    .addStyleEntry('categories-styles', './assets/styles/categories.scss')
    .addStyleEntry('show-recipe-styles', './assets/styles/recipes/show-recipe.scss')
    .addStyleEntry('recipe-form-styles', './assets/styles/recipes/recipe-form.scss')
    .addStyleEntry('recipes-dashboard-styles', './assets/styles/recipes/recipes-dashboard.scss')
    .addStyleEntry('recipes-filters', './assets/styles/recipes/recipes-filters.scss')
    .addStyleEntry('cuisines-dashboard-styles', './assets/styles/cuisines/cuisines-dashboard.scss')
    .addStyleEntry('card-styles', './assets/styles/components/card.scss')
    .addStyleEntry('form-styles', './assets/styles/components/form.scss')
    .addStyleEntry('button-styles', './assets/styles/components/button.scss')
    .addStyleEntry('user-profile', './assets/styles/profiles/user_profile.scss')

    // Added bootstrap CSS
    .addStyleEntry('bootstrap-css', './node_modules/bootstrap/dist/css/bootstrap.css')

    // Added bootstrap JavaScript
    .addEntry('bootstrap-js', './node_modules/bootstrap/dist/js/bootstrap.js')

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // configure Babel
    // .configureBabel((config) => {
    //     config.plugins.push('@babel/a-babel-plugin');
    // })

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
