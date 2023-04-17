@extends('layouts.app')
@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{$module[0]->group_module_name}}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{$module[0]->module_name}}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary" id="btnAdd" onclick="AddNew()"><i class="bx bx-plus"></i>Add</button>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered table-sm">
                <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-left">Name</th>
                    <th class="text-left">Route</th>
                    <th class="text-center">Icon</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody id="bodyGroupModule"></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="frmAddNew" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Group Module</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <input type="hidden" id="txtId" value="0" data-required="0">
                                <label>Name <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" id="txtName" data-required="1">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Route <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" id="txtRoute" data-required="1">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Icon <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" id="txtIcon" data-required="1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="Save()"><i class="bx bxs-save"></i> Save</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bx bx-x"></i> Close</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function (){
            LoadData();
        })
        function LoadData(){
            $("#bodyGroupModule").html("");
            $.ajax({
                type:'GET',
                url:"{{ route('group_modules.GetData') }}",
                data:{},
                success:function(data){
                    // console.log(data);
                    var item =data;
                    for (var i = 0; i < item.length; i++) {
                        var btnEdit ='<span class="text-primary" style="cursor: pointer;font-size: 24px" title="Edit" onclick="actionEdit('+item[i].id+',\''+item[i].name+'\',\''+item[i].icon+'\',\''+item[i].route+'\')"><i class="bx bx-edit"></i> </span>';
                        var btnDelete ='<span class="text-danger" style="cursor: pointer;font-size: 24px" title="Delete" onclick="actionDelete('+item[i].id+')"><i class="bx bx-trash"></i></span>';

                        $("#bodyGroupModule").append('<tr>'+
                            '<td class="text-center">'+(i+1)+'</td>'+
                            '<td class="text-left">'+item[i].name+'</td>'+
                            '<td class="text-left">'+item[i].route+'</td>'+
                            '<td class="text-center">'+item[i].icon+'</td>'+
                            '<td class="text-center">'+btnEdit+btnDelete+'</td>'+
                            '</tr>');
                    }
                }
            });
        }
        function clearForm(){
            $('#frmAddNew input').each(function(){
                $(this).val('');
            });
            $("#txtId").val(0);
        }
        function AddNew(){
            clearForm();
            $("#frmAddNew").modal("show");
            $('#frmAddNew').on('shown.bs.modal', function () {
                $('#txtName').focus();
            })
        }
        function actionEdit(Id,Name,Icon,Route){
            clearForm();
            $("#txtId").val(Id);
            $("#txtName").val(Name);
            $("#txtIcon").val(Icon);
            $("#txtRoute").val(Route);
            $("#frmAddNew").modal("show");
            $('#frmAddNew').on('shown.bs.modal', function () {
                $('#txtName').focus();
            })
        }
        function Save(){
            var Id = $("#txtId").val();
            var Name = $("#txtName").val();
            var Route = $("#txtRoute").val();
            var Icon = $("#txtIcon").val();
            if(Name == ""){
                MSG.Validation("Please input name !!!");
            }
            else if(Route == ""){
                MSG.Validation("Please input route");
            }
            else if(Icon == ""){
                MSG.Validation("Please input icon !!!");
            }
            else{
                $.ajax({
                    type:'POST',
                    url:"{{ route('group_modules.Save') }}",
                    data:{
                        id: Id,
                        name: Name,
                        route: Route,
                        icon: Icon
                    },
                    success:function(result){
                        // console.log(result);
                        if(result.code == 0){
                            MSG.Success();
                            $("#frmAddNew").modal('hide');
                            LoadData();
                        }
                    }
                });
            }
        }

        function actionDelete(_id){
            Swal.fire({
            title: 'Delete',
            text: "Are you sure to delete?",
            type: 'question',
            showCancelButton: true,
            cancelButtonText: 'No',
            confirmButtonText: 'Yes',
            confirmButtonColor: "#58db83",
            cancelButtonColor: "#ec536c",
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        async: false,
                        dataType: "json",
                        type: "POST",
                        url:"{{ route('group_modules.Delete') }}",
                        data: {
                            id: _id
                        },
                        success: function (result) {
                            if(result.code ==0){
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Data was deleted.',
                                    type: 'success'
                                })
                                LoadData();
                            }
                            else{
                                MSG.Error(result.message);
                            }
                        },
                        error: function (e) {
                            MSG.Error(e.message);
                        }
                    });

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {

                }
            });
        }
    </script>
@endsection
