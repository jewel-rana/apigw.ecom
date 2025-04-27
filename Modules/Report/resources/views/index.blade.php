@extends('metis::layouts.master')

@section('header')
    <style>
        #our-stats {
            padding: 30px 0;
        }

        #our-stats .stat-item {
            margin-bottom: 30px;
        }

        #our-stats .h1 {
            color: #007b5e !important;
        }

        #our-stats a {
            color: #000 !important;
            text-decoration: none;
        }

        #our-stats i {
            color: #007b5e !important;
        }

        #our-stats .counter {
            background-color: #f5f5f5;
            padding: 35px 0;
            border-radius: 5px;
        }

        #our-stats .counter i,
        #our-stats .counter .count-title {
            margin-bottom: 15px;
        }

        #our-stats .counter p {
            font-style: italic;
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3>Reports</h3>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs menuTab" role="tablist">
                        <li role="presentation"
                            class="@if(!request()->has('tab') || request()->input('tab') == 'info') active @endif">
                            <a href="#home" aria-controls="home" role="tab" data-toggle="tab"
                               aria-expanded="true">Generate</a>
                        </li>
                        @if(\App\Helpers\CommonHelper::hasPermission(['report-download']))
                            <li role="presentation"
                                class="@if(request()->has('tab') && request()->input('tab') == 'download') active @endif">
                                <a href="#downloads" aria-controls="downloads" role="tab"
                                   data-toggle="tab">Downloads</a>
                            </li>
                        @endif
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel"
                             class="tab-pane fade @if(!request()->has('tab') || request()->input('tab') == 'info') active in @endif"
                             id="home">
                            <section id="our-stats">
                                <div class="row text-center">
                                    <div class="col-sm-3 stat-item">
                                        <a href="{{ route('report.transaction') }}">
                                            <div class="counter">
                                                <i class="fa fa-bar-chart fa-2x text-green"></i>
                                                {{--                                    <h2 class="timer count-title count-number" data-to="100" data-speed="1500">100</h2>--}}
                                                <p class="count-text ">Transactions</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-sm-3 stat-item">
                                        <a href="{{ route('report.order') }}">
                                            <div class="counter">
                                                <i class="fa fa-shopping-cart fa-2x text-green"></i>
                                                {{--                                    <h2 class="timer count-title count-number" data-to="1700" data-speed="1500">1,700</h2>--}}
                                                <p class="count-text ">Orders</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-sm-3 stat-item">
                                        <a href="{{ route('report.customer') }}">
                                            <div class="counter">
                                                <i class="fa fa-users fa-2x text-green"></i>
                                                {{--                                    <h2 class="timer count-title count-number" data-to="11900" data-speed="1500">11,900</h2>--}}
                                                <p class="count-text ">Customers</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div role="tabpanel"
                             class="tab-pane fade @if(request()->has('tab') && request()->input('tab') == 'download') active in @endif"
                             id="downloads">
                            <table class="table table-striped table-bordered" id="dataTable">
                                <thead>
                                <tr>
                                    <th>
                                        <div>ID</div>
                                    </th>
                                    <th>
                                        <div>Report Type</div>
                                    </th>
                                    <th>
                                        <div>Criteria</div>
                                    </th>
                                    <th>
                                        <div>Created at</div>
                                    </th>
                                    <th>
                                        <div>Exported by</div>
                                    </th>
                                    <th>
                                        <div><i class="fa fa-check-circle"></i></div>
                                    </th>
                                    <th style="width:95px;" class="align-middle">
                                        <div><i class="fa fa-wrench"></i></div>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script>
        $(function () {
            let table = $('#dataTable').DataTable({
                "processing": true,
                "serverSide": true,
                responsive: true,
                "ajax": {
                    'url': "{{ route('report.export.index') }}",
                    pages: 5, // number of pages to cache
                    'data': function (data) {
                    }
                },
                "bAutoWidth": false,
                "sPageButtonActive": "active",
                dom: 'lrtip',
                "lengthChange": true,
                "oLanguage": {
                    "sLengthMenu": "Show _MENU_ ",
                },
                "pageLength": 25,
                "bFilter": true,
                "bInfo": true,
                "searching": true,
                "order": [[0, "desc"]],
                deferRender: true,
                columns: [
                    {data: 'id'},
                    {data: 'type'},
                    {data: 'criteria', sortable: false},
                    {data: 'created_at', order: false},
                    {data: 'user.name', sortable: false},
                    {data: 'status', sortable: false},
                    {data: 'action', sortable: false}
                ],
                createdRow: function (row, data, dataIndex) {
                    if (data.status == "failed") {
                        $(row).addClass("text-danger");
                    } else if (data.status == "success") {
                        $(row).addClass("text-success");
                    } else {
                        $(row).addClass("text-info");
                    }
                },
            });

            $('table').on('click', '.deleteExport', function () {
                let action = $(this).data('action');
                $.ajax({
                    type: "DELETE",
                    url: action,
                    data: null,
                    success: function (response, status, xhr) {
                        defaultToast(response.status, response.message);
                        table.draw();
                    }
                })
            });

            //Date range as a button
            $('#reportrange').daterangepicker(
                {
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function (start, end) {
                    $('#reportrange span').html(start.format('D/MM/YYYY') + ' - ' + end.format('D/MM/YYYY'));
                    sellAt = start.format('YYYY-MM-D');
                    sellAtTo = end.format('YYYY-MM-D');
                    table.draw();
                }
            )

            $('#dropDownForm').on('click', e => {
                e.preventDefault();
                e.stopPropagation();
                console.log('Dropdown prevented');
            });
            $(".dropdown-menu").on("show.bs.dropdown", function (e) {
                if (!e.relatedTarget.value) {
                    return false;
                }
            });

            $(document).on("ajaxComplete", function (event, xhr, settings) {
                if (settings.type == 'POST') {
                    if (settings.url == baseUrl + "/transaction/action") {
                        table.draw();
                    }
                }
            });
        });
    </script>
@endsection
