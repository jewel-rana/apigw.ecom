@extends('metis::layouts.master')

@section('header')
    <style>
        .form-horizontal .form-group {
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
    </style>
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3>{{ $title ?? 'Settings'}}</h3>
                <div>

                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item @if($tab === 'general') active @endif">
                            <a class="nav-link" id="general-tab"
                               data-toggle="tab" href="#general"
                               aria-controls="general" role="tab" aria-expanded="@if($tab === 'general') true @else false @endif"> General
                            </a>
                        </li>
                        <li class="nav-item @if($tab === 'header') active @endif">
                            <a class="nav-link @if($tab === 'header') active @endif" id="header-tab"
                               data-toggle="tab" href="#header"
                               aria-controls="header" role="tab" aria-selected="false"> Header
                            </a>
                        </li>
                        <li class="nav-item @if($tab === 'footer') active @endif">
                            <a class="nav-link @if($tab === 'footer') active @endif" id="footer-tab"
                               data-toggle="tab" href="#footers"
                               aria-controls="footers" role="tab" aria-selected="false"> Footer
                            </a>
                        </li>
                        <li class="nav-item @if($tab === 'home') active @endif">
                            <a class="nav-link @if($tab === 'home') active @endif" id="home-tab"
                               data-toggle="tab" href="#home"
                               aria-controls="home" role="tab" aria-selected="false">Homepage
                            </a>
                        </li>
                        <li class="nav-item @if($tab === 'order') active @endif">
                            <a class="nav-link @if($tab === 'order') active @endif" id="orders-tab"
                               data-toggle="tab" href="#orders"
                               aria-controls="orders" role="tab" aria-selected="false">
                                Orders
                            </a>
                        </li>
                        <li class="nav-item @if($tab === 'other') active @endif">
                            <a class="nav-link @if($tab === 'other') active @endif" id="others-tab"
                               data-toggle="tab" href="#others"
                               aria-controls="others" role="tab" aria-selected="false">
                                Others
                            </a>
                        </li>
                        <li class="nav-item @if($tab === 'seo') active @endif">
                            <a class="nav-link @if($tab === 'seo') active @endif" id="seo-tab"
                               data-toggle="tab" href="#seo"
                               aria-controls="seo" role="tab" aria-selected="false">
                                SEO
                            </a>
                        </li>
                        <li class="nav-item @if($tab === 'attribute') active @endif">
                            <a class="nav-link @if($tab === 'attribute') active @endif" id="attributes-tab"
                               data-toggle="tab" href="#attributes"
                               aria-controls="attributes" role="tab" aria-selected="false">
                                Language attributes
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane @if($tab === 'general') active @endif" id="general" aria-labelledby="general-tab"
                             role="tabpanel">
                            @include('setting::general')
                        </div>
                        <div class="tab-pane @if($tab === 'header') active @endif" id="header" aria-labelledby="header-tab" role="tabpanel">
                            @include('setting::header')
                        </div>
                        <div class="tab-pane @if($tab === 'footer') active @endif" id="footers" aria-labelledby="footer-tab" role="tabpanel">
                            @include('setting::footer')
                        </div>
                        <div class="tab-pane @if($tab === 'home') active @endif" id="home" aria-labelledby="home-tab" role="tabpanel">
                            @include('setting::home')
                        </div>
                        <div class="tab-pane @if($tab === 'seo') active @endif" id="seo" aria-labelledby="seo-tab" role="tabpanel">
                            @include('setting::seo')
                        </div>
                        <div class="tab-pane @if($tab === 'other') active @endif" id="others" aria-labelledby="others-tab" role="tabpanel">
                            @include('setting::other')
                        </div>
                        <div class="tab-pane @if($tab === 'order') active @endif" id="orders" aria-labelledby="orders-tab" role="tabpanel">
                            @include('setting::order')
                        </div>
                        <div class="tab-pane @if($tab === 'attribute') active @endif" id="attributes" aria-labelledby="attributes-tab" role="tabpanel">
                            @include('setting::attribute')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            let table = $('#attributeTables').DataTable({
                serverSide: true,
                processing:true,
                ajax: {
                    url: '{{ route('setting.attribute.index') }}',
                    data: function (data) {
                    }
                },
                "bAutoWidth": false,
                "sPageButtonActive": "active",
                dom: 'lr<"toolbar">tip',
                "lengthChange": true,
                lengthMenu: [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
                "pageLength": 25,
                "bFilter": true,
                "bInfo": true,
                "searching": true,
                "order": [[0, "desc"]],
                columns: [
                    {"data": 'key'},
                    {"data": 'lang'},
                    {"data": 'value'},
                    {"data": 'actions'}
                ]
            });
            $('#settingAttributeForm').submit(function (e) {
                let url = $(this).attr('action');
                let data = $(this).serialize();
                let form = $(this);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (response, textStatus, xhr) {
                        table.draw();
                        $(form).trigger("reset");
                        Toast.fire({
                            icon: response.status ? 'success' : 'error',
                            title: response.message
                        });
                    }
                })
                return false;
            });

            $('#attributeKey').select2({
                width: "100%",
                allowClear: true,
                placeholder: "Select key",
                delay: 250,
                ajax: {
                    url: '{{ route('setting.suggestion') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            term: params.term,
                            lang: $('#attributeLang').val()
                        }
                    },
                    results: function (data, page) {
                        return {results: data.data};
                    }
                }
            });

            $('table').on('click', '.deleteAttribute', function () {
                let action = $(this).data('action');
                $.ajax({
                    type: "DELETE",
                    url: action,
                    data: null,
                    success: function (response, status, ok) {
                        table.draw();
                        Toast.fire({
                            icon: response.status ? 'success' : 'error',
                            title: response.message
                        });
                    }
                })
            });
        });
    </script>
@endsection
