var eventId;
$(function() {

    $("#setDelivery").on("click",function(){
        var matricula = $("#matricula").val();
        var delivery = $("#setDelivery").val();
        setDelivery(matricula,delivery);
    });

    $("#cancel").on("click",function(){
        showCancel();

    });


    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'agendaWeek,agendaDay'
        },
        defaultView: 'agendaDay',
        editable: false,
        selectable: false,
        eventOverlap: false,
        minTime: "07:00:00",
        maxTime: "22:00:00",
        eventClick: function (calEvent, jsEvent, view) {  
            eventId = calEvent.id;
            $.ajax({
                url: '../../scripts/separate/ajax/detailOf.php',
                dataType: 'json',
                method: 'POST',
                data: { id: calEvent.id },

                success: function(response) {

                    var event = response[0];
                    $("#startTime").html(event.start);
                    $("#endTime").html(event.end);
                    $("#from").html(event.from);////
                    $("#to").html(event.to);////
                    $("#name").html(event.name);
                    $("#lesson").html(event.lesson);
                    $("#diasApartado").html(event.days);/////
                    $("#area").html(event.area);
                    $("#comments").html(event.comments);
                    if(event.inuse == "1"){
                        $("#setDelivery").text("Recibir");
                    } else{
                        $("#setDelivery").text("Entregar");
                    }
                    $("#setDelivery").val(event.inuse);
                    $("#eventContent").attr("title", event.title);
                    $("#eventContent").dialog({ modal: true, title: event.title, width:350});  

                }
            });

        },
        events: function(start, end, timezone, callback) {
            $.ajax({
                url: '../../scripts/separate/ajax/getAllEvents.php',
                dataType: 'json',
                method: 'POST',
                data: {
                    start: start.format(),
                    end: end.format(),
                },
                success: function(json) {
                    callback(json);
                }
            });
        }
    });
});

function setDelivery(matricula,inuse){
    $.ajax({
        url:'../../scripts/separate/ajax/setDeliveryStatus.php',
        dataType: 'text',
        method: 'POST',
        data:{ id: eventId, matricula: matricula, inuse: inuse },
        success: function(response){
            if(response == "TRUE"){
                alert("Se ha registrado la entrega");
                $("#eventContent").dialog("close");  
                $("#matricula").val("");   
            }

        }
    });

}

function showCancel(){
     $("#eventContent").dialog("close");
    var popup = "<div id='confirmation' class='popup'>"+
        "<h4>¿Desea cancelar apartado?</h4>"+
        "<div id='confirm-table' class='col-md-12 col-sm-12'>"+
        "<table class='table table-responsive'>"+
        "<thead>"+
        "</thead>"+
        "<tbody>"+
        "<tr>"+
        "<th colspan='2'>Código de autenticación </th><td colspan='2' id='resource-confirm'><input type='text' id='valCodigo' name='valCodigo'></td>"+
        "</tr>"+
        "<tr>"+
        "<th colspan='2'>Motivo </th><td colspan='2' id='resource-confirm'><textarea rows='5' cols='40'id='motivo' name='motivo' placeholder='Describir motivo de la cancelacion.'></textarea></td>"+
        "</tr>"+
        "</tbody>"+
        "</table>"+
        "<button type='button' name='cancelar' id='cancelDelivery' class='btn btn-primary col-md-5 col-sm-5 col-xs-5'>Cancelar apartado</button>"+
        "<button type='button' name='cerrar' id='cerrar' class='btn btn-danger col-md-5 col-sm-5 col-xs-5 col-xs-offset-2 col-sm-offset-2 col-md-offset-2'>Cerrar</button>"
    "</div>";
    var block = "<div id='block'><div>";
    event.preventDefault();
    $("body").append(block).append(popup);
    $("#cerrar").on("click",function(event){
        
        $("#confirmation, #block").fadeOut(400,function(){
            $("#confirmation, #block").remove();
        });

    });

    $("#cancelDelivery").on("click",function(event){
        var valCodigo = $("#valCodigo").val();
        var motivo = $("#motivo").val();
         alert("Se ha cancelado la entrega");
          $("#confirmation, #block").fadeOut(400,function(){
            $("#confirmation, #block").remove();
        });
        
        $.ajax({
            url:'../../scripts/separate/ajax/cancelSeparate.php',
            dataType: 'text',
            method: 'POST',
            data:{ id: eventId, valCodigo, motivo },
            succes:function(response){
            if(response == "TRUE"){
                alert("Se ha cancelado la entrega");  
            }

        }
        });



    });






};