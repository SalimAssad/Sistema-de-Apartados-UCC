<script src="../../utils/jquery-1.12.3.min.js">
</script>
<script src="utils/highcharts/highcharts.js"></script>
<script src="utils/highcharts/modules/exporting.js"></script>
<script type="text/javascript">
    $(function () {
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
</script>

<table>
    <tr>
        <td class="area">
            Area 1
        </td>
        <td class="result">
            7
        </td>
    </tr>
    <tr>
        <td class="area">
            Area 2
        </td>
        <td class="result">
            12
        </td>
    </tr>
    <tr>
        <td class="area">
            Area 3
        </td>
        <td class="result">
            4
        </td>
    </tr>
    <tr>
        <td class="area">
            Area 4
        </td>
        <td class="result">
            9
        </td>
    </tr>
</table>

<div id="container">

</div>