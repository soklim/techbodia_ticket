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
    <hr />
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-md-2">
            <label>Role</label>
            <select class="form-select" id="role_id_filter">
                <option value="0">-- select --</option>
                @foreach($role as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Ticket Type</label>
            <select class="form-select" id="ticket_type_id_filter">
                <option value="0">-- select --</option>
                @foreach($ticket_type as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <br>
            <button type="button" class="btn btn-primary" id="btnSearch" onclick="LoadData()"><i class="bx bx-search-alt"></i>Search</button>

            @if($permission->a_create == 1)
            <button type="button" class="btn btn-primary" id="btnAdd" onclick="AddNew()"><i class="bx bx-plus"></i>Add</button>
            @endif
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Ticket Type</th>
                        <th>Role</th>
                        <th>Can Create</th>
                        <th>Can Resolve</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="bodyTicketType"></tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="frmAddNew" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{$module[0]->module_name}}</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Ticket Type <span class="text-danger">(*)</span></label>
                                <select class="form-select" id="ticket_type_id">
                                    <option value="0">-- select --</option>
                                    @foreach($ticket_type as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Role <span class="text-danger">(*)</span></label>
                                <select class="form-select" id="role_id">
                                    <option value="0">-- select --</option>
                                    @foreach($role as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="can_create">
                                <label class="form-check-label" for="can_create">Can Create</label>
                            </div>                          
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="can_resolve">
                                <label class="form-check-label" for="can_resolve">Can Resolve</label>
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
        var GID=0;
        $(document).ready(function (){
            LoadData();
        })
        function LoadData(){
            var role_id = $("#role_id_filter").val();
            var ticket_type_id = $("#ticket_type_id_filter").val();

            $("#bodyTicketType").html("");
            $.ajax({
                type:'GET',
                url:"{{ route('assign_ticket_type.GetData') }}",
                data:{
                    role_id: role_id,
                    ticket_type_id: ticket_type_id
                },
                success:function(data){
                    // console.log(data);
                    var item =data;
                    for (var i = 0; i < item.length; i++) {
                        var btnEdit="";
                        var btnDelete="";

                        @if($permission->a_update)
                            btnEdit ='<span class="text-primary" style="cursor: pointer;font-size: 24px" title="Edit" onclick="actionEdit('+item[i].id+','+item[i].ticket_type_id+','+item[i].role_id+','+item[i].can_create+','+item[i].can_resolve+')"><i class="bx bx-edit"></i> </span>';
                        @endif
                        @if($permission->a_delete)
                            btnDelete ='<span class="text-danger" style="cursor: pointer;font-size: 24px" title="Delete" onclick="actionDelete('+item[i].id+')"><i class="bx bx-trash"></i></span>';

                        @endif                        

                        var can_create = '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="can_create" disabled></div>';
                        var can_resolve = '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="can_resolve" disabled></div>';
                        
                        if(item[i].can_create == 1){
                            can_create = '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked name="can_create" disabled></div>';
                        }
                        if(item[i].can_resolve == 1){
                            can_resolve = '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked name="can_resolve" disabled></div>';
                        }

                        $("#bodyTicketType").append('<tr>'+
                            '<td class="text-center">'+(i+1)+'</td>'+
                            '<td class="text-left">'+item[i].ticket_type_name+'</td>'+
                            '<td class="text-left">'+item[i].role_name+'</td>'+
                            '<td class="text-center">'+can_create+'</td>'+
                            '<td class="text-center">'+can_resolve+'</td>'+
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
            $('#frmAddNew select').each(function(){
                $(this).val(0).trigger("change");
            });
            GID=0;
            $("#can_create").prop("checked",false);
            $("#can_resolve").prop("checked",false);

        }
        function AddNew(){
            clearForm();
            $("#frmAddNew").modal("show");         
        }
        function actionEdit(Id,ticket_type_id,role_id,can_create,can_resolve){
            clearForm();
            GID = Id;
            $("#ticket_type_id").val(ticket_type_id);
            $("#role_id").val(role_id);
            if(can_create == 1){
                $("#can_create").prop("checked",true);
            }
            if(can_resolve == 1){
                $("#can_resolve").prop("checked",true);
            }
            $("#frmAddNew").modal("show");
           
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
                        url:"{{ route('assign_ticket_type.Delete') }}",
                        data: {
                            id: _id
                        },
                        success: function (result) {
                            if(result.code ==0){
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Data was deleted from system.',
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
        function Save(){
            var Id = GID;
            var ticket_type_id = $("#ticket_type_id").val();
            var role_id = $("#role_id").val();
            var chb_can_create = $('#can_create').is(':checked'); 
            var can_create=0;
            if(chb_can_create == true){
                can_create=1;
            }
            var chb_can_resolve = $('#can_resolve').is(':checked'); 
            var can_resolve=0;
            if(chb_can_resolve == true){
                can_resolve=1;
            }

            if(ticket_type_id == 0){
                MSG.Validation("Please select ticket type !!!");
            }
            else if(role_id == 0){
                MSG.Validation("Please select role !!!");
            }
            else{
                $.ajax({
                    type:'POST',
                    url:"{{ route('assign_ticket_type.Save') }}",
                    data:{
                        id: Id,
                        ticket_type_id: ticket_type_id,
                        role_id: role_id,
                        can_create: can_create,
                        can_resolve: can_resolve,

                    },
                    success:function(result){
                        // console.log(result);
                        if(result.code == 0){
                            MSG.Success();
                            $("#frmAddNew").modal('hide');
                            LoadData();
                        }
                        else{
                            MSG.Error(result.message);
                        }
                    }
                });
            }
        }

    </script>
@endsection
