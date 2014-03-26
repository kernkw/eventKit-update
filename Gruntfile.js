module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            css_vendor: {
                src: [
                    'stylesheets/lib/*.css'
                ],
                dest: 'assets/vendor/css/vendor.css'
            },
            css_test: {
                src: [
                    'stylesheets/tests/*.css'
                ],
                dest: 'tests/tests.css'
            },
            js_vendor: {
                src: [
                    'javascripts/libs/jquery-1.10.2.js',
                    'javascripts/libs/handlebars-1.1.2.js',
                    'javascripts/libs/ember-1.3.1.js',
                    'javascripts/libs/bootstrap-3.1.1.js',
                    'javascripts/libs/bootstrap-datepicker.js'
                ],
                dest: 'assets/vendor/js/vendor.js'
            },
            js_test: {
                src: [
                    'javascripts/libs/qunit-1.13.0.js',
                    'javascripts/tests/setup.js',
                    'javascripts/tests/acceptance.js',
                    'javascripts/tests/integration.js'
                ],
                dest: 'tests/tests.js'
            },
            js_app: {
                src: [
                    'javascripts/application.js',
                    'javascripts/router.js',
                    'javascripts/controllers/*.js',
                    'javascripts/views/*.js',
                    'javascripts/helpers/*.js',
                    'javascripts/routes/*.js'
                ],
                dest: 'assets/application/js/application.js'
            }
        },
        recess: {
            dist: {
                options: {
                    compile: true
                },
                files: {
                    'assets/application/css/application.css': [
                        'stylesheets/app/*.less'
                    ]
                }
            }
        },
        cssmin: {
            css_vendor: {
                src: 'assets/vendor/css/vendor.css',
                dest: 'assets/vendor/css/vendor.css'
            },
            css_application: {
                src: 'assets/application/css/application.css',
                dest: 'assets/application/css/application.css'
            }
        },
        uglify: {
            js_vendor: {
                files: {
                    'assets/vendor/js/vendor.js': ['assets/vendor/js/vendor.js']
                }
            },
            js_app: {
                files: {
                    'assets/application/js/application.js': ['assets/application/js/application.js'] 
                }
            }
        },
        emberTemplates: {
            compile: {
                options: {
                    templateBasePath: 'javascripts/views',
                    templateName: function(name) {
                        var split = name.toLowerCase().split("_"),
                            id = "";
                        split.forEach(function(word, i, array) {
                            var add = word.toLowerCase();
                            if (i) add = word.charAt(0).toUpperCase() + word.substr(1);
                            id += add;
                        });
                        return id;
                    }
                },
                files: {
                    "javascripts/views/all_views.js": "javascripts/views/*.hbs"
                }
            }
        },
        copy: {
            prod: {
                files: [{
                    expand: true,
                    src: [
                        'index.php',
                        'Installer.php',
                        'Step2Installer.php',
                        'DatabaseController.php',
                        'Logger.php',
                        'api/**/*',
                        'assets/**/*',
                        'images/**/*'
                    ],
                    dest: 'production/'
                }]
            }
        },
        open: {
            file: {
                path: 'tests/tests.html'
            }
        },
        watch: {
            files: ['css/*', 'js/*'],
            tasks: ['concat', 'cssmin', 'uglify']
        }
    });
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-ember-templates');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-recess');
    grunt.loadNpmTasks('grunt-open');
    grunt.registerTask('default', ['concat:css_vendor', 'recess:dist', 'concat:js_vendor', 'emberTemplates:compile', 'concat:js_app']);
    grunt.registerTask('test', ['concat:css_vendor', 'concat:css_test', 'recess:dist', 'concat:js_vendor', 'emberTemplates:compile', 'concat:js_app', 'concat:js_test', 'open:file']);
    grunt.registerTask('prod', ['concat:css_vendor', 'recess:dist', 'cssmin:css_vendor', 'emberTemplates:compile', 'concat:js_app', 'concat:js_vendor', 'uglify:js_vendor', 'uglify:js_app', 'copy:prod']);
};