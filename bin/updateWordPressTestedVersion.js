/**
 * We need to update the minimum WordPress version in the main plugin file.
 * This is done by reading the plugin file, finding the lines that
 * contains the minimum WordPress version, and replacing it with the new version.
 */

/**
 * We do not want eslint to report console calls.
 */
/* eslint-disable no-console */

const fs = require('fs');

/**
 * Get the current WordPress version from an api call.
 */
const getWordPressVersion = () => {
	return new Promise((resolve, reject) => {
		const https = require('https');
		https
			.get('https://api.wordpress.org/core/version-check/1.7/', (res) => {
				let body = '';
				res.on('data', (chunk) => {
					body += chunk;
				});
				res.on('end', () => {
					const parsed = JSON.parse(body);
					resolve(parsed.offers[0].version);
				});
			})
			.on('error', (err) => {
				reject(err);
			});
	});
};

/**
 * Update the minimum WordPress version in the plugin readme file.
 *
 * @param {string} version The new WordPress version.
 */
const updateReadme = (version) => {
	return new Promise((resolve, reject) => {
		// Read the plugin readme file.
		fs.readFile(`./plugin/readme.txt`, (err, data) => {
			if (err) {
				reject(err);
				return;
			}

			// Find the file doc comment.
			const versionRowRegex = /Tested up to:[\s?](\d+(\.\d+)*)/;

			// Build the new version row.
			const newVersionRow = `Tested up to: ${version}`;

			// Replace the existing version row.
			const newData = data
				.toString()
				.replace(versionRowRegex, newVersionRow);

			// Write the new content to the file.
			fs.writeFile(`./plugin/readme.txt`, newData, () => {
				console.log(
					`Tested up to version in readme.txt update to ${version}.`
				);
				if (err) {
					reject(err);
				}
				resolve();
			});
		});
	});
};

getWordPressVersion().then((version) => {
	updateReadme(version);
});
