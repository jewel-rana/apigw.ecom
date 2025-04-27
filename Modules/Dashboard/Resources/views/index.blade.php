@extends('metis::layouts.master')

@section('header')

@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light lter">
                <div class="text-center">
                    <div class="row">
                        <div class="col-sm-6">
                            <fieldset>
                                <legend>Today's sales</legend>
                                <ul class="stats_box">
                                    <li>
                                        <div class="stat_text">
                                            <strong>{{ \App\Helpers\CommonHelper::shorten($statistics['today_sales']['vouchers'] ?? 0) }}</strong>Vouchers
                                            @php
                                                $growth = \App\Helpers\CommonHelper::calculatePercentageGrowth($statistics['yesterday_sales']['vouchers'] ?? 0, $statistics['today_sales']['vouchers'] ?? 0);
                                                $shortenGrowth = \App\Helpers\CommonHelper::shorten($growth);
                                                if($growth >= 0){
                                                    echo "<span class='percent up'><i class='fa fa-caret-up'></i> {$shortenGrowth}%</span>";
                                                } else {
                                                    echo "<span class='percent down'><i class='fa fa-caret-down'></i> {$shortenGrowth}%</span>";
                                                }
                                            @endphp
                                        </div>
                                    </li>
                                    <li>
                                        <div class="stat_text">
                                            <strong>{{ \App\Helpers\CommonHelper::shorten($statistics['today_sales']['bill-payment'] ?? 0) }}</strong>Bills
                                            @php
                                                $growth = \App\Helpers\CommonHelper::calculatePercentageGrowth($statistics['yesterday_sales']['bill-payment'] ?? 0, $statistics['today_sales']['bill-payment'] ?? 0);
                                                $shortenGrowth = \App\Helpers\CommonHelper::shorten($growth);
                                                if($growth >= 0){
                                                    echo "<span class='percent up'><i class='fa fa-caret-up'></i> {$shortenGrowth}%</span>";
                                                } else {
                                                    echo "<span class='percent down'><i class='fa fa-caret-down'></i> {$shortenGrowth}%</span>";
                                                }
                                            @endphp
                                        </div>
                                    </li>
                                    <li>
                                        <div class="stat_text">
                                            <strong>{{ \App\Helpers\CommonHelper::shorten($statistics['today_sales']['mobile-recharge'] ?? 0) }}</strong>Recharges
                                            @php
                                                $growth = \App\Helpers\CommonHelper::calculatePercentageGrowth($statistics['yesterday_sales']['mobile-recharge'] ?? 0, $statistics['today_sales']['mobile-recharge'] ?? 0);
                                                $shortenGrowth = \App\Helpers\CommonHelper::shorten($growth);
                                                if($growth >= 0){
                                                    echo "<span class='percent up'><i class='fa fa-caret-up'></i> {$shortenGrowth}%</span>";
                                                } else {
                                                    echo "<span class='percent down'><i class='fa fa-caret-down'></i> {$shortenGrowth}%</span>";
                                                }
                                            @endphp
                                        </div>
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                        <div class="col-sm-6">
                            <fieldset>
                                <legend>Yesterday's sales</legend>
                                <ul class="stats_box">
                                    <li>
                                        <div class="stat_text">
                                            <strong>{{ \App\Helpers\CommonHelper::shorten($statistics['yesterday_sales']['vouchers'] ?? 0) }}</strong>Vouchers
                                            @php
                                                $growth = \App\Helpers\CommonHelper::calculatePercentageGrowth($statistics['today_sales']['vouchers'] ?? 0, $statistics['yesterday_sales']['vouchers'] ?? 0);
                                                $shortenGrowth = \App\Helpers\CommonHelper::shorten($growth);
                                                if($growth >= 0){
                                                    echo "<span class='percent up'><i class='fa fa-caret-up'></i> {$shortenGrowth}%</span>";
                                                } else {
                                                    echo "<span class='percent down'><i class='fa fa-caret-down'></i> {$shortenGrowth}%</span>";
                                                }
                                            @endphp
                                        </div>
                                    </li>
                                    <li>
                                        <div class="stat_text">
                                            <strong>{{ \App\Helpers\CommonHelper::shorten($statistics['yesterday_sales']['bill-payment'] ?? 0) }}</strong>Bills
                                            @php
                                                $growth = \App\Helpers\CommonHelper::calculatePercentageGrowth($statistics['today_sales']['bill-payment'] ?? 0, $statistics['yesterday_sales']['bill-payment'] ?? 0);
                                                $shortenGrowth = \App\Helpers\CommonHelper::shorten($growth);
                                                if($growth >= 0){
                                                    echo "<span class='percent up'><i class='fa fa-caret-up'></i> {$shortenGrowth}%</span>";
                                                } else {
                                                    echo "<span class='percent down'><i class='fa fa-caret-down'></i> {$shortenGrowth}%</span>";
                                                }
                                            @endphp
                                        </div>
                                    </li>
                                    <li>
                                        <div class="stat_text">
                                            <strong>{{ \App\Helpers\CommonHelper::shorten($statistics['yesterday_sales']['mobile-recharge'] ?? 0) }}</strong>Recharge
                                            @php
                                                $growth = \App\Helpers\CommonHelper::calculatePercentageGrowth($statistics['today_sales']['mobile-recharge'] ?? 0, $statistics['yesterday_sales']['mobile-recharge'] ?? 0);
                                                $shortenGrowth = \App\Helpers\CommonHelper::shorten($growth);
                                                if($growth >= 0){
                                                    echo "<span class='percent up'><i class='fa fa-caret-up'></i> {$shortenGrowth}%</span>";
                                                } else {
                                                    echo "<span class='percent down'><i class='fa fa-caret-down'></i> {$shortenGrowth}%</span>";
                                                }
                                            @endphp
                                        </div>
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="box">
                            <header>
                                <h5>Last 6 months sales <small>(Excluded today's sales)</small></h5>
                            </header>
                            {{--                            <div class="body" id="myChart" style="height: 250px;"></div>--}}
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="box">
                            <div class="body">
                                <a class="quick-btn" href="{{ route('operator.index') }}">
                                    <i class="fa fa-cube fa-2x"></i>
                                    <span>Operators</span>
                                    <span class="label label-default">{{ $statistics['operators'] }}</span>
                                </a>
                                <a class="quick-btn" href="{{ route('bundle.index') }}">
                                    <i class="fa fa-cubes fa-2x"></i>
                                    <span>Bundles</span>
                                    <span class="label label-info">{{ $statistics['bundles'] }}</span>
                                </a>
                                <a class="quick-btn" href="">
                                    <i class="fa fa-id-card fa-2x"></i>
                                    <span>Cards</span>
                                    <span class="label label-success">{{ $statistics['cards'] }}</span>
                                </a>
                                <div>
                                    <h5>Today's sales</h5>
                                    <canvas id="myPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
{{--                <div class="row">--}}
{{--                    <div class="col-lg-12">--}}
{{--                        <div class="box">--}}
{{--                            <header>--}}
{{--                                <h5>Calendar</h5>--}}
{{--                            </header>--}}
{{--                            <div id="calendar_content" class="body">--}}
{{--                                <div id='calendar'></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}


{{--                <div class="row">--}}
{{--                    <div class="col-lg-12">--}}
{{--                        <div class="box">--}}
{{--                            <div id="calendar_content" class="body">--}}
{{--                                <h4>Coming...</h4>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

            </div>
            <!-- /.inner -->
        </div>
        <!-- /.outer -->
    </div>
    <!-- /#content -->
@endsection

@section('footer')
    <script src="//cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(function () {
            const data = {
                labels: {!! json_encode($statistics['bar']['labels']) !!},
                datasets: [
                    {
                        label: 'Cards',
                        data: {!! json_encode($statistics['bar']['vouchers']) !!},
                        backgroundColor: '#67d088',
                        borderColor: '#fff'
                    },
                    {
                        label: 'Bills',
                        data: {!! json_encode($statistics['bar']['payments']) !!},
                        backgroundColor: 'rgb(6,114,106)',
                        borderColor: '#fff'
                    },
                    {
                        label: 'Recharges',
                        data: {!! json_encode($statistics['bar']['recharges']) !!},
                        backgroundColor: '#31a0ba',
                        borderColor: '#fff'
                    }
                ]
            };
            const ctx = document.getElementById('myChart');
            const config = {
                type: 'bar',
                data: data,
                options: {
                    plugins: {
                        title: {
                            display: false,
                            text: 'Last 7 days sales graph'
                        },
                    },
                    responsive: true,
                    scales: {
                        x: {
                            stacked: false,
                        },
                        y: {
                            stacked: false
                        }
                    }
                }
            }
            const stackedBar = new Chart(ctx, config);

            const pieData = {
                labels: ['Cards', 'Bills', 'Recharges'],
                datasets: [
                    {
                        label: 'Dataset 1',
                        data: [{{ $statistics['today_sales']['vouchers'] }}, {{ $statistics['today_sales']['bill-payment'] }}, {{ $statistics['today_sales']['mobile-recharge'] }}],
                        backgroundColor: ['#67d088', 'rgb(6,114,106)', '#31a0ba', 'Green', 'Blue'],
                    }
                ]
            };

            const pieConfig = {
                type: 'pie',
                data: pieData,
                options: {
                    responsive: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false,
                            text: 'Today\'s sales'
                        }
                    }
                },
            };
            const pieCtx = document.getElementById('myPieChart');
            const pieChart = new Chart(pieCtx, pieConfig);
        });
    </script>
@endsection
