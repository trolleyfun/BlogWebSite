<!-- Google Charts -->
<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        /* Search for chart elements in html */
        var chart_elements = document.querySelectorAll('.dashboard_chart');
        chart_elements.forEach((item) => {
            /* Get chart properties and data from the tag attributes */
            var chart_color = item.getAttribute('data-color');
            var chart_names = item.getAttribute('data-names').split(' ');
            var chart_values = item.getAttribute('data-values').split(' ');
            var chart_length = Math.min(chart_values.length, chart_names.length);
            var axis_x_title = item.getAttribute('data-x-title');
            var axis_y_title = item.getAttribute('data-y-title');
            var chart_title = item.getAttribute('data-chart-title');
            var chart_array = [[axis_x_title, axis_y_title]];

            for (var i = 0; i < chart_length; i++) {
                chart_array.push([chart_names[i], parseInt(chart_values[i])]);
            }

            /* Create chart */
            var data = google.visualization.arrayToDataTable(chart_array);

            var options = {
                chart: {
                    title: chart_title,
                    subtitle: '',
                },
                colors: chart_color
            };

            var chart = new google.charts.Bar(item);

            chart.draw(data, google.charts.Bar.convertOptions(options));
        });
        
    }
</script>