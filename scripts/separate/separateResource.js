var actualEvents;
var response = null;
var input;
var dayNamesShort = ['Dom','Lun','Mar','Mie','Jue','Vie','Sáb'];
var monthNamesShort = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
var dayNames = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
var monthNames = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$(function() {
    var minSelection;
    var maxSelection;
    actualEvents = [];
    input = {
            "resource": null,       //integer: the resource's id
            "start": null,          //string: a "yyyy-mm-dd" formatted date, the period's beginning date
            "end": null,            //string: a "yyyy-mm-dd" formatted date, the period's ending date
            "grade": "",            //integer(opt): a number between 1 and 10 to declare the lesson's grade
            "lesson": "",           //string(opt): the lesson name
            "area": null,           //integer: the id of the area in which the resource will be used
            "lendTo": null,         //integer: the benefited user's id 
            "comments": "",         //string(opt): general comments
            "daysOfTheWeek": null,  //integer: number of the days separated by comma where the resource will be separated (0-6)
            "from": null,           //string: a "hh:mm:ss" formatted time, the resource won't be available from this hour
            "to": null              //string: a "hh:mm:ss" formatted time, the resource won't be available until this hour
        };

    getDataFromTable("areas", "area");
    getDataFromTable("usuarios", "lendTo");

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        //timezone: 'local',
        defaultView: 'agendaDay',
        slotEventOverlap: false,
        eventOverlap: false,
        selectOverlap: false,
        lazyFetching: true,
        editable: false,
        selectable: true,
        unselectAuto: false,
        dayNames: dayNames,
        dayNamesShort: dayNamesShort,
        monthNames: monthNames,
        monthNamesShort: monthNamesShort,
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
        },
        selectAllow: function(selectInfo) {
            var startDate = selectInfo.start;
            return canSeparateOn(startDate, input);
        },
        events: function(start, end, timezone, getEventsInView) {
            var param = { "start": start.format(), "end": end.format() };
            if(input.resource != "")
                param.resourceID = input.resource;
            else
                delete param.resourceID;
            $.ajax({
                data: param,
                dataType: "json",
                error: function() {
                    alert("Error al obtener la información");
                },
                success: function(response) {
                    //console.log(response);
                    actualEvents = getEventsInView(response);
                },
                type: "POST",
                url: "../../scripts/separate/ajax/getAllEvents.php"
            });
        }
    });

    var options = {
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        minDate: 0
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
                        else
                            $(this).prop("checked", false);
                    }).change();
                } else {
                    $("#startDate").datepicker("setDate", "+0").change();
                    $("#endDate").datepicker("setDate", "+0").change();
                    $("#from").val("00:00:00").change();
                    $("#to").val("23:59:00").change();
                }
            });
        }
    }).change();

    //inputs
    $(".inputs").on("change", function() {
        $(this).css("border-color", "lightgray");
        var id = $(this).attr("id");
        input[id] = $(this).val();
    });
    /*
    $("#resource").on("change", function() { input.resource = $(this).val();});
    $("#area").on("change", function() {input.area = $(this).val();});
    $("#lendTo").on("change", function() {input.lendTo = $(this).val();});
    $("#grade").on("change", function() {input.grade = $(this).val();});
    $("#lesson").on("change", function() {input.lesson = $(this).val();});
    $("#comments").on("change", function() {input.comments = $(this).val();});
    $("#from").on("change", function() {input.from = $(this).val();});
    $("#to").on("change", function() {input.to = $(this).val();}); */

    $("#resource").on("change", function() {
        $("#calendar").fullCalendar('refetchEvents');
        $("#calendar").fullCalendar('unselect');
    });

    $("input:checkbox.daysOfTheWeek").on("change", function() {
        input = handleDaysOfTheWeek(input);
    });

    $("#separate").on("click", function() {
        if(validateInputs(input)) {
            showConfirmation(input);
        }
    });
});

/*
    Validates all the required inputs, returns boolean */
function validateInputs(fields) {
    var valid = true;
    var notRequired = ["lesson","area","grade","comments"];
    for(data in fields) {
        if(notRequired.indexOf(data) == -1) { 
            //It means that this field is required
            if(fields[data] == "" || fields[data] == null) {
                $("#"+data).css("border-color","red");
                valid = false;
            }
        }
    }
    return valid;
}

/*
    Creates a table to let the user see the preview 
    of the data that will be send to the server.
    Also sends the data to the server if the user
    confirms */
function showConfirmation(input) {
    var userResponse;
    var popup = "<div id='confirmation' class='popup'>"+
                    "<h4>¿Desea continuar con el apartado?</h4>"+
                    "<table class='table table-responsive'>"+
                        "<thead>"+
                        "</thead>"+
                        "<tbody>"+
                            "<tr>"+
                                "<th colspan='2'>Recurso </th><td colspan='2' id='resource-confirm'></td>"+
                            "</tr>"+
                            "<tr>"+
                                "<th>Desde </th><td id='start-confirm'></td>"+
                                "<th>Hasta </th><td id='end-confirm'></td>"+
                            "</tr>"+
                            "<tr>"+
                                "<th>De </th><td id='from-confirm'></td>"+
                                "<th>A </th><td id='to-confirm'></td>"+
                            "</tr>"+
                            "<tr>"+
                                "<th colspan='2'>Días solicitados </th><td colspan='2' id='daysOfTheWeek-confirm'></td>"+
                            "</tr>"+
                            "<tr>"+
                                "<th colspan='2'>Solicitante </th><td colspan='2' id='lendTo-confirm'></td>"+
                            "</tr>"+
                            "<tr>"+
                                "<th colspan='2'>Área </th><td colspan='2' id='area-confirm'></td>"+
                            "</tr>"+
                            "<tr>"+
                                "<th colspan='2'>Actividad </th><td colspan='2' id='lesson-confirm'></td>"+
                            "</tr>"+
                            "<tr>"+
                                "<th colspan='2'>Semestre </th><td colspan='2' id='grade-confirm'></td>"+
                            "</tr>"+
                            "<tr>"+
                                "<th colspan='2'>Comentarios </th><td colspan='2' id='comments-confirm'></td>"+
                            "</tr>"+
                        "</tbody>"+
                    "</table>"+
                    "<div class='row'>"+
                        "<button id='cTrue' class='btn btn-primary col-md-4 col-sm-4 col-xs-4 col-xs-offset-1 col-sm-offset-1 col-md-offset-1'>Enviar</button>"+
                        "<button id='cFalse' class='btn btn-danger col-md-4 col-sm-4 col-xs-4 col-xs-offset-1 col-sm-offset-2 col-md-offset-2'>Cancelar</button>"+
                    "</div>"+
                "</div>";
    var block = "<div id='block'></div>";
    $("body").append(block).append(popup);
    $("#cFalse").click(function(){ 
        $("#confirmation, #block").fadeOut(400, function() {
            $("#confirmation, #block").remove();
        }); 
    });
    $("#cTrue").click(function() { 
        insertEvent(input);
        input = resetInputs();
        $("#confirmation, #block").fadeOut(400, function() {
            $("#confirmation, #block").remove();
        }); 
    });
    for(data in input) { 
        if(input[data] != "" || input[data] != null)
            $("#"+data+"-confirm").text(input[data]); 
    }
    var selectedDays = input.daysOfTheWeek.split(",");
    var textDays = "";
    $.each(selectedDays, function(i, dayNumber) {
        if(i != 0)
            textDays += " - ";
        textDays += dayNamesShort[dayNumber];
    });
    $("#start-confirm").text(dateToUser(input.start));
    $("#end-confirm").text(dateToUser(input.end));
    $("#resource-confirm").text($("#resource option:selected").text());
    $("#lendTo-confirm").text($("#lendTo option:selected").text());
    $("#area-confirm").text($("#area option:selected").text());
    $("#grade-confirm").text(input.grade+"°");
    $("#daysOfTheWeek-confirm").text(textDays);
}

/*
    Function to format a more readable date */
function dateToUser(isoDate) {
    var date = new Date(isoDate);
    date.setDate(date.getDate() + 1);
    var dayOfWeek = date.getDay();
    var year = isoDate.split("-")[0];
    var month = parseInt(isoDate.split("-")[1]) - 1;
    var day = isoDate.split("-")[2];
    return dayNamesShort[dayOfWeek]+", "+day+" "+monthNamesShort[month]+" "+year;
}

/* Nothing special here "Experimental Function" */
function getEventsInView(events) {
    return events;
}

/* Resets the form when it has been sent */
function resetInputs() {
    var object = {};
    $("#device.resourceType").prop("checked", true).change();
    $("#occasional.type").prop("checked", true).change();
    $("#grade").val("");
    $("#lesson").val("");
    $("#area").val("");
    $("#comments").val("");
    object = {
        "resource": "",
        "start": null,
        "end": null,
        "grade": "",
        "lesson": "",
        "area": null,
        "lendTo": null,
        "comments": "",
        "daysOfTheWeek": null,
        "from": null,
        "to": null
    };
    return object;
}

/*
    Function to form a string with all the
    checked checkboxes separated with comma. */
function handleDaysOfTheWeek(input) {
    input.daysOfTheWeek = "";
    $("input:checkbox.daysOfTheWeek:checked").each(function() {
        if(input.daysOfTheWeek != "")
            input.daysOfTheWeek += ",";    
        input.daysOfTheWeek += $(this).val();
    });
    return input;
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
function canSeparateOn(date, input) {
    var today = new Date();
    // Full calendar returns the absolute time, so here in Mexico
    // we just add 6 hours
    
    date._d.setHours(date._d.getHours() + 6);
    da = date;
    if(date._d < today){
        date._d.setHours(date._d.getHours() - 6);
        return false;
    }
    date._d.setHours(date._d.getHours() - 6);
    return true;
}
var da;

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
        error: function(e) {
            alert("Error al conseguir los recursos");
        },
        success: function(response) {
            $("#resource").html(response);
            $("#resource").prepend("<option value=''>Seleccione un recurso...</option>");
            $("#resource").val("").change();
        },
        type: "POST",
        url: "../../scripts/separate/ajax/getAvailableResources.php"
    });
}

/* 
    Does an ajax request to separate a resource 
    Parameter: Object 
        input = {
            "resource": resourceId,     //integer: the resource's id
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
    Return: "TRUE" if the insert went as expected, "FALSE" if not */
function insertEvent(input) {
    // input must be an object!!!
    $.ajax({
        data: input,
        dataType: "text",
        error: function() {
            alert("Error al obtener la información");
        },
        success: function(response) {
            if(response == "TRUE")
                $("#calendar").fullCalendar( 'refetchEvents' );
            else
                console.log("Error at separating the resource");
        },
        type: "POST",
        url: "../../scripts/separate/ajax/insertEvent.php"
    });
}

/*  
    Function to get two fields from any table in the database
    Parameters:
        table       //string: the table's name
        divId       //string: the id of the "select" element in which the
                                response will render
    Example:
        getDataFromTable("areas", "area")  */
function getDataFromTable(table, divId) {
    $.ajax({
        data: { "table": table },
        dataType: "html",
        error: function() {
            alert("Error al conseguir la información");
        },
        success: function(response) {
            $("#"+divId).html(response).change();
            $("#"+divId).prepend("<option value=''>Seleccione una opción...</option>");
            $("#"+divId).val("").change();
        },
        type: "POST",
        url: "../../scripts/separate/ajax/getDataFromTable.php"
    });
}