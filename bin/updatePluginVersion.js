/**
 * We need to update the plugin version in the main plugin file.
 * This is done by reading the plugin file, finding the lines that
 * contains the version number, and replacing it with the new version.
 */

/**
 * We do not want eslint to report console calls.
 */
/* eslint-disable no-console */

const fs = require('fs');
const pkg = require('../package.json');

/**
 * Read the main plugin file.
 */
fs.readFile(`./plugin/${pkg.slug}.php`, (err, data) => {
	if (err) {
		console.error(err);
		return;
	}

	// Find the file doc comment.
	const versionRowRegex = /[\s?]\*[\s?]Version:[\s?]\d+.\d+.\d+/;

	// Build the new version row.
	const newVersionRow = ` * Version: ${pkg.version}`;

	// Replace the existing version row.
	const newData = data.toString().replace(versionRowRegex, newVersionRow);

	// Write the new content to the file.
	fs.writeFile(`./plugin/${pkg.slug}.php`, newData, () => {
		console.log(`Plugin version in ${pkg.slug}.php updated.`);
		if (err) {
			console.error(err);
		}
	});
});

/**
 * Read the plugin readme file.
 */
fs.readFile(`./plugin/readme.txt`, (err, data) => {
	if (err) {
		console.error(err);
		return;
	}

	// Find the file doc comment.
	const versionRowRegex = /Stable tag:[\s?]\d+.\d+.\d+/;

	// Build the new version row.
	const newVersionRow = `Stable tag: ${pkg.version}`;

	// Replace the existing version row.
	const newData = data.toString().replace(versionRowRegex, newVersionRow);

	// Write the new content to the file.
	fs.writeFile(`./plugin/readme.txt`, newData, () => {
		console.log(`Plugin version in readme.txt updated.`);
		if (err) {
			console.error(err);
		}
	});
});
