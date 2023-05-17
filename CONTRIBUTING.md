# Contributing to this project

Hi! Thank you for your interest in contributing to this project, we really appreciate it.

There are many ways to contribute – reporting bugs, feature suggestions, fixing bugs, submitting pull requests for enhancements.

## Reporting Bugs, Asking Questions, Sending Suggestions

Just [file a GitHub issue](./issues/new), that’s all! If you want to prefix the title with a “Question:”, “Bug:”, or the general area of the issue, that would be helpful, but by no means mandatory. If you have write access, add the appropriate labels.

If you’re filing a bug, specific steps to reproduce are helpful. Please include the URL of the page that has the bug, along with what you expected to see and what happened instead.

## Setting up the dev environment

If you want to contribute code to the project you have to set up the environment locally. Make sure that you have `node`, `npm`, `docker` and `webpack` installed.

The working directories are the `theme` & `plugin` directories. If you change something in another location of the repository the pull request will be ignored.

The development server and all dependencies are handled by [@wordpress/env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/). Make sure you have [docker installed](https://docs.docker.com/compose/install/) and run `npm start` in the directory. Your spawned WordPress instance will be available under `http://localhost` with the account `admin:password`.

Please be aware, that you should usually not write code directly on the main branch.

Start the watcher with the terminal command `npm run watch`. Webpack will make sure that the code will be compiled and that your browser gets a [LiveReload](http://livereload.com/extensions/) command.

Before committing execute the command `npm run test` to test if your code follows the general coding standards.

This project uses the `@wordpress\env` package to provide the development server. Please refer to the [`wp-env`-documentation](https://github.com/WordPress/gutenberg/tree/master/packages/env) for more details.

## I'm stuck, what do I do?

Not knowing what to do is perfectly normal for any developer or programmer. One of the main skills of a good programmer is the ability to quickly adapt patterns and find solutions. In short: Being able type the right question into Google. If you are stuck somewhere in project development we suggest the following:

1. Check out the [WordPress Codex](https://codex.wordpress.org) and read the documentation of what you're trying to build.
2. Look at how others do it. Places to look for best practices are the [TwentyNinteen project](https://github.com/WordPress/twentynineteen), the [Underscores project](https://github.com/automattic/_s) or the [TwentySeventeen project](https://github.com/WordPress/twentyseventeen).
3. Ask a senior programmer nearby

## Development Workflow

To keep the work in this repository structured and maintainable, we follow a certain way to add changes and code. A good workflow is structured like this:

1. Write or take an issue about the problem you want to solve
2. Add your own branch to the repository and add code to this branch
3. As soon as you have a presentable solution, add a pull request to the master branch
4. Get reviews for your solution and make sure the automated tests pass
5. Before merging the PR to the master branch, update your branch from master to resolve conflicts
6. Merge the PR into the master branch, test the solution and delete your branch

### Naming branches

Ideally name your branches with prefixes and descriptions, like this: [type]/[change]. A good prefix would be:

* add/ = add a new feature
* try/ = experimental feature, "tentatively add"
* update/ = update an existing feature
* fix/ = fix a bug or unwanted behavior

For example, add/gallery-block means you're working on adding a new gallery block.

## Releasing Updates

The release workflow is more or less automated. A github workflow takes the code, runs the tests and builds it into a release-ready zip. This zip is then attached to a GitHub release, from where it is then pushed to our own update server. To create a release refer to the [`Create Release`-Action](./actions/workflows/release.yml), trigger it with the `Run Workflow` button and define the specifics of the new version.

Please refer to the documentation of [npm version](https://docs.npmjs.com/cli/v7/commands/npm-version) to learn more about how to set the version. In determining which version should be the next one we try to follow the SemVer Specification. Read [more about that here](https://semver.org/).

While theoretically everyone with write access to the repository has the ability to push a release, the release should only be done by one person, either the *build master* or the *project manager*.
