var gulp            = require('gulp');
var ngAnnotate 		= require('gulp-ng-annotate');
var props           = require('gulp-props');
var gutil           = require('gulp-util');
var rename          = require('gulp-rename');
var concat          = require('gulp-concat');
var cssmin          = require('gulp-cssmin');
var jshint          = require('gulp-jshint');
var uglify          = require('gulp-uglify');
var watch           = require('gulp-watch');
var duration        = require('gulp-duration');
var sourcemaps      = require('gulp-sourcemaps');
var autoprefixer    = require('gulp-autoprefixer');
var sass            = require('gulp-sass');
var htmlmin         = require('gulp-htmlmin');
var fileSync        = require('gulp-file-sync');
var minimist        = require('minimist');
var browserSync     = require("browser-sync").create();
var proxy           = require('proxy-middleware');
var url             = require('url');
var RevAll          = require('gulp-rev-all');
var imagemin        = require('gulp-imagemin');
var gulpIgnore      = require('gulp-ignore');

var webappRootDist = './dist';
var webappRootSrc = './src';

var projectPaths = {
    sass: webappRootSrc + '/sass/*.scss',
    html: webappRootSrc + '/**/*.html',
    js: webappRootSrc + '/js/**/*.js'
};

var fileSyncOptions = {
    recursive: true,
    addFileCallback: function(_fullPathSrc, _fullPathDest){gutil.log('Added: ' + _fullPathDest);},
    deleteFileCallback: function(_fullPathSrc, _fullPathDest){gutil.log('Deleted: ' + _fullPathDest);},
    updateFileCallback: function(_fullPathSrc, _fullPathDest){gutil.log('Updated: ' + _fullPathDest);}
};

// Command-Line Arguments
var cliArgs = minimist(process.argv.slice(2), {
    string: 'serverPath',
    default: {serverPath: 'src'}
});

gulp.task('sass', function(){
    return gulp.src(projectPaths.sass)
        .pipe(sass({outputStyle: 'compact'}))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(webappRootSrc + '/generated-css'))
        .pipe(browserSync.stream());
});

// Duplicate server tasks removed, now its possible to run server in dist and
// src with CLI argument
// gulp connect --serverPath dist
// Note: by default src folder.
gulp.task('connect', ['sass'], function(){
    var serverSourcePath = webappRootSrc;
    if(cliArgs.serverPath == 'dist'){
        serverSourcePath = webappRootDist;
    }

    var apiUrl = url.parse('https://api.net');
    apiUrl.route = '/api';

    browserSync.init({
        server: {
            baseDir: serverSourcePath,
            middleware: [proxy(apiUrl), function (req, res, next){
                res.setHeader('Access-Control-Allow-Origin', '*');
                res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
                next();
            }]
        }
    });

    gulp.watch(projectPaths.sass, ['sass']);
    gulp.watch(projectPaths.html).on('change', browserSync.reload);
    gulp.watch(projectPaths.js).on('change', browserSync.reload);
});

// !! -- Build only tasks -- !!
gulp.task('sass:build', function(){
    return gulp.src(projectPaths.sass)
        .pipe(sass({outputStyle: 'compact'}))
        .pipe(autoprefixer())
        .pipe(cssmin())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(webappRootSrc + '/generated-css'))
});

gulp.task('sync:build', ['revision'], function(){
    // no need to revision external libraries so, sync folder after revision
    fileSync(webappRootSrc + '/bower_components', webappRootDist + '/bower_components', fileSyncOptions)
});


gulp.task('minify-html', ['revision'], function(){
    return gulp.src([webappRootSrc + '/**/*.html'])
         .pipe(gulpIgnore.exclude('!' + webappRootSrc + '/bower_components/**'))
        .pipe(htmlmin({collapseWhitespace: true}))
        .pipe(gulp.dest(webappRootDist))
});

gulp.task('minify-img', ['revision'], function(){
    return gulp.src(webappRootDist + '/img/**')
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}]
        }))
        .pipe(gulp.dest(webappRootDist + '/img/'));
});

gulp.task('uglify-js', ['revision'], function(){
    return gulp.src(webappRootDist + '/js/**/*.js')
        .pipe(ngAnnotate()) // auto generate a series of alias string of
                            // providers,
                        // to prevent unknown provider when minified
        .pipe(uglify({mangle: true}).on('error', gutil.log)) // mangle: false not change variable
                                        // name
        .pipe(gulp.dest(webappRootDist + '/js'))
});

gulp.task('revision', ['sass:build'], function(){
    var revAll = new RevAll({debug: true, dontRenameFile: [/^\/favicon.ico$/g, 'index.html']});
    return gulp.src([
            webappRootSrc + '/**',
            '!' + webappRootSrc + '/bower_components/**',
            '!' + webappRootSrc + '/sass/**'
        ])
        .pipe(revAll.revision())
        .pipe(gulp.dest(webappRootDist))
        // create manifest file for revision files
        .pipe(revAll.manifestFile())
        .pipe(gulp.dest(webappRootDist))
        .pipe(revAll.versionFile())
        .pipe(gulp.dest(webappRootDist));
});

gulp.task('webversion', function () {
	  gulp.src('global.properties')
	  .pipe(props())
	  .pipe(gulp.dest('src/generated-js'))
});

gulp.task('default', ['webversion', 'sass', 'connect']);
gulp.task('build', ['webversion', 'sync:build', 'minify-html', 'uglify-js', 'minify-img']);
