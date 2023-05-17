# WordPress Project Boilerplate
[![🏗 Build & Deploy](../../actions/workflows/main.yml/badge.svg)](../../actions/workflows/main.yml)

There are probably more WordPress boilerplates than actual themes & plugins available for bootstrapping your work on an amazing WordPress project. And that is very much okay, because every developer, every agency has their own little flavors in how they like to do things.

That is the reason we made this  boilerplate. We liked the work of so many other developers before us, but we never found the perfect boilerplate that fit our style of work. The result is this, a very opinionated project boilerplate based on @wordpress/env, webpack and PostCSS.

Included in this boilerplate is the structure needed to release a theme and an acompanying plugin for a WordPress client project. As per WordPress guidlines and best practices, all business and data logic should be put into the plugin, all presentation and styles should be put into the theme.

This boilerplate will give you all the tools you need to write, test and publish your WordPress project, either for commercial clients or to publish the theme & plugin in the WordPress.org repository.

## Getting started

These steps will guide you through the setup process up until you can start
writing functions, markup and styles for your project.

For the sake of scope we will assume that you know the slug of your project.
Please make sure that the slug is unique to the system of the client, our
projects and the WordPress.org project repository.

We will also assume, that you have already configured your GitHub repository to
your liking, downloaded the source of the boilerplate and uploaded it to your
new repository. So let's get started:

### Project Slug & Names
- [ ] Search & Replace (case sensitive) `lhpbp` with your new WordPress project slug
- [ ] Adjust details in `package.json` and update the file headers in `theme/style.css` and `plugin/lhpbpp.php`
- [ ] Rename the main plugin file from `plugin/lhpbpp.php` to `plugin/<new_project_slug>p.php`

### Running the enviroment
- [ ] Type `npm start` into the terminal to spin up the docker enviroment
- [ ] Open `http://localhost/wp-admin` and log in with `admin:password`

### Test Release
- [ ] Add the `GH_ADMIN_TOKEN` secret to the [action secrets](../../settings/secrets/actions) and [dependabot secrets](../../settings/secrets/dependabot).
- [ ] Create a [patch release](../../actions/workflows/release.yml) with the github action
- [ ] Check if the [release](../../releases) has been created and uploaded in the GitHub release section

### Finishing touches
- [ ] Edit the `project-README.md` with the appropriate text about your theme
- [ ] Delete (or rename) the `README.md` (this file)
- [ ] Rename the `project-README.md` to `README.md`
- [ ] 🎉  Celebrate!
