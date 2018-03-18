$(document).ready(function(){
    var s = $("#phoneNumber").html();
    var s2 = (""+s).replace(/\D/g, '');
    var m = s2.match(/^(\d{3})(\d{3})(\d{4})$/);
    $("#phoneNumber").text((!m) ? null : "(" + m[1] + ") " + m[2] + "-" + m[3]);
});