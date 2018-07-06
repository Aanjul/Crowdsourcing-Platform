var mix = require('laravel-mix');

mix.disableNotifications();

mix.js('resources/assets/js/common.js', 'public/dist/js/')
    .js('resources/assets/js/pages/register.js', 'public/dist/js')
    .js('resources/assets/js/pages/myProfile.js', 'public/dist/js')
    .js('resources/assets/js/pages/createQuestionnaire.js', 'public/dist/js')
    .js('resources/assets/js/UsersListController.js', 'public/dist/js')
    .sass('resources/assets/sass/common.scss', 'public/dist/css')
    .sass('resources/assets/sass/auth.scss', 'public/dist/css')
    .sass('resources/assets/sass/pages/my-profile.scss', 'public/dist/css')
    .sass('resources/assets/sass/pages/projects-list.scss', 'public/dist/css')
    .sass('resources/assets/sass/pages/landing-page.scss', 'public/dist/css')
    .sass('resources/assets/sass/pages/edit-project.scss', 'public/dist/css')
    .sass('resources/assets/sass/pages/create-questionnaire.scss', 'public/dist/css')
    .extract([
        'jquery-slimscroll', 'fastclick', 'admin-lte', 'bootstrap-sweetalert', 'select2', 'bootstrap', 'jquery-toast-plugin'
    ])
    .sourceMaps()
    .version();

// move sweetalert.css to public/dist/css
// mix.copy(['node_modules/bootstrap-sweetalert/dist/sweetalert.css'], 'public/dist/css/sweetalert.css');
//
// move select2.min.css to public/dist/css
mix.copy(['node_modules/select2/dist/css/select2.min.css'], 'public/dist/css/select2.min.css');