var eventId;
$(function() {
    $("#setDelivery").on("click",function(){
        var matricula = $("#matricula").val();
        var delivery = $("#setDelivery").val();
        setDelivery(matricula,delivery);
    });
    
    $("#cancel").on("click",function(){
        
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
            if(response == "TRUE")
                alert("Se ha registrado la entrega");
        }
    });

}