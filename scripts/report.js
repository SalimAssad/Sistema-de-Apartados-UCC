$(document).ready(function () {
    var headers = [];
    var results = [];
    var i = 0;
    $(".header").each(function () {
        headers[i] = $(this).html();
        i++;
    });
    i = 0;
    $(".result").each(function () {
        results[i] = parseInt($(this).html());
        i++;
    });
    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },

        title: {
            text: 'Gráfica'
        },

        xAxis: {
            categories: headers
        },

        series: [{
            type: 'column',
            colorByPoint: true,
            data: results,
            showInLegend: false
        }]

    });
});