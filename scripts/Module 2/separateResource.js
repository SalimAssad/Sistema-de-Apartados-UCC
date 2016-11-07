$(function() {
    var minSelection;
    var maxSelection;

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
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
        }
    });

    var options = {
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true
    };
    $(".datepick").datepicker(options).on("change", function() {
        if(getDate(this) == null)
            $(this).addClass("error");
        else 
            $(this).removeClass("error");
    });
    $("#startDate").on( "change", function() {
        $("#endDate").datepicker( "option", "minDate", getDate( this ) );
    });
    $("#endDate").on( "change", function() {
        $("#startDate").datepicker( "option", "maxDate", getDate( this ) );
    });

    $(".resourceType").on("change", function() {
        var resType = $(".resourceType:checked").data("type");
        if(resType == "d")
            getAvailableResources("EQUIPO");
        else
            getAvailableResources("AULA");
    }).change();

    $(".type").on("change", function() {
        var sepType = $(".type:checked").data("type");
        if(sepType == "t")
            $(".temporary").fadeIn();
        else
            $(".temporary").fadeOut();
    }).change();

    $("input, select").on("focus", function() {
        $('#calendar').fullCalendar('select', minSelection, maxSelection);
    });
});

/* 
    Parses the selected date of a datepicker
    if the date is invalid, it returns null
    otherwise, returns a date with "yy-mm-dd" format */
function getDate(element) {
    var date;
    try {
        date = $.datepicker.parseDate("yy-mm-dd", element.value);
    } catch (e) {
        date = null;
    }
    return date;
}

/*
    Does an ajax to get the available resources
    from the server.
    if resourceType is given, it will return the
    type's available resources, otherwise 
    it will return all the resources in the DB. */
function getAvailableResources(resourceType) {
    $.ajax({
        data: { "resourceType": resourceType},
        dataType: "html",
        error: function() {
            alert("Error al conseguir los recursos");
        },
        success: function(response) {
            $("#resource").html(response);
        },
        type: "POST",
        url: "../../scripts/Module 2/ajax/getAvailableResources.php"
    });
}

/*
    This function is used by the selectAllow callback
    method of the fullcalendar object to allow or deny 
    the selection of previous days */
function canSeparateOn(date) {
    var today = new Date();
    var selected = new Date(date);
    var d = selected.getDate();
    if(d[0] == 0)
        selected.setDate(selected.getDate() + 1);
    if(selected <= today)
        return false;
    return true;
}