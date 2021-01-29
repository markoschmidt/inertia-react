const mix = require('laravel-mix')
const cssImport = require('postcss-import')
const cssNesting = require('postcss-nesting')
const path = require('path')
const tailwindcss = require('tailwindcss')
const autoprefixer = require('autoprefixer')

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

mix
  .js(['resources/js/app.jsx'], 'js')
  .react()
  .postCss('resources/css/app.css', 'css')
  .options({
    postCss: [
      cssImport(),
      cssNesting(),
      tailwindcss('tailwind.config.js'),
    ],
  })
  .webpackConfig({
    output: { chunkFilename: 'js/[name].js?id=[chunkhash]' },
    resolve: {
      alias: {
        '@': path.resolve('resources/js'),
      },
    },
  })
  .babelConfig({
    plugins: [
      '@babel/plugin-syntax-dynamic-import',
      '@babel/plugin-proposal-class-properties',
    ],
  })
  .version()

// https://browsersync.io/docs/options/
// mix.browserSync({
//   proxy: 'laravel.test',
//   ws: false,
// })
