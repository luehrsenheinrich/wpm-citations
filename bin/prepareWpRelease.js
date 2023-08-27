/**
 * This file is used to prepare the release of the plugin to the WordPress.org repository.
 */

/**
 * External Dependencies
 */
const fs = require('fs');
const ignore = require('ignore');
const { globSync } = require('glob');
const path = require('path');

/**
 * Prepare the release of the plugin to the WordPress.org repository.
 * We do this by copying the files from the plugin directory to the target directory.
 * We also ignore files defined in a potential .distignore file.
 *
 * @param {string} srcPath    The path to the plugin source files.
 * @param {string} targetPath The path to the plugin target files.
 */
const prepareRelease = (srcPath, targetPath) => {
	// Initialize the ignorer
	const ig = ignore();

	// We ignore files defined in a potential .distignore file
	if (fs.existsSync(srcPath + '/.distignore')) {
		ig.add(fs.readFileSync(srcPath + '/.distignore', 'utf8').toString());
	}

	// Delete the target directory
	fs.rmSync(targetPath, { recursive: true, force: true });

	try {
		const files = globSync('**/*', {
			cwd: srcPath,
			nodir: true,
		});

		files.forEach((file) => {
			// Skip ignored files
			if (ig.ignores(file)) {
				return;
			}

			// Get the full target path
			const targetFile = targetPath + '/' + file;
			const srcFile = srcPath + '/' + file;

			// Get the directory of the target file
			const targetDir = path.dirname(targetFile);

			// Create the directory if it doesn't exist
			if (!fs.existsSync(targetDir)) {
				fs.mkdirSync(targetDir, { recursive: true });
			}

			// Copy the file from the source to the target directory
			fs.copyFileSync(srcFile, targetFile);
		});
	} catch (error) {
		// eslint-disable-next-line no-console
		console.error(error);
	}
};

prepareRelease('./plugin', './.wordpress-org-plugin');
