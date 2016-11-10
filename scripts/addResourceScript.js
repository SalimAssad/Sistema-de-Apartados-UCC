
function typeHandler(type){
    if(type == "EQUIPO"){
        $(".equipment").prop("disabled", false).css("display", "");
    }else{
        $(".equipment").prop("disabled", true).css("display", "none");
    }
}

function locationHandler(data){
    if(data == "Nuevo"){
        $("#campus").val("").prop("disabled", false);
        $("#hidden-campus").val("").prop("disabled", true);
        $("#pile").val("").prop("readonly", false);
        $("#floor").val("").prop("readonly", false);
        $("#room").val("").prop("readonly", false);
    }else {
        var d = data.replace(/[:,]/g, "");
        var arr = d.split(" ");
        $("#campus").val(arr[0]).prop("disabled", true);
        $("#hidden-campus").val("").prop("disabled", false);
        $("#pile").val(arr[1]).prop("readonly", true);
        $("#floor").val(arr[2]).prop("readonly", true);
        $("#room").val(arr[3]).prop("readonly", true);
    }
}