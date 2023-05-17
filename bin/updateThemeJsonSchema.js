/* eslint-disable no-console */

const request = require('request');
const fs = require('fs');

/**
 * Load the default theme schema.
 */
request.get('https://schemas.wp.org/trunk/theme.json', (err, res, body) => {
	if (!err && res.statusCode === 200) {
		/**
		 * @see https://schemas.wp.org/trunk/theme.json
		 */
		const themeJsonSchema = JSON.parse(body);

		/**
		 * Modify the default theme schema to our needs.
		 */
		const palette =
			themeJsonSchema.definitions.settingsPropertiesColor.properties.color
				.properties.palette;

		palette.items.properties = {
			...palette.items.properties,
			hover: {
				description:
					'CSS hex or rgba(a) string of a matching hover color.',
				type: 'string',
			},
			contrast: {
				description:
					'CSS hex or rgba(a) string of a matching contrast color.',
				type: 'string',
			},
		};

		const fileContent = JSON.stringify(themeJsonSchema, null, 2);

		fs.writeFile('./schemas/theme.json', fileContent, (error) => {
			if (error) {
				console.error(error);
			} else {
				console.log('Theme schema updated.');
			}
		});
	}
});
