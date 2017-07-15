//Gruntfile
module.exports = function(grunt) {

  //Initializing the configuration object
  grunt.initConfig({
    vendorsPath : './vendor/assets',
    assetsPath : './resources/assets',
    publicAssets : './public/assets',
    fontsPath : './public/assets/fonts',
    pkg: grunt.file.readJSON('package.json'),

    jshint: {
      files: ['Gruntfile.js', '<%= publicAssets %>/js/main.js'],
      options: {
        // options here to override JSHint defaults
        globals: {
          jQuery: true,
          console: true,
          module: true,
          document: true
        }
      }
    },

    autoprefixer: {
      dist: {
        options: {
          browsers: ['last 2 version', 'ie 9', 'Firefox > 20', 'Safari > 5'],
          flatten: true
        },
        files: {
          '<%= publicAssets %>/css/<%= pkg.name %>-<%= pkg.version %>.css': '<%= publicAssets %>/css/<%= pkg.name %>-<%= pkg.version %>.css'
        }
      }
    },

    copy: {
      fontawesome: {
        files: [
          {
            expand: true,
            cwd: '<%= vendorsPath %>/font-awesome/fonts/',
            src: '**',
            dest: '<%= fontsPath %>/'
          }
        ]
      },
      mediaresa: {
        files: [
          {
            expand: true,
            cwd: '<%= assetsPath %>/fonts/',
            src: '**',
            dest: '<%= fontsPath %>/'
          }
        ]
      },
    },

    sass: {
      options: {
        sourceMap: false
      },
      dist: {
        files: {
          '<%= publicAssets %>/css/<%= pkg.name %>-<%= pkg.version %>.css': '<%= assetsPath %>/scss/main.scss',
          '<%= publicAssets %>/css/styleguide.css': '<%= assetsPath %>/scss/styleguide.scss',
          '<%= publicAssets %>/css/regie-layout.css': '<%= assetsPath %>/scss/regie-layout.scss'
        }
      }
    },

    csso: {
      compress: {
        options: {
          report: 'min'
        },
        files: {
          '<%= publicAssets %>/css/regie-layout.min.css' : '<%= publicAssets %>/css/regie-layout.css',
          '<%= publicAssets %>/css/admin.min.css': '<%= publicAssets %>/css/admin.css',
          '<%= publicAssets %>/css/styleguide.min.css': '<%= publicAssets %>/css/styleguide.css',
          '<%= publicAssets %>/css/<%= pkg.name %>-<%= pkg.version %>.min.css': '<%= publicAssets %>/css/<%= pkg.name %>-<%= pkg.version %>.css'
        }
      }
    },

    csscount: {
      dev: {
        src: [
          '<%= publicAssets %>/admin.css'
        ],
        options: {
          maxSelectors: 4095,
          maxSelectorDepth: false
        }
      }
    },

    concat: {
      options: {
        separator: ';\n', // Avoid syntax error on Smart-Table concat
      },
      main: {
        src: ['<%= vendorsPath %>/jquery/dist/jquery.js',
          '<%= vendorsPath %>/bootstrap-sass/assets/javascripts/bootstrap.js',
          '<%= vendorsPath %>/fancyselect/fancySelect.js',
          '<%= vendorsPath %>/formajax/src/jquery.formajax.js',
          '<%= vendorsPath %>/select2/dist/js/select2.full.min.js',
          '<%= vendorsPath %>/jquery-lazyload/jquery.lazyload.js',
          '<%= assetsPath %>/js/jquery-btn-ajax.js',
          '<%= assetsPath %>/js/main.js',
          '<%= vendorsPath %>/angular/angular.js',
          '<%= vendorsPath %>/angular-messages/angular-messages.js',
          '<%= vendorsPath %>/angular-bootstrap/ui-bootstrap.js',
          '<%= vendorsPath %>/angular-bootstrap/ui-bootstrap-tpls.js',
          '<%= vendorsPath %>/angular-filter/dist/angular-filter.js',
          '<%= vendorsPath %>/angular-multi-step-form/dist/browser/angular-multi-step-form.js',
          '<%= vendorsPath %>/angular-stripe/release/angular-stripe.js',
          '<%= vendorsPath %>/angular-promise-buttons/dist/angular-promise-buttons.js',
          '<%= vendorsPath %>/angular-i18n/angular-locale_fr-fr.js',
          '<%= assetsPath %>/js/app/app.js',
          '<%= assetsPath %>/js/app/api.js',
          '<%= assetsPath %>/js/app/controllers/**.js',
          '<%= assetsPath %>/js/app/directives/**.js'
          ],
        dest: '<%= publicAssets %>/js/<%= pkg.name %>-<%= pkg.version %>.js'
      },
      admin: {
        src: ['<%= vendorsPath %>/jquery/dist/jquery.js',
          '<%= vendorsPath %>/angular/angular.js',
          '<%= vendorsPath %>/angular-resource/angular-resource.js',
          '<%= vendorsPath %>/bootstrap/dist/js/bootstrap.min.js',
          '<%= vendorsPath %>/angular-smart-table/dist/smart-table.js',
          '<%= vendorsPath %>/blueimp-file-upload/js/vendor/jquery.ui.widget.js',
          '<%= vendorsPath %>/blueimp-file-upload/js/jquery.iframe-transport.js',
          '<%= vendorsPath %>/blueimp-file-upload/js/jquery.fileupload.js',
          '<%= vendorsPath %>/formajax/src/jquery.formajax.js',
          '<%= vendorsPath %>/select2/dist/js/select2.full.min.js',
          '<%= vendorsPath %>/select2/dist/js/i18n/fr.js',
          '<%= vendorsPath %>/datetimepicker/jquery.datetimepicker.js',
          '<%= assetsPath %>/js/mediaresa.js',
          '<%= assetsPath %>/js/components/*.js',
          '<%= assetsPath %>/js/angular_admin.js',
          '<%= assetsPath %>/js/admin.js'],
        dest: '<%= publicAssets %>/js/<%= pkg.name %>-<%= pkg.version %>-admin.js',
      },
      ieSupport: {
        src: ['<%= vendorsPath %>/html5shiv/dist/html5shiv.js',
          'vendor/respond/dest/respond.js'],
        dest: '<%= publicAssets %>/js/<%= pkg.name %>-<%= pkg.version %>-ie-support.js'
      },
      styleguide: {
        src: ['<%= vendorsPath %>/prism/prism.js'],
        dest: '<%= publicAssets %>/js/styleguide.js'
      }
    },

    uglify: {
      options: {
        mangle: false
      },
      js: {
        files: {
          '<%= publicAssets %>/js/<%= pkg.name %>-<%= pkg.version %>.min.js' : [ '<%= publicAssets %>/js/<%= pkg.name %>-<%= pkg.version %>.js' ],
          '<%= publicAssets %>/js/<%= pkg.name %>-<%= pkg.version %>-admin.min.js' : [ '<%= publicAssets %>/js/<%= pkg.name %>-<%= pkg.version %>-admin.js' ],
          '<%= publicAssets %>/js/<%= pkg.name %>-<%= pkg.version %>-ie-support.min.js' : ['<%= publicAssets %>/js/<%= pkg.name %>-<%= pkg.version %>-ie-support.js']        }
      }
    },

    imagemin: {
      dynamic: {
        files: [{
          expand: true,
          cwd: '<%= assetsPath %>/im/',
          src: ['**/*.{png,jpg,gif}'],
          dest: '<%= publicAssets %>/im/'
        }]
      }
    },

    less: {
      dev: {
        files: {
          '<%= publicAssets %>/css/admin.css' : '<%= assetsPath %>/less/admin.less'
        }
      },
      prod: {
        options: {
          compress: true,
          yuicompress: true,
          optimization: 2
        },
        files: {
          '<%= publicAssets %>/css/admin.css' : '<%= assetsPath %>/less/admin.less'
        }
      }
    },

    delta: {
      options: {
        livereload: true,
      },
      gruntfile: {
        files: 'Gruntfile.js',
        tasks: ['less:dev', 'copy', 'jshint', 'concat', 'uglify'],
        options: {
          livereload: false
        }
      },
      less: {
        files: [
          '<%= assetsPath %>/less/**/*.less',
        ],
        tasks: ['less:dev', 'csscount', 'csso', 'autoprefixer']
      },
      sass: {
        files: [
          '<%= assetsPath %>/scss/**/*.scss',
        ],
        tasks: ['sass', 'csscount', 'csso', 'autoprefixer']
      },
      js: {
        files: [
          '<%= assetsPath %>/js/**/*.js',
        ],
        tasks: ['jshint', 'concat', 'uglify']
      },
      emails: {
        files: ['<%= emailsPath %>/*.twig'],
        tasks: ['inlinecss']
      },
      twig: {
        files: ['<%= assetsPath %>/views/*.twig', '<%= assetsPath %>/views/*/*.twig', '<%= assetsPath %>/views/*/*/*.twig'],
        tasks: ['sass', 'autoprefixer', 'csso']
      },
    },

    criticalcss: {
      home: {
        options:  {
          outputfile : '<%= publicAssets %>/css/critical/critical-home.css',
          filename : '<%= publicAssets %>/css/<%= pkg.name %>-<%= pkg.version %>.css',
          url : 'http://localhost:9001',
          width: 1200,
          height: 900
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-autoprefixer');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-css-count');
  grunt.loadNpmTasks('grunt-csso');
  grunt.loadNpmTasks('grunt-criticalcss');
  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-imagemin');

  // Task definition
  grunt.registerTask('prod', [
    'jshint',
    'less:prod' ,
    'sass',
    'autoprefixer',
    'concat',
    'csso',
    'uglify',
    'copy',
    'imagemin'
  ]);

  grunt.registerTask('images', 'imagemin');

  grunt.renameTask( 'watch', 'delta' );

  grunt.registerTask('watch', [
    'copy',
    'jshint',
    'concat',
    'delta',
    'csscount'
  ]);

  grunt.registerTask('default', [
      'less:dev',
      'sass',
      'copy',
      'jshint',
      'concat',
      'csscount'
  ]);
};
