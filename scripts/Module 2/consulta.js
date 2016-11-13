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
        editable: true,
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
        selectAllow: function(selectInfo) {
            var startDate = selectInfo.start.format().split("T")[0];
            return canSeparateOn(startDate);
        },
        events: function(start, end, timezone, callback) {


            $.ajax({
                url: 'consultaQuery.php',
                dataType: 'json',
                method: 'POST',
                data: {

                    start: start.format(),
                    end: end.format()
                },

                success: function(json) {
 
                    var events = [];
                 
                    json.forEach(function(obj,i){
                       
                        events.push({
                            title: obj.title,
                            start: obj.start,
                           // end: obj.AP_END,
                            id: obj.id,
                            //resid: obj.AP_RESID
                        });
                    });
                    console.log(events);
                    callback(events);

                }
            });
        }

    });


});
/*
 console.log(start.format());
                    console.log(end.format());
*/