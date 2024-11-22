function ocr(id_img, id_output) {
    img = document.getElementById(id_img).src;

    if (img!="") {
        Tesseract.recognize(img).then(function(result) {
            lang: "rus" // Язык текста
        }).then(function(result) {
            document.getElementById(id_output).value  = result.text;
        });
    }
}