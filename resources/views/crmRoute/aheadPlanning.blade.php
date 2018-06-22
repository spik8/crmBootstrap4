{{--/*--}}
{{--*@category: CRM,--}}
{{--*@info: This view allows user to edit given hotel (DB table: "hotels"),--}}
{{--*@controller: CrmRouteController,--}}
{{--*@methods: hotelGet, hotelPost--}}
{{--*/--}}

@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('/css/fixedColumns.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('/css/fixedHeader.dataTables.min.css')}}" rel="stylesheet" >

@endsection
@section('content')

    <style>

        #float {
            position: fixed;
            top: 3em;
            right: 2em;
            z-index: 100;
        }
        .heading-container {
            text-align: center;
            font-size: 2em;
            margin: 1em;
            font-weight: bold;
            box-shadow: 0 1px 15px 1px rgba(39,39,39,.1);
            padding-top: 1em;
            padding-bottom: 1em;
        }

        .form-container {
            box-shadow: 0 1px 15px 1px rgba(39,39,39,.1);
            padding-top: 1em;
            padding-bottom: 1em;
            margin: 1em;
        }
    </style>

{{--Header page --}}
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Edytuj Hotel</div>
        </div>
    </div>
</div>


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">

                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="year">Rok</label>
                                <select id="year" class="form-control" multiple="multiple">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="weeks">Tygodnie</label>
                                <select id="weeks" class="form-control" multiple="multiple">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="typ" style="display: block;">Typ</label>
                                <select id="typ" multiple="multiple">
                                    <option value="1">Wysyłka</option>
                                    <option value="2">Badania</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 buttonSection">

                        </div>
                    </div>
                    <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th>Tydzien</th>
                            <th>Dzień</th>
                            <th>Data</th>
                        @foreach($departmentInfo as $item)
                                <th>{{$item->name2.' '.$item->name}}</th>
                        @endforeach
                            <th>Suma</th>
                            <th>Podział</th>
                        {{--@foreach($departmentInfo as $item)--}}
                            {{--<th>CEL {{$item->name2.' '.$item->name}}</th>--}}
                        {{--@endforeach--}}
                        </tr>
                        </thead>
                        <tbody>
                        {{--@foreach($departmentInfo as $item)--}}
                        {{--<tr>--}}
                            {{--<td>1</td>--}}
                            {{--<td>2</td>--}}
                            {{--<td>3</td>--}}
                            {{--@foreach($departmentInfo as $item)--}}
                                {{--<td>1123123123123123</td>--}}
                            {{--@endforeach--}}
                            {{--<td>5</td>--}}
                            {{--<td>6</td>--}}
                            {{--@foreach($departmentInfo as $item)--}}
                                {{--<td>1123123123123123</td>--}}
                            {{--@endforeach--}}
                        {{--</tr>--}}
                        {{--@endforeach--}}
                        {{--@foreach($departmentInfo as $item)--}}
                            {{--<tr>--}}
                                {{--<td>1</td>--}}
                                {{--<td>2</td>--}}
                                {{--<td>3</td>--}}
                                {{--@foreach($departmentInfo as $item)--}}
                                    {{--<td>1123123123123123</td>--}}
                                {{--@endforeach--}}
                                {{--<td>5</td>--}}
                                {{--<td>6</td>--}}
                                {{--@foreach($departmentInfo as $item)--}}
                                    {{--<td>1123123123123123</td>--}}
                                {{--@endforeach--}}
                            {{--</tr>--}}
                        {{--@endforeach--}}
                        </tbody>
                    </table>
                    <div class="row">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ asset('/js/fixedColumns.dataTables.min.js')}}"></script>
    <script src="{{ asset('/js/dataTables.fixedHeader.min.js')}}"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function(mainEvent) {

            /********** GLOBAL VARIABLES ***********/
            let selectedYears = ["0"]; //this array collect selected by user years
            let selectedWeeks = ["0"]; //this array collect selected by user weeks
            /*******END OF GLOBAL VARIABLES*********/




            /*********************DataTable FUNCTUONS****************************/

            table = $('#datatable').DataTable({
                serverSide: true,
                scrollY: '60vh',
                scrollX:true,
                scrollCollapse: true,
                paging:false,
                fixedColumns: true,
                fixedColumns:{
                    leftColumns: 3
                },
                "ajax": {
                    'url': "{{ route('api.getaHeadPlanningInfo') }}",
                    'type': 'POST',
                    'data': function (d) {
                        d.startDate = $('#date_start').val();
                        d.stopDate = $("#date_stop").val();
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },"columns":[
                    {"data":"numberOfWeek"},
                    {"data":"dayName"},
                    {"data":"day"},
                    {"data":"Lublin"},
                    {"data":"Lublin"},
                    {"data":"Radom"},
                    {"data":"Radom"},
                    {"data":"numberOfWeek"},
                    {"data":"numberOfWeek"},
                    {"data":"numberOfWeek"},
                    {"data":"numberOfWeek"},
                    {"data":"numberOfWeek"},
                    {"data":"numberOfWeek"},
                    {"data":"numberOfWeek"},
                    {"data":"numberOfWeek"},
                    {"data":"numberOfWeek"},
                    {"data":"numberOfWeek"},
                    {"data":"numberOfWeek"},
                    {"data":"numberOfWeek"},
                    {"data":"numberOfWeek"},

                ]
            });


            /*********************EVENT LISTENERS FUNCTIONS****************************/

            /**
             * This event listener change elements of array selected Years while user unselects some year
             */
            $('#year').on('select2:unselect', function(e) {
                if($('#year').val() != null) {
                    let yearArr = $('#year').val();
                    selectedYears = yearArr;
                }
                else {
                    selectedYears = ["0"];
                }
                table.ajax.reload();
            });

            /**
             * This event listener change elements of array selecteWeeks while user selects another week
             */
            $('#weeks').on('select2:select', function(e) {
                let weeksArr = $('#weeks').val();
                if(weeksArr.length > 0) {
                    selectedWeeks = weeksArr;
                }
                else {
                    selectedWeeks = ["0"];
                }
                table.ajax.reload();
            });

            /**
             * This event listener change elements of array selectedWeeks while user unselects any week.
             */
            $("#weeks").on('select2:unselect', function(e) {
                if($('#weeks').val() != null) {
                    let weeksArr = $('#weeks').val();
                    selectedWeeks = weeksArr;
                }
                else {
                    selectedWeeks = ['0'];
                }
                table.ajax.reload();
            });

            /**
             * This event listener change elements of array selectedTypes while user selects any type
             */
            $('#typ').on('select2:select', function(e) {
                let types = $('#typ').val();
                if(types.length > 0) {
                    selectedTypes = types;
                }
                else {
                    selectedTypes = ['0'];
                }
                table.ajax.reload();
            });

            /**
             * This event listener change elements of array selectedTypes while user unselects any type
             */
            $('#typ').on('select2:unselect', function(e) {
                if($('#typ').val() != null) {
                    let types = $('#typ').val();
                    selectedTypes = types;
                }
                else {
                    selectedTypes = ['0'];
                }
                table.ajax.reload();
            });
            /*********************END EVENT LISTENERS FUNCTIONS****************************/

            /**
             * This function appends week numbers to week select element and years to year select element
             * IIFE function, execute after page is loaded automaticaly
             */
            (function appendWeeksAndYears() {
                const maxWeekInYear = {{$lastWeek}}; //number of last week in current year
                const weekSelect = document.querySelector('#weeks');
                const yearSelect = document.querySelector('#year');
                const baseYear = '2017';
                const currentYear = {{$currentYear}};
                const currentWeek = {{$currentWeek}};

                for(let j = baseYear; j <= currentYear + 1; j++) {
                    const opt = document.createElement('option');
                    opt.value = j;
                    opt.textContent = j;
                    if(j == currentYear) {
                        opt.setAttribute('selected', 'selected');
                        selectedYears = [j];
                    }
                    yearSelect.appendChild(opt);
                }

                for(let i = 1; i <= maxWeekInYear + 1; i++) {
                    const opt = document.createElement('option');
                    opt.value = i;
                    opt.textContent = i;
                    if(i == currentWeek) {
                        opt.setAttribute('selected', 'selected');
                        selectedWeeks = [i];
                    }
                    weekSelect.appendChild(opt);
                }
            })();
            /*Activation select2 framework*/
            (function initial() {
                $('#weeks').select2();
                $('#year').select2();
                $('#typ').select2();
            })();
        });
    </script>
@endsection