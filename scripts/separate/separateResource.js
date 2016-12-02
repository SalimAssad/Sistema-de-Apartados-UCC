var actualEvents;
var dayNamesShort = ['Dom','Lun','Mar','Mie','Jue','Vie','Sáb'];
var monthNamesShort = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
var dayNames = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
var monthNames = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
var input;
$(function() {
    var minSelection;
    var maxSelection;
    var options = {
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        minDate: 0
    };
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
        customButtons: {
            selectDate: {
                text: 'Seleccionar día...',
                click: function() {
                    $("#selectDate").remove();
                    $(this).parent().append("<input type='text' readonly class='form-control' style='width: 100px' id='selectDate' placeholder='Clic aquí'/>");
                    $("#selectDate").datepicker(options);
                    $("#selectDate").change(function() {
                        $("#calendar").fullCalendar( 'gotoDate', $(this).val() );
                    });
                }
            }
        },
        header: {
            left: 'title next',
            center: '',
            right: 'selectDate'
        },
        allDaySlot: false,
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
            minSelection = start;
            maxSelection = end;
            $(".type").change();
        },
        selectAllow: function(selectInfo) {
            if($("#resource").val() == "") {
                $(".moving-box").text("Seleccione primero el recurso");
                handleMovingBox(true);
                return false;
            }
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
            $(".temporary").fadeIn(400, function() {
                if(maxSelection != undefined &&
                    minSelection != undefined) {
                    $("#from").val(minSelection.format().split("T")[1]).change();
                    $("#to").val(maxSelection.format().split("T")[1]).change();
                }
            });
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
                        $(this).prop("checked", false);
                        if($(this).val() == day)
                            $(this).prop("checked", true);
                    }).change();
                } else {
                    $("#startDate").datepicker("setDate", "+0").change();
                    $("#endDate").datepicker("setDate", "+0").change();
                    $("input:checkbox.daysOfTheWeek").each(function() {
                        var day = new Date();
                        $(this).prop("checked", false);
                        if($(this).val() == day.getDay())
                            $(this).prop("checked", true);
                    }).change();
                }
            });
        }
    }).change();

    // Form inputs' events
    $(".inputs").on("change", function() {
        $(this).css("border-color", "lightgray");
        var id = $(this).attr("id");
        input[id] = $(this).val();
    });

    $("#resource").on("change", function() {
        $("#calendar").fullCalendar('refetchEvents');
        $("#calendar").fullCalendar('unselect');
        input.from = null;
        input.to = null;
    });

    $("input:checkbox.daysOfTheWeek").on("change", function() {
        input = handleDaysOfTheWeek(input);
    });

    $("#area").on("change", function() {
        if($(".optional").css("display") == "none"
            && $(this).val() != "")
            $(".optional").fadeIn();
    });

    $("#separate").on("click", function() {
        $("body").scrollTop(0);
        if(validateInputs(input)) {
            showConfirmation(input);
        }
    });
});

/*
    Validates all the required inputs, returns boolean */
function validateInputs(fields) {
    var valid = true;
    var msg = "";
    //From and To are handled apart
    var notRequired = ["lesson","grade","comments","from","to"];
    for(data in fields) {
        if(notRequired.indexOf(data) == -1) { 
            //It means that this field is required
            if(fields[data] == "" || fields[data] == null) {
                $("#"+data).css("border-color","#a8011d");
                msg = "Por favor, introduzca los campos requeridos marcados con rojo";
                valid = false;
            }
        }
    }
    if(fields.from == null || fields.to == null) {
        valid = false;
        if(msg != "") msg += " y ";
        msg += "Seleccione un horario arrastrando el mouse sobre el calendario";
        $(".gif").fadeIn();
        setTimeout(function() { $(".gif").fadeOut(); },6400);
    }
    if(!valid)
        showMessage("glyphicon-remove-sign", msg, "alert-danger");
    return valid;
}

/*
    Function that shows a custom message by passing three parameters 
    icon: String: the name of the bootstrap glyphicon
    msg: String: the text that will be desplayed in the box
    style: String: the bootstrap style of the box alert-success|alert-danger|...| */
function showMessage(icon, msg, style) {
    $("#message").html("<span class='glyphicon "+icon+"'></span> "+msg);
    $("#message").attr("class", "col-md-6 alert text-center "+style);
    $("#message").fadeIn();
    setTimeout(function() {
        $("#message").fadeOut();
    }, 5000);
}

/* 
    Does an Ajax request to get the code introduced by the user
    when logged in */
function validateAdminTransaction(code) {
    if(code == "")
        return false;
    var validate = false;
    $.ajax({
        data: { "verificationCode": code },
        dataType: "html",
        error: function() {
            alert("Error al conseguir la información");
        },
        success: function(response) {
            if(response == "TRUE")
                validate = true;
        },
        type: "POST",
        url: "../../scripts/separate/ajax/validateAdminTransaction.php",
        async: false
    });
    return validate;
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
                    "<div id='confirm-table' class='col-md-12 col-sm-12'>"+
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
                                    "<th colspan='2'>Horario </th><td id='from-confirm'></td><td id='to-confirm'></td>"+
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
                    "</div>"+
                    "<div class='col-md-12 col-sm-12 margin-top'>"+
                        "<label for='verifCode'>Introduce el código de verificación</label>"+
                        "<input type='text' id='verifCode' class='form-control'/>"+
                        "<div class='verification-error'></div>"+
                    "</div>"+
                    "<div class='col-md-12 col-sm-12 margin-top'>"+
                        "<button id='cTrue' class='btn btn-primary col-md-5 col-sm-5 col-xs-5'>Apartar</button>"+
                        "<button id='cFalse' class='btn btn-danger col-md-5 col-sm-5 col-xs-5 col-xs-offset-2 col-sm-offset-2 col-md-offset-2'>Cancelar</button>"+
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
        if($("#verifCode").val() == "") {
            $(".verification-error").text("Por favor, introduzca su código de autenticación");
            $(".verification-error").fadeIn();
        } else {
            if(validateAdminTransaction($("#verifCode").val())) {
                insertEvent(input);
                input = resetInputs();
                $("#confirmation, #block").fadeOut(400, function() {
                    $("#confirmation, #block").remove();
                });
            } else {
                $(".verification-error").text("El valor ingresado no coincide con el que se ha definido al iniciar sesión.");
                $(".verification-error").fadeIn();
            }
        }
    });
    $("#verifCode").keyup(function(event){
        if(event.keyCode == 13){
            $("#cTrue").click();
        }
    });
    // For in to save coding for lesson, comments, from and to 
    // that are showed without parsing, and grade
    for(data in input) { 
        if(input[data] != "" || input[data] != null) {
            $("#"+data+"-confirm").text(input[data]); 
            if(data == "grade")
                $("#"+data+"-confirm").text(input[data]+"°"); 
        }
    }
    var selectedDays = input.daysOfTheWeek.split(",");
    var textDays = "";
    $.each(selectedDays, function(i, dayNumber) {
        if(i != 0)
            textDays += " - ";
        textDays += dayNamesShort[dayNumber];
    });
    $("#daysOfTheWeek-confirm").text(textDays);
    $("#start-confirm").text(dateToUser(input.start));
    $("#end-confirm").text(dateToUser(input.end));
    $("#resource-confirm").text($("#resource option:selected").text());
    $("#lendTo-confirm").text($("#lendTo option:selected").text());
    $("#area-confirm").text($("#area option:selected").text());

    if(input.start == input.end) {
        $("#daysOfTheWeek-confirm").parent().remove();
        $("#start-confirm").attr("colspan","2").next().remove();
        $("#start-confirm").next().remove();
        $("#start-confirm").prev().attr("colspan","2").text("Durante el día");
    }
    // Optional fields - Only tested on Chrome
    if(!input.grade)
        $("#grade-confirm").parent().remove();
    if(!input.lesson)
        $("#lesson-confirm").parent().remove();
    if(!input.comments)
        $("#comments-confirm").parent().remove();
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

/* This function handles the moving box that tracks the cursor
    when is positioned over the calendar, usually it should be
    showed if the user try to select past hours so the
    @boolean parameter indicates if it will be shown or not */
function handleMovingBox(show) {
    if(show) {
        $(".moving-box").fadeIn();
        $(".fc-view-container").on("mousemove", function(event) {
            $(".moving-box").css("left", event.pageX + 20);
            $(".moving-box").css("top", event.pageY - ($(".moving-box").height() * 2));
        });
        $(".fc-view-container").on("mouseleave", function(event) {
            $(".moving-box").fadeOut();        
        });
    } else {
        $(".moving-box").fadeOut();
        $(".fc-view-container").off("mousemove");
        $(".fc-view-container").off("mouseleave");
    }
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
    // We add a delay of 15 min to handle human inconsistences
    today.setMinutes(today.getMinutes() - 15);
    if(date._d < today){
        date._d.setHours(date._d.getHours() - 6);
        $(".moving-box").text("No se puede seleccionar una hora pasada");
        handleMovingBox(true);
        return false;
    }
    date._d.setHours(date._d.getHours() - 6);
    handleMovingBox(false);
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
            $("body").scrollTop(0);
            var icon = "", msg = "", styleAlert = "";
            if(response == "TRUE") {
                icon = "glyphicon-ok-sign";
                msg = "¡El apartado se realizó con éxito!";
                styleAlert = "alert-success";
            } else {
                icon = "glyphicon-remove-sign";
                msg = "Hubo un error, intente de nuevo, por favor";
                if(response == "ALREADY SEPARATED")
                    msg = "Este recurso ya se encuentra apartado en al menos un día de los definidos, intente con otro horario, por favor.";
                styleAlert = "alert-danger";
            }
            $("#calendar").fullCalendar('refetchEvents');
            showMessage(icon, msg, styleAlert);
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
            $("#"+divId).html(response);
            $("#"+divId).prepend("<option value=''>Seleccione una opción...</option>");
            $("#"+divId).val("").change();
        },
        type: "POST",
        url: "../../scripts/separate/ajax/getDataFromTable.php"
    });
}