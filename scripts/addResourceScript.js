
/* ----------------------------------------------------------------------

    SE ENCARGA DE OCULTAR LOS CAMPOOS DE EQUIPO CUANDO ES UN AULA

---------------------------------------------------------------------- */

function typeHandler(type){
    if(type == "EQUIPO"){
        $(".equipment").prop("disabled", false).css("display", "");
    }else{
        $(".equipment").prop("disabled", true).css("display", "none");
    }
}

/* ----------------------------------------------------------------------

     SE ENCARGA DE LLENAR Y DESHABILITAR LOS CAMPOS DE UBICACIÓN
     EN CASO DE QUE SE SELECCIONE

---------------------------------------------------------------------- */

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

/* ----------------------------------------------------------------------

     SE ENCARGA DE AÑADIR LAS REFERENCIAS

---------------------------------------------------------------------- */

function addReference(){
    var val = $("#reference").val();
    if(val != "") {
        var auxArray = val.split("-");
        var id = auxArray[0];
        var description = auxArray[1];
        if($("#reference-container").find("#"+id).length == 0)
            $("#reference-container").html($("#reference-container").html() + "<div id='" + id + "' class='top-margin'><div class='col-sm-8'><input type='text' class='form-control' value='" + description + "' readonly><input type='hidden' name='references[]' value='" + id + "'></div><div class='col-sm-4 valign'><button type='button' class='btn-danger form-control' value='" + id + "' onclick='removeReference(this.value)'>Remover</button></div></div>");
    }
}

/* ----------------------------------------------------------------------

     SE ENCARGA DE REMOVER LA REVERENCIA QUE RECIBE

---------------------------------------------------------------------- */

function removeReference(id){
    $("#"+id).remove();
}