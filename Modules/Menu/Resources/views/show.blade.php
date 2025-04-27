@extends('metis::layouts.master')

@section('header')
    <style type="text/css">

        ol {
            padding-left: 25px;
        }

        ol.sortable, ol.sortable ol {
            list-style-type: none;
        }

        .sortable li > div {
            /*border: 1px solid #d4d4d4;*/
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            cursor: move;
            margin-bottom: 10px;
            padding: 10px;
        }

        .sortable li .menuUpdateForm {
            padding: 10px;
        }

        .sortable li.mjs-nestedSortable-expanded {
            background-color: #f6f6f6;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border: 1px solid #eeeeee;
        }

        .sortable li.mjs-nestedSortable-expanded ol {
            padding: 5px 5px 5px 25px;
        }

        li.mjs-nestedSortable-collapsed.mjs-nestedSortable-hovering div {
            border-color: #999;
        }

        .disclose, .expandEditor {
            cursor: pointer;
            width: 20px;
            display: none;
        }

        .sortable li.mjs-nestedSortable-collapsed > ol {
            display: none;
        }

        .sortable li.mjs-nestedSortable-branch > div > .disclose {
            display: inline-block;
        }

        .sortable span.ui-icon {
            display: inline-block;
            margin: 0 5px;
            padding: 0 5px;
        }

        .menuDiv {
            background: #EBEBEB;
        }

        .menuEdit {
            background: #FFF;
        }

        .itemTitle {
            vertical-align: middle;
            cursor: pointer;
        }

        .deleteMenu {
            float: right;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css"/>
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3>{{ $title ?? 'Menu'}}</h3>
                <div>
                    <ul class="nav nav-tabs menuTab" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#home" aria-controls="home" role="tab"
                               data-toggle="tab" aria-expanded="true">Menus</a>
                        </li>
                        @if(\App\Helpers\CommonHelper::hasPermission(['menu-update', 'menu-action']))
                            <li role="presentation">
                                <a href="#attributes" aria-controls="attributes"
                                   role="tab" data-toggle="tab">Translations</a>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in"
                             id="home">
                            <div class="row">
                                <div class="col-sm-4 col-12">
                                    <div class="card">
                                        @if(\App\Helpers\CommonHelper::hasPermission(['menu-create', 'menu-update']))
                                            <div class="panel-group" id="accordion"
                                                 role="tablist"
                                                 aria-multiselectable="true">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab"
                                                         id="headingOne">
                                                        <h4 class="panel-title">
                                                            <a role="button"
                                                               data-toggle="collapse"
                                                               data-parent="#accordion"
                                                               href="#collapseOne"
                                                               aria-expanded="true"
                                                               aria-controls="collapseOne">
                                                                Custom menu
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseOne"
                                                         class="panel-collapse collapse in"
                                                         role="tabpanel"
                                                         aria-labelledby="headingOne">
                                                        <div class="panel-body">
                                                            <form
                                                                action="{{ route('menu.item.store') }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="card-body">
                                                                    <input type="hidden"
                                                                           name="menu_id"
                                                                           value="{{ $menu->id }}">
                                                                    <input type="hidden"
                                                                           name="type"
                                                                           value="custom">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="input-group">
                                                                                <span class="input-group-addon"
                                                                                      id="basic-addon3">Name (*)</span>
                                                                            <input
                                                                                type="text"
                                                                                name="name"
                                                                                value="{{ old('name') }}"
                                                                                class="form-control"
                                                                                id="name"
                                                                                placeholder="name">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="input-group">
                                                                                <span class="input-group-addon"
                                                                                      id="basic-addon3">Description (*)</span>
                                                                            <input
                                                                                type="text"
                                                                                name="description"
                                                                                value="{{ old('description') }}"
                                                                                class="form-control"
                                                                                id="description"
                                                                                placeholder="Description">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="input-group">
                                                                                <span class="input-group-addon"
                                                                                      id="basic-addon3">URL (*)</span>
                                                                            <input
                                                                                type="text"
                                                                                name="menu_url"
                                                                                value="{{ old('menu_url') }}"
                                                                                class="form-control"
                                                                                id="url"
                                                                                placeholder="url">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="input-group">
                                                                                <span class="input-group-addon"
                                                                                      id="basic-addon3">Menu Class</span>
                                                                            <input
                                                                                type="text"
                                                                                name="css_class"
                                                                                value="{{ old('css_class') }}"
                                                                                class="form-control"
                                                                                id="css_class"
                                                                                placeholder="Class name">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="input-group">
                                                                                <span class="input-group-addon"
                                                                                      id="basic-addon3">Menu Icon</span>
                                                                            <select
                                                                                name="icon_class"
                                                                                class="form-control menuIcon"
                                                                                id="menuIcon"></select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-footer">
                                                                    <button
                                                                        class="btn btn-primary">
                                                                        Add to menu
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab"
                                                         id="headingTwo">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed"
                                                               role="button"
                                                               data-toggle="collapse"
                                                               data-parent="#accordion"
                                                               href="#collapseTwo"
                                                               aria-expanded="false"
                                                               aria-controls="collapseTwo">
                                                                Pages
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseTwo"
                                                         class="panel-collapse collapse"
                                                         role="tabpanel"
                                                         aria-labelledby="headingTwo">
                                                        <div class="panel-body">
                                                            <ul class="list-group">
                                                                @foreach(app(\Modules\Page\PageService::class)->all() as $page)
                                                                    <li class="list-group-item">
                                                                        <button
                                                                            class="badge badge-info addToMenu"
                                                                            data-id="{{ $page->id }}"
                                                                            data-menu-type="page">
                                                                            Add
                                                                        </button>
                                                                        {{ $page->title }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab"
                                                         id="headingThree">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed"
                                                               role="button"
                                                               data-toggle="collapse"
                                                               data-parent="#accordion"
                                                               href="#collapseThree"
                                                               aria-expanded="false"
                                                               aria-controls="collapseThree">
                                                                Categories
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseThree"
                                                         class="panel-collapse collapse"
                                                         role="tabpanel"
                                                         aria-labelledby="headingThree">
                                                        <div class="panel-body">
                                                            <ul class="list-group">
                                                                @foreach(app(\Modules\Category\App\Services\CategoryService::class)->all() as $category)
                                                                    <li class="list-group-item">
                                                                        <button
                                                                            class="badge badge-info addToMenu"
                                                                            data-id="{{ $category->id }}"
                                                                            data-menu-type="category">
                                                                            Add
                                                                        </button>
                                                                        {{ $category->name }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab"
                                                         id="headingFour">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed"
                                                               role="button"
                                                               data-toggle="collapse"
                                                               data-parent="#accordion"
                                                               href="#collapseFour"
                                                               aria-expanded="false"
                                                               aria-controls="collapseThree">
                                                                Services
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseFour"
                                                         class="panel-collapse collapse"
                                                         role="tabpanel"
                                                         aria-labelledby="headingFour">
                                                        <div class="panel-body">
                                                            <ul class="list-group">
                                                                @foreach(app(\Modules\ServiceType\Services\ServiceTypes::class)->all() as $service)
                                                                    <li class="list-group-item">
                                                                        <button
                                                                            class="badge badge-info addToMenu"
                                                                            data-id="{{ $service->id }}"
                                                                            data-menu-type="service">
                                                                            Add
                                                                        </button>
                                                                        {{ $service->label }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-8 col-8">
                                    <div id="demo">
                                        @include('menu::sortable')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="attributes">
                            <table class="table table-hover table-bordered"
                                   id="dataTable">
                                <thead>
                                <tr>
                                    <td>Menu</td>
                                    <td>Language</td>
                                    <td>Title</td>
                                    <td>Description</td>
                                    <td class="table-actions"><i class="fa fa-cog"></i></td>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- END #demo -->
            </div>
        </div>
    </div>

    <x-metis::modal.default action="{{ route('menu.attribute.store') }}" title="Add new lang">
        <input type="hidden" name="menu_id" value="{{ $menu->id }}"/>
        <div class="form-group">
            <label>Menu</label>
            <select name="menu_item_id" class="form-control" id="menuItemSelect"></select>
        </div>
        <div class="form-group">
            <label>Language</label>
            <select name="language" class="form-control" id="attributeLanguage">
                <option value="">Select language</option>
                @foreach(app(\Modules\Region\Services\LanguageService::class)->all()->where('code', '!=', 'en') as $language)
                    <option value="{{ $language->code }}">{{ $language->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Name</label>
            <input name="name" class="form-control" id="attributeName">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" id="attributeDescription"></textarea>
        </div>
    </x-metis::modal.default>
@endsection


@section('footer')
    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
    <script type="text/javascript"
            src="/assets/plugins/nestedSortable/jquery.mjs.nestedSortable.js"></script>

    <script>
        jQuery(function ($) {
            let table = $('#dataTable').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '{{ route('menu.attribute.index', ['menu_id' => $menu->id]) }}',
                    data: function (data) {
                        data.menu_id = {{ $menu->id }};
                        data.menu_item_id = $('#filterPanel #menuItemId').val();
                        data.keyword = $('#filterPanel #keywords').val();
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
                    {"data": 'menu_item.name', sortable: false, searchable: false},
                    {"data": 'language'},
                    {"data": 'name'},
                    {"data": 'description', sortable: false, searchable: false},
                    {"data": 'actions', sortable: false, searchable: false}
                ],
                "createdRow": function (row, data, index) {
                    // if ( data[6] == 'Disable' ){
                    //     $(row).addClass('highlightError');
                    // }
                }
            });

            document.querySelector('div#dataTable_wrapper .toolbar').innerHTML = "" +
                "<div class='form-inline' id='filterPanel'>" +
                "<div class='form-group mx-sm-3 mr'>" +
                "<input type='text' class='form-control' id='keywords' placeholder='Search...'>" +
                "</div>" +
                "<div class='form-group mx-sm-3 mr'>" +
                "<select class='form-control' id='menuItemId'></select>" +
                "</div>" +
                "<div class='form-group mx-sm-3 mr'>" +
                "<button class='btn btn-primary' id='addNewAttribute'><i class='fa fa-plus'></i></button>" +
                "</div>" +
                "</div>";

            function initSelect2(elem, url, placeholder) {
                $(elem).select2({
                    allowClear: true,
                    width: "100%",
                    placeholder: placeholder,
                    delay: 250,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        data: function (params) {
                            return {
                                term: params.term
                            }
                        },
                        results: function (data, page) {
                            return {results: data.data};
                        }
                    }
                });
            }

            $('table').on('click', '.editLangAttribute', function () {
                let action = $(this).data('action');
                let data = $(this).data('payload');
                $(modal).find('#menuItemSelect').append(new Option(data.item.name, data.item.id, true, true)).trigger('change');
                $(modal).find('#attributeLanguage option[value="' + data.language + '"]').prop('selected', true);
                $(modal).find('#attributeName').val(data.name);
                $(modal).find('#attributeDescription').val(data.description);
                console.log(data);
                $(modal).modal('show');
            });

            $(modal).on('hidden.bs.modal', function () {
                table.draw();
                $(this).find('form').trigger("reset");
                $(this).find('#menuItemSelect').val(null).trigger('change');
            });

            initSelect2(
                "#filterPanel #menuItemId, #menuItemSelect",
                "{{ route('menu.item.suggestion', $menu->id) }}",
                'Select menu'
            );

            $("#filterPanel input").on("keyup", function () {
                table.draw();
            });
            $("#filterPanel select").on("change", function () {
                table.draw();
            });

            $('#filterPanel #addNewAttribute').on("click", function () {
                $(modal).modal('show');
            });

            function formatSelect2(option) {
                if (!option.id) {
                    return option.text;
                }
                var imageUrl = '/default/menus/' + option.id;
                var optionWithImage = $(
                    '<span><img src="' + imageUrl + '" class="img-flag" /> ' + option.text + '</span>'
                );
                return optionWithImage;
            }

            $(".menuIcon").select2({
                width: "100%",
                placeholder: "Choose icon",
                templateResult: formatSelect2,
                templateSelection: formatSelect2,
                ajax: {
                    url: "{{ route('menu.icon.suggestion') }}",
                    dataType: 'json'
                }
            });
            $('.addToMenu').on("click", function (e) {
                let type = $(this).data('menu-type');
                let id = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "{{ route('menu.item.add', $menu->id) }}",
                    data: {id: id, type: type},
                    success: function (data) {
                        defaultToast(data.status, data.message);
                        if (data.status) {
                            window.location.href = "{{ route('menu.show', $menu->id) }}";
                        }
                    }
                })
            });
            $('.menuUpdateForm').submit(function () {
                let data = $(this).serialize();
                let url = $(this).attr('action');
                console.log(data);
                console.log(url);
                $.ajax({
                    type: "PUT",
                    url: url,
                    data: data,
                    success: function (data) {
                        defaultToast(data.status, data.message);
                    }
                });

                return false;
            });
            var ns = $('ol.sortable').nestedSortable({
                forcePlaceholderSize: true,
                handle: 'div',
                helper: 'clone',
                items: 'li',
                opacity: .6,
                placeholder: 'placeholder',
                revert: 250,
                tabSize: 25,
                tolerance: 'pointer',
                toleranceElement: '> div',
                maxLevels: 2,
                isTree: true,
                expandOnHover: 700,
                startCollapsed: false,
                change: function (e) {
                }
            });

            $('.disclose').on('click', function () {
                $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
                $(this).toggleClass('ui-icon-plusthick').toggleClass('ui-icon-minusthick');
            });

            $('.expandEditor, .itemTitle').click(function () {
                var id = $(this).attr('data-id');
                $('#menuEdit' + id).toggle();
                $(this).toggleClass('ui-icon-triangle-1-n').toggleClass('ui-icon-triangle-1-s');
            });

            $('.deleteMenu').click(function () {
                var id = $(this).attr('data-id');
                $.ajax({
                    type: "DELETE",
                    url: $(this).data('url'),
                    success: function (data) {
                        $('#menuItem_' + id).remove();
                        defaultToast(data.status, data.message);
                    }
                });
            });

            $('#toArray').click(function (e) {
                let sorted = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
                $('#toArrayOutput').text("Saving...");
                $.ajax({
                    type: "POST",
                    data: {sorted: sorted},
                    url: "{{ route('menu.item.save') }}",
                    success: function (data) {
                        $('#toArrayOutput').text(data.message);
                        defaultToast(data.status, data.message);
                    }
                });
            });
        });
    </script>
@endsection
