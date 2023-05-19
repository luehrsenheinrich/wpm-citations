/**
 * External dependencies
 */
const { globSync } = require('glob');
const path = require('path');
const TerserPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');
const LiveReloadPlugin = require('webpack-livereload-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');

/**
 * Plugin backend JavaScript files
 *
 * @type {string[]}
 */
const PluginBackendJs = globSync('./plugin/admin/src/js/*.js').reduce(function (
	obj,
	el
) {
	obj['js/' + path.parse(el).name + '.min'] = el;
	obj['js/' + path.parse(el).name] = el;
	return obj;
},
{});

/**
 * Plugin backend CSS files
 *
 * @type {string[]}
 */
const PluginBackendCSS = globSync('./plugin/admin/src/css/*.css').reduce(
	function (obj, el) {
		obj['css/' + path.parse(el).name + '.min'] = el;
		return obj;
	},
	{}
);

/**
 * Plugin frontend CSS files
 *
 * @type {string[]}
 */
const PluginFrontendCSS = globSync('./plugin/src/css/*.css').reduce(function (
	obj,
	el
) {
	obj['css/' + path.parse(el).name + '.min'] = el;
	return obj;
},
{});

/**
 * The WordPress JS loader.
 * The opinionated WordPress JS loader. We only use this within the WordPress admin.
 *
 * @type {Object}
 */
const wordpressJsLoader = {
	test: /\.js$/,
	exclude: /node_modules/,
	use: {
		loader: 'babel-loader',
		options: {
			presets: ['@wordpress/babel-preset-default'],
			plugins: [
				'@wordpress/babel-plugin-import-jsx-pragma',
				'@babel/plugin-transform-react-jsx',
				'lodash',
			],
		},
	},
};

/**
 * Our default webpack config
 *
 * @type {Object}
 */
const defaultConfig = {
	mode: 'production',
	optimization: {
		minimizer: [
			new TerserPlugin({
				include: /\.min\.js$/,
			}),
		],
	},
	output: {
		clean: true,
		filename: '[name].js',
	},
	resolve: {
		modules: ['node_modules'],
		preferRelative: true,
	},
	plugins: [
		new LiveReloadPlugin({
			useSourceHash: true,
		}),
		new RemoveEmptyScriptsPlugin({
			extensions: ['css', 'php'],
		}),
		new MiniCssExtractPlugin({
			filename: '[name].css',
		}),
	],
	module: {
		rules: [
			{
				test: /\.css$/i,
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: {
							modules: false,
							import: false,
							url: false,
							sourceMap: true,
							importLoaders: 1,
						},
					},
					{
						loader: 'postcss-loader',
						options: {
							sourceMap: true,
						},
					},
				],
			},
			{
				test: /\.php$/,
				loader: 'null-loader',
			},
			{
				test: /\.svg$/i,
				issuer: /\.[jt]sx?$/,
				use: ['@svgr/webpack'],
			},
		],
	},
};

/**
 * The webpack config to bundle CSS and JS for the Plugin backend.
 *
 * @type {Object}
 */
const pluginBackendWebpackOptions = {
	...defaultConfig,
	name: 'pluginBackend',
	entry: {
		...PluginBackendJs,
		...PluginBackendCSS,
	},
	output: {
		...defaultConfig.output,
		path: path.resolve(__dirname, 'plugin/admin/dist'),
	},
	module: {
		rules: [...defaultConfig.module.rules, wordpressJsLoader],
	},
	plugins: [
		...defaultConfig.plugins,
		new DependencyExtractionWebpackPlugin({
			outputFormat: 'json',
			combineAssets: true,
		}),
	],
};

/**
 * The default JS loader.
 * This is a very vanilla JS loader based on Babel preset-env.
 *
 * @type {Object}
 */
const defaultJsLoader = {
	test: /\.js$/,
	exclude: /node_modules/,
	use: {
		loader: 'babel-loader',
		options: {
			presets: ['@babel/preset-env'],
		},
	},
};

/**
 * The webpack config to bundle CSS and JS for the Theme frontend.
 *
 * @type {Object}
 */
const pluginFrontendWebpackOptions = {
	...defaultConfig,
	name: 'pluginFrontend',
	entry: {
		...PluginFrontendCSS,
	},
	output: {
		...defaultConfig.output,
		path: path.resolve(__dirname, 'plugin/dist'),
	},
	module: {
		rules: [...defaultConfig.module.rules, defaultJsLoader],
	},
};

module.exports = [pluginBackendWebpackOptions, pluginFrontendWebpackOptions];
