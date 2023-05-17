module.exports = {
	plugins: [
		['postcss-import'],
		[
			'@csstools/postcss-global-data',
			{
				files: [
					'./theme/src/css/vars.css',
					'./theme/src/css/vars/_media-queries.css',
				],
			},
		],
		[
			'postcss-preset-env',
			{
				stage: 1,
			},
		],
		['cssnano'],
	],
};
