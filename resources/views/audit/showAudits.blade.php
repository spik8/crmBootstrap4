@extends('layouts.main')
@section('content')
    {{--*******************************************--}}
    {{--THIS PAGE DISPLAYS TABLE WITH FILLED AUDITS--}}
    {{--*******************************************--}}

    {{--<style>--}}
        {{--td:nth-of-type(5)::after {--}}
            {{--content: '%';--}}
        {{--}--}}
    {{--</style>--}}
    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <div class="alert gray-nav">Tabela wykonanych audytów</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Lista wykonanych audytów
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-info">Filtrując tabelę po oddziałach można wybrać bezpośrednio oddział lub dyrektora, jeżeli zostanie wybrany dyrektor, zostaną wyfiltrowane rekordy z oddziałami mu podległymi</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Zakres od:</label>
                                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                        <input class="form-control" id="date_start" name="date_start" type="text" value="{{date('Y-m-01')}}" >
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Zakres do:</label>
                                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                        <input class="form-control" id="date_stop" name="date_stop" type="text" value="{{date('Y-m-d')}}" >
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department">Oddział</label>
                                    <select name="department" id="department" class="form-control">
                                        <option value="0">Wybierz</option>
                                        <optgroup label="departamenty">
                                        @foreach($departments as $department)
                                        <option value="{{$department->id}}" data-type="1">{{$department->departments->name}} {{$department->department_type->name}}</option>
                                        @endforeach
                                        </optgroup>
                                        <optgroup label="dyrektorzy">
                                            @foreach($directors as $director)
                                                <option value="{{$director->id}}" data-type="2">{{$director->first_name}} {{$director->last_name}}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Typ</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="0">Wybierz</option>
                                        <option value="1">Trener</option>
                                        <option value="2">Hr-owiec</option>
                                        <option value="3">Oddział</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="start_stop">
                                    <div class="panel-body table-responsive">
                                        @if(Session::has('adnotation'))
                                                    <div class="alert alert-success">{{Session::get('adnotation') }}</div>
                                            @php
                                                Session::forget('adnotation');
                                            @endphp
                                        @endif
                                        <table id="datatable" class="thead-inverse table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th class="search-input-text" data-column="1">Wypełniającey</th>
                                                <th>Oceniany</th>
                                                <th>Typ</th>
                                                <th>Department</th>
                                                <th>Data</th>
                                                <th class="score">Wynik</th>
                                                <th>Podgląd/Edycja</th>
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
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
        $(document).ready( function () {
            var selectedDepartment = document.getElementById('department');
            var selectedType = document.getElementById('type');
            var departmentValue = null;
            var directorId = null;
            var type = null;

            //ajax reponsible for receiving and displaying data through datatable
            table = $('#datatable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "drawCallback": function( settings ) {
                },
                "ajax": {
                    'url': "{{ route('api.auditTable') }}",
                    'type': 'POST',
                    'data': function (d) {
                        d.date_start = $('#date_start').val();
                        d.date_stop =  $('#date_stop').val();
                        d.department = departmentValue;
                        d.director = directorId;
                        d.type = type;
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },"columns":[
                    {"data":function (data, type, dataToSet) {
                            return data.user_first_name+' '+data.user_last_name;
                        },"name":"users.last_name"
                    },
                    {"data":function (data, type, dataToSet) {
                            if(data.user_type != 3) {
                                return data.trainer_first_name+' '+data.trainer_last_name;
                            }
                            else {
                                return data.department_name + ' ' + data.department_type;
                            }
                        },"name":"trainer.last_name"
                    },
                    {"data":function (data, type, dataToSet) {
                        if(data.user_type == 1) {
                            return "Trener";
                        }
                        else if(data.user_type == 2) {
                            return "Hr";
                        }
                        else {
                            return 'Oddział';
                        }
                        },"name":"users.last_name"
                    },
                    {"data":function (data, type, dataToSet) {
                            return data.department_name+' '+data.department_type;
                        },"name":"departments.name"
                    },
                    {"data": "date_audit"},
                    {"data":function (data, type, dataToSet) {
                        if(data.score != null && data.score != 'null' )
                            return data.score + ' ' + '%';
                        else return "0 %"
                        },"name":"score"
                    },
                    {"data":function (data, type, dataToSet) {
                            return '<a href="{{URL::to("audit")}}/' + data.audit_id + '">Link</a>';
                        },"orderable": false, "searchable": false
                    }
                ]
            });

            $('.search-input-text').on( 'change', function () {   // for text boxes
                var i =$(this).attr('data-column');  // getting column index
                var v = $(this).text();  // getting search input value
                table.columns(i).search(v).draw();
            } );

            /**
             * This event listener filter table by selected date
             */
            $('#date_start, #date_stop').on('change',function(e) {
                table.ajax.reload();
            });

            /**
             * This event listener filter table by selected department
             */
            $('#department').on('change', function(e) {
                if(selectedDepartment.options[selectedDepartment.selectedIndex].dataset.type == 1) {
                    departmentValue = selectedDepartment.options[selectedDepartment.selectedIndex].value;
                }
                if(selectedDepartment.options[selectedDepartment.selectedIndex].dataset.type == 2) {
                    directorId = selectedDepartment.options[selectedDepartment.selectedIndex].value;
                }

                table.ajax.reload();
                departmentValue = null;
                directorId = null;

            });

            /**
             * This event listener filter table by selected type
             */
            $('#type').on('change', function(e) {
                if(selectedType.options[selectedType.selectedIndex].value != 0) {
                    type = selectedType.options[selectedType.selectedIndex].value;
                }

                table.ajax.reload();
                type = null;
            });


            $('.form_date').datetimepicker({
                language:  'pl',
                autoclose: 1,
                minView : 2,
                pickTime: false
            });
        });
    </script>
@endsection
