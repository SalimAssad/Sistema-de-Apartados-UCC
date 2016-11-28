$(function() {
    var minSelection;
    var maxSelection;

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'agendaWeek,agendaDay'
        },
        defaultView: 'agendaDay',
        editable: false,
        selectable: true,
        eventOverlap: false,
        minTime: "07:00:00",
        maxTime: "22:00:00",
        select: function(start, end, jsEvent, view) {
            $('#calendar').fadeOut(400, function() {
                $('#calendar').addClass("col-sm-8 col-md-9");
                $(".separate").fadeIn(400, function() {
                    $('#calendar').fadeIn();
                });
            });
            //minSelection = start.format();
            //maxSelection = end.format();
            minSelection = start;
            maxSelection = end;
            //$('#calendar').fullCalendar('unselect');
        },
        eventClick: function (calEvent, jsEvent, view) {  
            $.ajax({
                url: '../../scripts/separate/ajax/detailOf.php',
                dataType: 'json',
                method: 'POST',
                data: { id: calEvent.id },

                success: function(response) {

                    var event = response[0];
                    $("#startTime").html(event.start);
                    $("#endTime").html(event.end);
                    $("#from").html(even.from);////
                    $("#to").html(even.to);////
                    $("#name").html(event.name);
                    $("#lesson").html(event.lesson);
                    $("#diasApartado").html(event.days);/////
                    $("#area").html(event.area);
                    $("#comments").html(event.comments);
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
