const gulp = require("gulp")
const livereload = require("gulp-livereload")
const rename = require("gulp-rename") // to rename files
const postcss = require("gulp-postcss")
const autoprefixer = require("autoprefixer") // Autoprefixer
const precss = require("precss") // Sass-like features (imports, vars...)
const googleFonts = require("postcss-google-font") // Google Fonts import
const colorFunction = require("postcss-color-function") // color related functions
const cssnano = require("cssnano") // CSS Minifier
const uglify = require("gulp-uglify") // JS minifier
const concat = require("gulp-concat") // JS concat files
const imagemin = require("gulp-imagemin") // Optimize images
const pump = require("pump")

/* 
    - CSS
*/
gulp.task("css", () => {
  const plugins = [
    googleFonts(),
    precss(),
    colorFunction(),
    autoprefixer({ browsers: ["last 1 version"] }),
    cssnano() ]
  return gulp.src("src/css/main.css")
    .pipe(postcss(plugins))
    .pipe(rename("stylesheet.min.css"))
    .pipe(gulp.dest("dist/assets/stylesheets"))
})

/*
  - JS
*/

gulp.task('js', (cb) => {
  pump([
        gulp.src("src/js/**/*.js"),
        uglify(),
        concat("global.min.js"),
        gulp.dest('dist/assets/scripts')
    ],
    cb
  )
})

/*
  - Images
*/
gulp.task("img", () =>
    gulp.src("dist/assets/img/**/*")
        .pipe(imagemin())
        .pipe(gulp.dest("dist/assets/img/**/*"))
);

/*
    - Watch & livereload
 */
gulp.task("watch", () => {
  livereload.listen({ start: true })
  gulp.watch("views/**/*.twig", livereload.reload) // for Twig files
  gulp.watch("src/css/**/*.css", ["css", livereload.reload]) // for CSS files
  gulp.watch("src/js/*.js", ["js", livereload.reload]) // for CSS files

  console.log("Livereload is alive \o/ Don't forget to turn it on in your browser!")
})
