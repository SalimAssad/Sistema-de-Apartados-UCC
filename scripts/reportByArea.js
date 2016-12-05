$(document).ready(function () {
    var areas = $(".area").toArray();
    var results = $(".result").toArray();
    Highcharts.chart('container', {

        title: {
            text: 'Chart.update'
        },

        subtitle: {
            text: 'Plain'
        },

        xAxis: {
            categories: areas
        },

        series: [{
            type: 'column',
            colorByPoint: true,
            data: results,
            showInLegend: false
        }]

    });
});