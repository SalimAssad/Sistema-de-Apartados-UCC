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


            /*usuario-, */
            $.ajax({
                url: '../../scripts/Module 2/ajax/detailOff.php',
                dataType: 'json',
                method: 'POST',
                data: {
                    id: calEvent.id
                   
                   

                },

                success: function(response) {

                    $("#startTime").html(response.start.format());
                    $("#endTime").html(response.end.format());
                    $("#name").html(response.name);
                    $("#lesson").html(response.lesson);
                    $("#area").html(response.area);
                    $("#resourceName").html(response.resourceName);
                    $("#eventContent").attr("title", response.title);
                    $("#eventContent").dialog({ modal: true, title: event.title, width:350});    

                    var events = [];

                    json.forEach(function(obj,i){

                        events.push({
                            title: obj.title,
                            start: obj.start,
                            end: obj.end,
                            id: obj.id,

                        });
                    });

                    console.log(events);
                    callback(events);

                }
            });

        },

        events: function(start, end, timezone, callback) {



            $.ajax({
                url: '../../scripts/Module 2/ajax/getAllEvents.php',
                dataType: 'json',
                method: 'POST',
                data: {

                    start: start.format(),
                    end: end.format(),


                },

                success: function(json) {

                    var events = [];

                    json.forEach(function(obj,i){

                        events.push({
                            title: obj.title,
                            start: obj.start,
                            end: obj.end,
                            id: obj.id,

                        });
                    });

                    console.log(events);
                    callback(events);

                }
            });
        }


    });


});
