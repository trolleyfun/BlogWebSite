<!-- Google Charts -->
<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['', ''],
        ['публикации', 2],
        ['комментарии', 8],
        ['регионы', 10],
        ['пользователи', 5]
        ]);

        var options = {
        chart: {
            title: '',
            subtitle: '',
        }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>