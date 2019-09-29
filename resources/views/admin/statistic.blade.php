@extends('layouts.app')

@section('content')

    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">{{ $title }}</h3>

        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column">
                <!--Div that will hold the pie chart-->
                <div id="chart_div"></div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {packages: ['corechart', 'line']});
        google.charts.setOnLoadCallback(drawBasic);

        function drawBasic() {

            var data = new google.visualization.DataTable();
            data.addColumn('date', 'Month');
            data.addColumn('number', 'Articles');

            data.addRows([
                    @foreach($dates as $date)
                        [new Date({{$date['year']}}, {{$date['month']}}), {{$date['count']}}],
                    @endforeach
            ]);

            var options = {

                title: 'Number of articles by month for last year',
                hAxis: {
                    title: 'Month'
                },
                vAxis: {
                    title: 'Count of articles'
                }
            };

            var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

            chart.draw(data, options);
        }
    </script>
@endsection