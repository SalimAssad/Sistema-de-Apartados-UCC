var input;
var minSelection;
var maxSelection;
$(function() {
    minSelection;
    maxSelection;
    input = {
            "id": null,             //-integer: the resource's id
            "start": null,          //-string: a "yyyy-mm-dd" formatted date, the period's beginning date
            "end": null,            //-string: a "yyyy-mm-dd" formatted date, the period's ending date
            "grade": "",            //integer(opt): a number between 1 and 10 to declare the lesson's grade
            "lesson": "",           //string(opt): the lesson name
            "area": null,           //integer: the id of the area in which the resource will be used
            "lendTo": null,         //integer: the benefited user's id 
            "comments": "",         //string(opt): general comments
            "daysOfTheWeek": null,   //integer: day's number of the week where the resource will be separated (0-6)
            "from": null,           //string: a "hh:mm:ss" formatted time, the resource won't be available from this hour
            "to": null              //string: a "hh:mm:ss" formatted time, the resource won't be available until this hour
        };

    getDataFromTable("area", "AR_ID,AR_NAME", "area", false);
    getDataFromTable("usuarios", "US_ID,US_NAME", "lendTo", true);

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        defaultView: 'agendaDay',
        editable: true,
        selectable: true,
        unselectAuto: false,
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
            minSelection = start;
            maxSelection = end;
            $(".type").change();
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
        if(getDate(this) != null)
            input.start = $(this).val();
    });
    $("#endDate").on( "change", function() {
        $("#startDate").datepicker( "option", "maxDate", getDate( this ) );
        if(getDate(this) != null)
            input.end = $(this).val();
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
        if(sepType == "t") {
            //$(".datepick").val("");
            $(".temporary").fadeIn();
        } else {
            $(".temporary").fadeOut(400, function() {
                if(maxSelection != undefined &&
                    minSelection != undefined) {
                    $("#startDate").datepicker("setDate", minSelection.format().split("T")[0]).change();
                    $("#endDate").datepicker("setDate", maxSelection.format().split("T")[0]).change();
                    $("#from").val(minSelection.format().split("T")[1]).change();
                    $("#to").val(maxSelection.format().split("T")[1]).change();
                    $("input:checkbox.daysOfTheWeek").each(function() {
                        var day = minSelection._d.getDay();
                        if($(this).val() == day)
                            $(this).prop("checked", true);
                    }).change();
                } else {
                    $("#startDate").datepicker("setDate", "+0").change();
                    $("#endDate").datepicker("setDate", "+0").change();
                    $("#from").val("00:00:00").change();
                    $("#to").val("00:00:00").change();
                }
            });
        }
    }).change();

    $("#resource").on("change", function() {
        input.id = $(this).val();
    });

    $("#area").on("change", function() {
        input.area = $(this).val();
    });

    $("#lendTo").on("change", function() {
        input.lendTo = $(this).val();
    });

    $("#grade").on("change", function() {
        input.grade = $(this).val();
    });

    $("#lesson").on("change", function() {
        input.lesson = $(this).val();
    });

    $("#comments").on("change", function() {
        input.lendTo = $(this).val();
    });

    $("#from").on("change", function() {
        input.from = $(this).val();
    });

    $("#to").on("change", function() {
        input.to = $(this).val();
    });

    $("input:checkbox.daysOfTheWeek").on("change", function() {
        handleDaysOfTheWeek();
    });
});

function handleDaysOfTheWeek() {
    input.daysOfTheWeek = "";
    $("input:checkbox.daysOfTheWeek:checked").each(function() {
        if(input.daysOfTheWeek != "")
            input.daysOfTheWeek += ",";    
        input.daysOfTheWeek += $(this).val();
    });
}

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
    This function is used by the selectAllow callback
    method of the fullcalendar object to allow or deny 
    the selection of previous days */
function canSeparateOn(date) {
    var today = new Date();
    var selected = new Date(date);
    var d = date.split("-")[2];
    if(d[0] == 0)
        selected.setDate(selected.getDate() + 1);
    if(selected <= today){
        return false;
    }
    return true;
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
            $("#resource").html(response).change();
        },
        type: "POST",
        url: "../../scripts/Module 2/ajax/getAvailableResources.php"
    });
}

/* 
    Does an ajax request to separate a resource 
    Parameter: Object 
        input = {
            "id": resourceId,           //integer: the resource's id
            "start": startDate,         //string: a "yyyy-mm-dd" formatted date, the period's beginning date
            "end": endDate,             //string: a "yyyy-mm-dd" formatted date, the period's ending date
            "grade": grade,             //integer(opt): a number between 1 and 10 to declare the lesson's grade
            "lesson": lessonName,       //string(opt): the lesson name
            "area": areaId,             //integer: the id of the area in which the resource will be used
            "lendTo": userId,           //integer: the benefited user's id 
            "comments": comments,       //string(opt): general comments
            "daysOfTheWeek": weekDay,   //string: number of the days of the week (separated by comma) where the resource will be separated (0-6)
            "from": fromHour,           //string: a "hh:mm:ss" formatted time, the resource won't be available from this hour
            "to": toHour                //string: a "hh:mm:ss" formatted time, the resource won't be available until this hour
        } 
    Return: 
        event = {
            "id": separateId,
            "title": resourceAlias,
            "start": startDate,
            "end": endDate
        } */
function insertEvent(input) {
    // input must be an object!!!
    $.ajax({
        data: input,
        dataType: "json",
        error: function() {
            alert("Error al obtener la información");
        },
        success: function(response) {
            console.log(response);
        },
        type: "POST",
        url: "../../scripts/Module 2/ajax/insertEvent.php"
    });
}


/*  
    Function to get two fields from any table in the database
    Parameters:
        table       //string: the table's name
        fields      //string: two fields of the table separated by a comma
        divId       //string: the id of the "select" element in which the
                                response will render
        filter      //boolean: indicates if the server should do a filtering process
                                with the id
    Example:
        getDataFromTable("area", "AR_ID,AR_NAME", "area", false)  */
function getDataFromTable(table, fields, divId, filter) {
    $.ajax({
        data: { "table": table, "fields": fields, "filter": filter},
        dataType: "html",
        error: function() {
            alert("Error al conseguir los recursos");
        },
        success: function(response) {
            $("#"+divId).html(response).change();
        },
        type: "POST",
        url: "../../scripts/Module 2/ajax/getDataFromTable.php"
    });
}