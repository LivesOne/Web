module.exports = function(grunt) {
    'use strict';
    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        path: {
            publicDir: 'Public/static/assets',
            buildDir: 'build_new'
        },
        pkg: grunt.file.readJSON('package.json'),
        clean: {
            options: {
                force: true
            },
            build: ['<%= path.buildDir %>'],
            release: ['<%= path.buildDir %>','./js','./css']
        },
        cssmin: {
            mobile: {
                files: [{
                    expand: true,
                    cwd: '<%= path.publicDir %>/css/',
                    src: ['**/*.css'],
                    dest: '<%= path.buildDir %>/css/'
                }]
            }
        },
        uglify: {
            mobile: {
                files: [{
                    expand: true,
                    cwd: '<%= path.publicDir %>/js',
                    src: ['**/*.js'],
                    dest: '<%= path.buildDir %>/js'
                }]
            }
        },
        copy: {
            css: {
                files: [{
                    expand: true,
                    cwd: '<%= path.buildDir %>/css/',
                    src: ['**/*.css'],
                    dest: '<%= path.publicDir %>/cssmin'
                }]
            },
            js: {
                files: [{
                    expand: true,
                    cwd: '<%= path.buildDir %>/js/',
                    src: ['**/*.js'],
                    dest: '<%= path.publicDir %>/jsmin'
                }]
            }
        }
    });

    grunt.registerTask('copyStatic', [
        'copy:css',
        'copy:js'
    ]);

    grunt.registerTask('main', [
        'cssmin',
        'uglify',
        'copyStatic'
    ]);

    grunt.registerTask('default', [
        'clean',
        'main'
    ]);
};
