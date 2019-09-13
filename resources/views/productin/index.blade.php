@extends('layouts.master')
@section('title')
    <title>Management Product Ins</title>
@endsection
@push('after-css')
  <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{ asset('bower_components/sweetalert/sweetalert2.min.css')}}"/>
@endpush
@section('content')
<div class="content-wrapper">
    <section class="content-header">
    <h1>
        Manage Product Ins
    </h1>

        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manage Product Ins</li>
        </ol>
        
    </section>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <a onclick="addModal()"class="btn btn-primary btn-sm">
                        <i class="fa fa-plus fa-sm"></i> Add Product In</a>
                    </div>
                    <div class="box-body">
                        <table id="DTproductin" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Product In</th>
                                    <th>Supplier</th>
                                    <th>Quantity</th>
                                    <th>Date</th>
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
@include('productin.form')
@endsection
@push('js')
    <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('bower_components/sweetalert/sweetalert2.min.js')}}"></script>
    <script>
        // DT customers
        $(function () {
            $('#DTproductin').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                responsive: true,
                ajax: "{{ route('productins.data') }}",
                columns: [
                    { data: 'product.name', name: 'product.name' },
                    { data: 'supplier.name', name: 'supplier.name' },
                    { data: 'qty', name: 'qty' },
                    { data: 'date', name: 'date' },
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
            $(".error-productin").css('display','none');
            $(`#product option[value=""`).attr("selected", "selected");
            $(`#supplier option[value=""`).attr("selected", "selected");
            $('.modal-title').text('Add Product In');
            $('.button').text('Save Changes');
        }

        // Edit Modal
        function editModal(id) {
            save_method = "edit";
            $('input[name=_method]').val('PATCH');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $(".error-productin").css('display','none');
            $.ajax({
                url: "{{ url('/productins') . '/' }}" + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Product In');
                    $('.button').text('Update Changes');
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $(`#product option[value="${data.product_id}"]`).attr("selected", "selected");
                    $(`#supplier option[value="${data.supplier_id}"]`).attr("selected", "selected");
                    $('#quantity').val(data.qty);
                    $('#date').val(data.date);
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
                    if (save_method == 'add') url = "{{ route('productins.store') }}";
                    else url = "{{ url('productins') . '/' }}" + id;
                    $.ajax({
                        url : url,
                        type : "POST",
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            if($.isEmptyObject(data.errors)){
                                $('#modal-form').modal('hide');
                                $('#DTproductin').DataTable().ajax.reload();
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
                $(".error-productin").find("ul").html('');
                $(".error-productin").css('display','block');
                $.each( msg, function( key, value ) {
                    $(".error-productin").find("ul").append('<li>'+value+'</li>');
                });
            }
        });

        // Delete 
        function deleteProductIn(id){
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
                        url : "{{ url('/productins') }}" + '/' + id,
                        type : "POST",
                        data : {'_method' : 'DELETE', '_token' : csrf_token},
                    })
                    .done(function(data){
                        Swal.fire(
                            'Success!',
                            'Product In has been deleted!',
                            'success'
                            )
                        $('#DTproductin').DataTable().ajax.reload();
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