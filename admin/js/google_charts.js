google.charts.load('current', {'packages':['bar']});
google.charts.setOnLoadCallback(drawChart1bar);
google.charts.setOnLoadCallback(drawChart2bar);

/* Charts with 1 bar */
function drawChart1bar() {
    /* Search for chart elements in html */
    var chart_elements = document.querySelectorAll('.dashboard_chart_1bar');
    chart_elements.forEach((item) => {
        /* Get chart properties and data from the tag attributes */
        var chart_color = item.getAttribute('data-color');
        /* '#' is divider between elements of array */
        var chart_names = item.getAttribute('data-names').split('#');
        var chart_values = item.getAttribute('data-values').split('#');
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
            colors: [chart_color]
        };

        var chart = new google.charts.Bar(item);

        chart.draw(data, google.charts.Bar.convertOptions(options));
    }); 
}

/* Charts with 2 bars */
function drawChart2bar() {
    /* Search for chart elements in html */
    var chart_elements = document.querySelectorAll('.dashboard_chart_2bar');
    chart_elements.forEach((item) => {
        /* Get chart properties and data from the tag attributes */
        var chart_color1 = item.getAttribute('data-color1');
        var chart_color2 = item.getAttribute('data-color2');
        /* '#' is divider between elements of array */
        var chart_names = item.getAttribute('data-names').split('#');
        var chart_values1 = item.getAttribute('data-values1').split('#');
        var chart_values2 = item.getAttribute('data-values2').split('#');
        var chart_length = Math.min(chart_values1.length, chart_values2.length, chart_names.length);
        var axis_x_title = item.getAttribute('data-x-title');
        var axis_y_title1 = item.getAttribute('data-y-title1');
        var axis_y_title2 = item.getAttribute('data-y-title2');
        var chart_title = item.getAttribute('data-chart-title');
        var chart_array = [[axis_x_title, axis_y_title1, axis_y_title2]];

        for (var i = 0; i < chart_length; i++) {
            chart_array.push([chart_names[i], parseInt(chart_values1[i]), parseInt(chart_values2[i])]);
        }

        /* Create chart */
        var data = google.visualization.arrayToDataTable(chart_array);

        var options = {
            chart: {
                title: chart_title,
                subtitle: '',
            },
            colors: [chart_color1, chart_color2]
        };

        var chart = new google.charts.Bar(item);

        chart.draw(data, google.charts.Bar.convertOptions(options));
    }); 
}
