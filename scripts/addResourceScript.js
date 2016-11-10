
function typeHandler(type){
    if(type == "EQUIPO"){
        $(".equipment").prop("disabled", false).css("display", "");
    }else{
        $(".equipment").prop("disabled", true).css("display", "none");
    }
}

function locationHandler(data){
    if(data == "new"){
        $("#campus").val("").prop("readonly", false);
        $("#pile").val("").prop("readonly", false);
        $("#floor").val("").prop("readonly", false);
        $("#room").val("").prop("readonly", false);
    }else {
        var d = data.replace(/:,/g, "");
        alert(d);
        var arr = d.split(" ");
        alert(data[0]);
        $("#campus").val(arr[0]).prop("readonly", true);
        $("#pile").val(arr[1]).prop("readonly", true);
        $("#floor").val(arr[2]).prop("readonly", true);
        $("#room").val(arr[3]).prop("readonly", true);
    }
}