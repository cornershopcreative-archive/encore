module.exports = function(grunt) {
	'use strict';

  grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

    // watch for file changes and trigger sass, uglify, imagemin and livereload
    watch: {
      options: {
				livereload: 12345,
			},
      sass: {
        files: ['sass/**/*.{scss,sass}'],
        tasks: ['sass']
      },
      uglify: {
        files: ['js/plugins.js', 'js/plugins/**/*.js', 'js/main.js'],
        tasks: ['uglify']
      },
      images: {
				files: ['images/spr/*.png', 'images/*.{png,jpg,gif}'],
				tasks: ['sprite','imagemin']
      },
      files: "*.php"
    },

		// spriting from https://github.com/Ensighten/grunt-spritesmith
		sprite: {
			all: {
				src: 'images/spr/*.png',
				dest: 'images/spr-generated.png',
				destCss: 'sass/_sprites.sass',
				cssTemplate: 'sass/lib/sprites.sass.handlebars',
				padding: 1,
			}
		},

		// compile compass and scss
		sass: {
			dist: {
				options: {
          includePaths: [
            require('node-bourbon').includePaths,
            'node_modules/breakpoint-sass/stylesheets',
            'node_modules/scut/dist',
          ],
          sourceMap: true,
          sourceComments: true,
          outputStyle: 'compressed'
        },
				files: {
					'css/core.min.css': 'sass/core.sass'
				}
			},
			src: {
        options: {
          includePaths: [
            require('node-bourbon').includePaths,
            'node_modules/breakpoint-sass/stylesheets',
            'node_modules/scut/dist',
          ],
          sourceMap: true,
          sourceComments: true,
          outputStyle: 'expanded'
        },
				files: {
					'css/core.css': 'sass/core.sass'
				}
			}
		},

    // uglify to concat, minify, and make source maps
    uglify: {
	    options: {
		    compress: {
			    drop_console: true
		    },
		    banner: '/*! <%= pkg.name %> v<%= pkg.version %> @ <%= grunt.template.today("yyyy-mm-dd") %> */'
	    },
      dist: {
        files: {
          'js/plugins.min.js': [
            'js/plugins.js',
            'js/plugins/**/*.js',
          ],
          'js/main.min.js': [
          	'js/main.js'
          ],
          'js/selectivizr.min.js': [
          	'js/selectivizr.js'
          ]
        }
      },
     },

    // image optimization - possibly not useful or necessary
    imagemin: {
      dist: {
        options: {
          optimizationLevel: 2,
          progressive: true,
          interlaced: true
        },
        files: [{
          expand: true,
          cwd: 'images/',
          src: ['**/*.{png,jpg,gif}'],
          dest: 'images/'
        }]
      }
    },

		// jshint to keep JS okay
		jshint: {
			all: ['js/main.js']
		}

  });	//end initConfig

  // register tasks

  // load all grunt tasks matching the `grunt-*` pattern
  // this doesn't seem to work for grunt-wordpress-deploy
  require('load-grunt-tasks')(grunt);

  // this makes $ grunt  the same as $ grunt watch
  grunt.registerTask( 'default', ['watch'] );

};