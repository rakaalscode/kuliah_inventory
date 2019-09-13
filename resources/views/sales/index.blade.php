@extends('layouts.master')
@section('title')
    <title>Management Sales</title>
@endsection
@push('after-css')
  <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{ asset('bower_components/sweetalert/sweetalert2.min.css')}}"/>
@endpush
@section('content')
<div class="content-wrapper">
    <section class="content-header">
    <h1>
        Manage Sales
    </h1>

        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manage Sales</li>
        </ol>
        
    </section>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <a onclick="addModal()"class="btn btn-primary btn-sm">
                        <i class="fa fa-plus fa-sm"></i> Add Sales</a>
                    </div>
                    <div class="box-body">
                        <table id="DTsales" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Telephone</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('sales.form')
@endsection
@push('js')
    <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('bower_components/sweetalert/sweetalert2.min.js')}}"></script>

    <script>
        // DT sales
        $(function () {
            $('#DTsales').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                responsive: true,
                ajax: "{{ route('sales.data') }}",
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'address', name: 'address' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false},
                ],
            })
        })

        // Add Modal
        function addModal() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $(".error-sales").css('display','none');
            $('.modal-title').text('Add Sales');
            $('.button').text('Save Changes');
        }

        // Edit Modal
        function editModal(id) {
            save_method = "edit";
            $('input[name=_method]').val('PATCH');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $(".error-sales").css('display','none');
            $.ajax({
                url: "{{ url('/sales') . '/' }}" + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Sales');
                    $('.button').text('Update Changes');
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                    $('#address').val(data.address);
                },
                error : function() {
                    toastr.error('Data not found', 'Error')
                }
            });
        }

        // Submit 
        $(function(){
            $('#modal-form').on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ route('sales.store') }}";
                    else url = "{{ url('sales') . '/' }}" + id;
                    $.ajax({
                        url : url,
                        type : "POST",
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            if($.isEmptyObject(data.errors)){
                                $('#modal-form').modal('hide');
                                $('#DTsales').DataTable().ajax.reload();
                            }else{
                                printErrorMsg(data.errors);
                            }
                        },
                        error : function(data){
                            $('#modal-form')
                                .on('hidden.bs.modal', function () {
                            });
                        }
                    });
                    return false;
                }
            });

            function printErrorMsg (msg) {
                $(".error-sales").find("ul").html('');
                $(".error-sales").css('display','block');
                $.each( msg, function( key, value ) {
                    $(".error-sales").find("ul").append('<li>'+value+'</li>');
                });
            }
        });

        // Delete 
        function deleteSales(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "This data will be permanently deleted",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete',
                showLoaderOnConfirm: true,
                
            preConfirm: function() {
                return new Promise(function(resolve) {
                    $.ajax({
                        url : "{{ url('/sales') }}" + '/' + id,
                        type : "POST",
                        data : {'_method' : 'DELETE', '_token' : csrf_token},
                    })
                    .done(function(data){
                        Swal.fire(
                            'Success!',
                            'Sales has been deleted!',
                            'success'
                            )
                        $('#DTsales').DataTable().ajax.reload();
                    })
                    .fail(function(){
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'A server error occurred!',
                    })
                    });
                });
            },
            allowOutsideClick: false			  
        });	
        }

    </script>
@endpush