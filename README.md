# What is this? #

This repo contains Encore's Generation to Generation WordPress site.

# How do I use it? #

Good question. Here's the general process:

### 1. SSH to the dev server ###
Use SSH to access the development server as either yourself or the dev user. Then, run this command:
```
#!bash

setupsite git@bitbucket.org:cornershopcreative/encore.git
```
to bring all the files down from Bitbucket, set up the WordPress database, and perform some initial configuration.

Follow setupsite's directions and respond to any prompts it gives you.

### 2. Commit all configuration changes ###

```
#!bash

cd sites/encore
git add -A .
git commit -m "Configuration changes from setupsite script"
```

### 3. Login and perform updates ###
Head to http://encore.[USER].cshp.co/cms/login. If there are any necessary updates:

1. Perform them
1. Commit the updates
1. Push them to bitbucket,

### 4. Have fun ###
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