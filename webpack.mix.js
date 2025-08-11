const mix = require('laravel-mix');
const path = require('path');

mix.webpackConfig({
    resolve: {
        // добавили .mjs
        extensions: ['.mjs', '.ts', '.js', '.vue', '.json'],
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            // пусть vue$ указывает на полноценный ESM-бандл Vue 2
            'vue$': 'vue/dist/vue.esm.js'
        }
    },
    module: {
        rules: [
            {
                test: /\.ts$/,
                loader: 'ts-loader',
                options: {
                    appendTsSuffixTo: [/\.vue$/],
                    transpileOnly: true
                },
                exclude: /node_modules/
            }
        ]
    }
});

mix
    .js(['resources/js/app.js', 'node_modules/bootstrap/dist/js/bootstrap.js'], 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/admin_app.scss', 'public/css')
    .version();
