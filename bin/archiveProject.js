/**
 * External Dependencies
 */
const fs = require('fs');
const archiver = require('archiver');
const ignore = require('ignore');
const { globSync } = require('glob');
const pkg = require('./../package.json');

const createArchive = (path, srcPath, slug) => {
	// Define the output stream
	const output = fs.createWriteStream(path + '/' + slug + '.zip');

	// Initialize the archive
	const archive = archiver('zip', {
		zlib: { level: 9 }, // Sets the compression level.
	});

	// Listen for all archive data to be written
	archive.pipe(output);

	// Initialize the ignorer
	const ig = ignore();

	// We ignore files defined in a potential .distignore file
	if (fs.existsSync(srcPath + '/.distignore')) {
		ig.add(fs.readFileSync(srcPath + '/.distignore', 'utf8').toString());
	}

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

			// Construct the file path
			const filePath = srcPath + '/' + file;

			// Add the file to the archive
			archive.file(filePath, {
				name: file,
				prefix: slug,
			});
		});

		// Finalize the archive
		archive.finalize();
	} catch (error) {
		console.error(error);
	}
};

createArchive('./archives', './plugin', pkg.slug + 'p');
createArchive('./archives', './theme', pkg.slug + 't');
