# What is this? #

This repo contains Cornershop Creative's stock WordPress install. It includes a number of oft-used plugins, our custom-built Crate theme, and a database dump to facilitate getting up and running quickly.

# How do I use it? #

Good question. Here's the general process:
### 1. Fork this repository ###
Give it the same name as the "Short Name" indicated in the [Pipeline smartsheet](https://app.smartsheet.com/b/home?lx=BQoCBSl1raFIpy4uUPSLvg) and make sure the owner is set to **cornershopcreative** instead of your own account.

### 2. SSH to the dev server ###
Use SSH to access the development server as either yourself or the dev user. Then, run this command:
```
#!bash

setupsite git@bitbucket.org:cornershopcreative/SHORTNAME.git
```
to bring all the files down from Bitbucket, set up the WordPress database, and perform some initial configuration.

Follow setupsite's directions and respond to any prompts it gives you.

### 3. Commit all configuration changes ###

```
#!bash

cd sites/SHORTNAME
git add -A .
git commit -m "Configuration changes from setupsite script"
```

### 4. Login and perform updates ###
Head to http://[SHORTNAME].[USER].cshp.co/cms/login. If there are any necessary updates:

1. Perform them
1. Commit the updates
1. Push them to bitbucket,

### 5. Have fun ###
Start customizing crate or adding themes or whatever.

***

# Managing JS plugins #

The crate theme has been configured with bower to manage javascript/jquery plugins. To use bower, first you'll need to install it if you haven't already:
```
#!bash

npm install -g bower
```

Once that's done, you can use bower to install and manage javascript plugins in Crate. Simply make sure you're in the crate directory and run the install command, e.g.
```
#!bash

bower install jquery-placeholder
```

That will install the requisite file in crate/js/plugins. When you run the grunt commands outlined below, js files in js/plugins will get smushed together into crate/js/plugins.min.js for you.

Note the `crate/js/plugins/bower.plugins.txt` file contains a reference list of frequently-used JS plugins you can install via bower.

***

# Using Grunt #

This version of crate is bundled with the necessary tools and components to utilize grunt.js to automate some tasks, such as uglifying javascript and deploying. There are two main things you should know and can do. First, though, make sure you have grunt:
```
#!bash

npm install -g grunt-cli
```

Once you have grunt, there are two main things to know about and take advantage of:

###1. Watching###
The command `grunt watch` has been set as the default such that just typing `grunt` in the root of the crate theme will run it. This will instantiate some scripts that do the following for you:

1. Minimize image files in crate/images (except those in the spr subdirectory)
2. Smush and uglify main.js and plugins.js to main.min.js and plugins.min.js, including fetching js files out of the plugins subdirectory
3. Recompile compass/sass files
4. Activate a livereload server

The above are all triggered automatically when files change; there's not you need to do. The livereload script is already built into crate so if you're on our cshp.co development server, once you've got `grunt watch` running, your JS/CSS/PHP changes should automatically prompt reloads magically. Which is awesome.

###2. Deploying###
This version of crate that includes grunt is bundled with a customized version of grunt-wordpress-deploy designed to facilitate pushing both code and database to different environments, such as production or staging.

1. Open up Gruntfile.js
2. Change the settings as needed for the "local" environment (where you're doing development) as well as for any other environments you want to be able to deploy to ("staging" is provided as an example). In addition to providing the kinds of stuff you'd need for a wp-config.php file, you can also tell it what to set the admin user's email to and set whether it should toggle the SEO/robots setting.
3. Use `grunt deploy:[environment]` e.g. `grunt deploy:staging` to migrate the entire codebase and database to that environment
4. You can also use tasks like `push_db` or `pull_files` to do other stuff, see https://github.com/webrain/grunt-wordpress-deploy for details

If you use this approach, the amount of work you'll have to do when doing a site deployment should be reduced. You'll still need to change the `config.rb` file to produce minified CSS via Compass, double-check things like file uploads and all that, but database "Update URLs", administrator email address, etc are now taken care of for you. In theory.