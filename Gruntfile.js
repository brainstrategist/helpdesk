module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        sass: {                              // Task
            dist: {                            // Target
                options: {                       // Target options
                    style: 'expanded'
                },
                files: {                         // Dictionary of files
                    'src/BrainStrategist/KernelBundle/Resources/public/css/*.css': 'src/BrainStrategist/KernelBundle/Resources/public/scss/*.scss'      // 'destination': 'source'
                }
            }
        },
        bowercopy: {
            options: {
                srcPrefix: 'bower_components',
                destPrefix: 'web/assets'
            },
            scripts: {
                files: {
                    'js/jquery.js': 'jquery/dist/jquery.js',
                    'js/bootstrap.js': 'bootstrap/dist/js/bootstrap.js'
                }
            },
            stylesheets: {
                files: {
                    'css/bootstrap.css': 'bootstrap/dist/css/bootstrap.css',
                    'css/font-awesome.css': 'font-awesome/css/font-awesome.css'
                }
            },
            fonts: {
                files: {
                    'fonts': 'font-awesome/fonts'
                }
            }
        },
        uglify : {
            js: {
                files: {
                    'web/assets/js/jquery.min.js': ['web/assets/js/jquery.js'],
                    'web/assets/js/bootstrap.min.js': ['web/assets/js/bootstrap.js'],
                    'web/assets/js/brain_strategist.min.js': ['src/BrainStrategist/KernelBundle/Resources/public/js/*.js']
                }
            }
        },
        concat: {
            options: {
                stripBanners: true
            },
            css: {
                src: [
                    'web/assets/css/bootstrap.min.css',
                    'web/assets/css/font-awesome.min.css',
                    'src/BrainStrategist/KernelBundle/Resources/public/css/*.css'
                ],
                dest: 'web/assets/css/brain_strategist_compiled.css'
            },
            js : {
                src : [
                    'web/assets/js/jquery.min.js',
                    'web/assets/js/bootstrap.min.js',
                    'web/assets/js/brain_strategist.min.js'
                ],
                dest: 'web/assets/js/brain_strategist_compiled.js'
            }
        },
        cssmin : {
            brain_strategist:{
                src: 'web/assets/css/brain_strategist_compiled.css',
                dest: 'web/assets/css/brain_strategist_compiled.min.css'
            }
        },
        copy: {
            images: {
                expand: true,
                cwd: 'src/BrainStrategist/KernelBundle/Resources/public/images',
                src: '*',
                dest: 'web/assets/images/'
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-bowercopy');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['bowercopy','copy', 'sass','concat', 'cssmin', 'uglify']);
};