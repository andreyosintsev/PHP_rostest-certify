$(function(){
    $('.formtip').hide();
    $("#addcert1").click(function(event){
        $('#addform2, #addform3, #addform4').hide();
        $('#addform1').show();
        $("#addcert1 img").css('border-color','rgb(47, 112, 255)');
        $("#addcert2 img, #addcert3 img, #addcert4 img").css('border-color','#aaa');
        console.log("#addcert1");
    });
    $("#addcert2").click(function(event){
        $('#addform1, #addform3, #addform4').hide();
        $('#addform2').show();
        $("#addcert2 img").css('border-color','rgb(47, 112, 255)');
        $("#addcert1 img, #addcert3 img, #addcert4 img").css('border-color','#aaa');
        console.log("#addcert2");
    });
    $("#addcert3").click(function(event){
        $('#addform1, #addform2, #addform4').hide();
        $('#addform3').show();
        $("#addcert3 img").css('border-color','rgb(47, 112, 255)');
        $("#addcert1 img, #addcert2 img, #addcert4 img").css('border-color','#aaa');
        console.log("#addcert3");
    });
    $("#addcert4").click(function(event){
        $('#addform1, #addform2, #addform3').hide();
        $('#addform4').show();
        $("#addcert4 img").css('border-color','rgb(47, 112, 255');
        $("#addcert1 img, #addcert2 img, #addcert3 img").css('border-color','#aaa');
        console.log("#addcert4");
    });
    $('.formtips').click(function(event){
        $('.formtip').toggle();
        console.log("toggle");
    });
    $('.formactual').hover(function (event){
        $(this).attr("title", "Нажмите для распознавания текста");
    });
});