@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Testy / Wszystkie testy</div>
        </div>
    </div>
<div>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#menu1">Do oceny</a></li>
    <li><a data-toggle="tab" href="#menu2">Ocenione</a></li>
</ul>

<div class="well well-back">
<div class="tab-content">
    <div id="menu1" class="tab-pane fade in active">
        <div class="table-responsive" style="margin-top: 30px;">
                <table id="to_check" class="table table-striped table-bordered thead-inverse" cellspacing="0" width="100%" >
                    <thead>
                        <tr>
                            <td>Nazwa testu</td>
                            <td style="width: 15%">Data</td>
                            <td style="width: 15%">Pracownik</td>
                            <td style="width: 10%">Sprawdź</td>
                        </tr>
                    </thead>
                    <tbody>
    
                    </tbody>
                </table>
            </div>
    </div>
    <div id="menu2" class="tab-pane fade">
        <div class="table-responsive" style="margin-top: 30px;">
            <table id="checked_tests" class="table table-striped table-bordered thead-inverse" cellspacing="0" width="100%" >
                <thead>
                    <tr>
                        <td>Nazwa testu</td>
                        <td style="width: 15%">Data</td>
                        <td style="width: 15%">Pracownik</td>
                        <td style="width: 10%">Rezultat</td>
                        <td style="width: 10%">Szczegóły</td>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
</div>


@endsection

@section('script')
<script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('/js/buttons.bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/dataTables.select.min.js')}}"></script>
<script>

table = $('#checked_tests').DataTable({
    "autoWidth": true,
    "processing": true,
    "serverSide": true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },
    "drawCallback": function (settings) {
    },
    "ajax": {
        'url': "{{ route('api.datatableShowCheckedTests') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        'data': function (d) {
        }
    }, "columns": [
        {"data": 'test_name'},
        {"data": function (data, type, dataToSet) {
            var str = data.test_start;
            return str.substr(0, 10);
        },"data": 'test_start'},
        {"data": function (data, type, dataToSet) {
            var str = data.last_name + " " + data.first_name;
            return str;
        },},
        {"data": function (data, type, dataToSet) {
            var str = data.test_result + "/" + data.count_questions;
            return str;
        },},
        {"data": function (data, type, dataToSet) {
            return "<a class='btn btn-default' href='{{ URL::to('/test_result') }}/" + data.test_id + "'> Pokaż szczegóły</a>";
        },},
    ],

});

table = $('#to_check').DataTable({
    "autoWidth": true,
    "processing": true,
    "serverSide": true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },
    "drawCallback": function (settings) {
    },
    "ajax": {
        'url': "{{ route('api.datatableShowUncheckedTests') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        'data': function (d) {
        }
    }, "columns": [
        {"data": 'test_name'},
        {"data": function (data, type, dataToSet) {
            var str = data.test_start;
            return str.substr(0, 10);
        },"data": 'test_start'},
        {"data": function (data, type, dataToSet) {
            var str = data.last_name + " " + data.first_name;
            return str;
        },},
        {"data": function (data, type, dataToSet) {
            return "<a class='btn btn-default' href='{{ URL::to('/check_test') }}/" + data.test_id + "'> Sprawdź test</a>";
        },},
    ],

});
</script>
@endsection
