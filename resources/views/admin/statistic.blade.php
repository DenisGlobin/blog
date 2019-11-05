@extends('layouts.app')

@section('content')

    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">{{ $title }}</h3>

        <form class="form-inline" method="post" action="{{ route('period.set') }}">
            @csrf
            <input id="userID" name="userID" type="hidden" value="{{ isset($userID) ? $userID : null }}">
            <fieldset>
                <legend>Select statistic period</legend>
                <p>
                    <input type="month" name="dateFrom" list="monthList" id="dateFrom"
                           min="{{ $dates->getStartDate()->format('Y-m') }}"
                           max="{{ $dates->getEndDate()->format('Y-m') }}"
                           value="{{ isset($selectedFrom) ? $selectedFrom : $dates->getStartDate()->format('Y-m') }}">
                    <datalist id="monthList">
                        @foreach ($dates as $date)
                            <option value="{{$date->format('Y-m')}}">
                        @endforeach
                    </datalist>
                    <input type="month" name="dateUntil" list="monthList" id="dateUntil"
                           min="{{ $dates->getStartDate()->format('Y-m') }}"
                           max="{{ $dates->getEndDate()->format('Y-m') }}"
                           value="{{ isset($selectedUntil) ? $selectedUntil : $dates->getEndDate()->format('Y-m') }}">

                    <button type="submit" class="btn btn-primary my-1">Submit</button>
                </p>
            </fieldset>
        </form>

        <!--Div that will hold the articles column chart per month-->
        <div id="columnchart" style="height: 400px"></div>
        <!--Div that will hold the tags pie chart-->
        <div id="piechart" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
    </div>

@endsection

@section('js')
    <script type="text/javascript" src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script type="text/javascript">
        Highcharts.chart('columnchart', {
            chart: {
                type: 'column'
            },
            xAxis: {
                categories:
                [
                    @foreach($articlesAndCommentsStat as $statistic)
                        '{{$statistic['date']}}',
                    @endforeach
                ]
            },

            plotOptions: {
                series: {
                    minPointLength: 3
                }
            },

            title: {
                text: 'Articles and comments statistic'
            },

            series: [{
                name: 'Total articles',
                data:
                [
                    @foreach($articlesAndCommentsStat as $statistic)
                        {{$statistic['artcl_total']}},
                    @endforeach
                ]
            }, {
                name: 'Total comments',
                data:
                [
                    @foreach($articlesAndCommentsStat as $statistic)
                        {{$statistic['cmnt_total']}},
                    @endforeach
                ]
            }]
        });
    </script>

    <script type="text/javascript">
        // Radialize the colors
        Highcharts.setOptions({
            colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
                return {
                    radialGradient: {
                        cx: 0.5,
                        cy: 0.3,
                        r: 0.7
                    },
                    stops: [
                        [0, color],
                        [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
                    ]
                };
            })
        });

        // Build the chart
        Highcharts.chart('piechart', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Tags Chart'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        connectorColor: 'silver'
                    }
                }
            },
            series: [{
                name: 'Share',
                data: [
                    @foreach ($tagsCount as $key => $tagCount)
                        {name: '{{$key}}', y: {{ $tagCount }} },
                    @endforeach
                ]
            }]
        });
    </script>

    <script>
        $(function () {
            $("select#yearFrom").on('change', function (e) {
                let optionSelected = $("option:selected", this);
                let year = this.value;
                // Get months from selected year
                let month = {!! json_encode($dates) !!};
                // Delete old month's buttons
                let buttonElem = $("label.btn-secondary");
                buttonElem.remove();
                // Show months buttons
                // $("div#blyat").after("" +
                //     "<div id='dateRow' class='btn-group btn-group-toggle' data-toggle='buttons'>" +
                // "");
                let monthRow = 1;
                let i = 1;
                month[year].forEach(function(element) {
                //for (i=0; i < month[year].length; i++) {
                    $("div#dateRow"+monthRow+"").append("" +
                        "<label class='btn btn-secondary'>" +
                        "<input type='radio' name='options' id='option1' autocomplete='off'>" + element +
                        "</label>" +
                    "");
                    if ((i % 4) == 0) {
                        monthRow++;
                    }
                    i++;
                });
                // $(".btn-outline-secondary").after("</div>");
            });
        });
    </script>
@endsection