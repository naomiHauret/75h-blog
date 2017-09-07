var fileImage = document.querySelector('.input-preview__src');
var filePreview = document.querySelector('.input-preview');

fileImage.onchange = function () {
    var reader = new FileReader();

    reader.onload = function (e) {
        // get loaded data and render thumbnail.
        filePreview.style.backgroundImage  = "url("+e.target.result+")";
        filePreview.classList.add("has-image");
    };

    // read the image file as a data URL.
    reader.readAsDataURL(this.files[0]);
};