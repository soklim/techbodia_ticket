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
                    <th class="text-center">No</th>
                    <th class="text-left">Ticket Type</th>
                    <th class="text-left">Summary</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Severity</th>
                    <th class="text-center">Priority</th>
                    <th class="text-center">Created_By</th>
                    <th class="text-center">Created_Date</th>
                    <th class="text-center">Resolved_By</th>
                    <th class="text-center">Resolved_Date</th>
                    <th class="text-center">Status Name</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody id="bodyTicket"></tbody>
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
                                    @foreach($ticket_type as $ticket_type)
                                        <option value="{{$ticket_type->type_id}}">{{$ticket_type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Summary <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" id="summary" data-required="1">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Description <span class="text-danger">(*)</span></label>
                                <textarea class="form-control" id="description" data-required="1" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="severity">
                                <label class="form-check-label" for="severity">Severity</label>
                            </div>                          
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="priority">
                                <label class="form-check-label" for="priority">Priority</label>
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
        var roleId = {{$role_id}};
        $(document).ready(function (){
            LoadData();
           
        })
        function LoadData(){
            $("#bodyTicket").html("");
            $.ajax({
                type:'GET',
                url:"{{ route('ticket.GetData') }}",
                data:{},
                success:function(data){
                    // console.log(data);
                    var item =data;
                    for (var i = 0; i < item.length; i++) {                        
                        var btnEdit="";
                        var btnDelete="";
                        var btnResolved = "";

                        if(item[i].status_id == 1){
                            @if($permission->a_update)
                                if(item[i].is_owner ==1){
                                    btnEdit ='<span class="text-primary" style="cursor: pointer;font-size: 24px" title="Edit" onclick="actionEdit('+item[i].id+',\''+item[i].summary+'\',\''+item[i].description+'\','+item[i].ticket_type_id+','+item[i].severity+','+item[i].priority+')"><i class="bx bx-edit"></i> </span>';
                                }
                                if(item[i].can_resolved > 0){
                                    btnResolved ='<span class="text-success" style="cursor: pointer;font-size: 24px" title="Resolve" onclick="actionResolve('+item[i].id+')"><i class="bx bx-message-square-check"></i> </span>';
                                }
                            @endif
                            @if($permission->a_delete)
                                if(item[i].is_owner ==1){
                                btnDelete ='<span class="text-danger" style="cursor: pointer;font-size: 24px" title="Delete" onclick="actionDelete('+item[i].id+')"><i class="bx bx-trash"></i></span>';
                                }
                            @endif
                        }
                        var statusName = "";
                        if(item[i].status_id == 1){
                            statusName ='<span class="badge bg-primary">'+item[i].status_name+'</span>';
                        }
                        else if(item[i].status_id == 2){
                            statusName ='<span class="badge bg-success">'+item[i].status_name+'</span>';
                        }
                        else if(item[i].status_id == 3){
                            statusName ='<span class="badge bg-danger">'+item[i].status_name+'</span>';
                        }
                        else{
                            statusName="";
                        }

                        var severity = '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="severity" disabled></div>';
                        var priority = '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="priority" disabled></div>';
                        if(item[i].severity == 1){
                            severity = '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked name="severity" disabled></div>';
                        }
                        if(item[i].priority == 1){
                            priority = '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked name="priority" disabled></div>';
                        }
                        
                        $("#bodyTicket").append('<tr>'+
                            '<td class="text-center">'+(i+1)+'</td>'+
                            '<td class="text-center">'+item[i].ticket_type_name+'</td>'+
                            '<td class="text-left">'+item[i].summary+'</td>'+
                            '<td class="text-left">'+item[i].description+'</td>'+
                            '<td class="text-center">'+severity+'</td>'+
                            '<td class="text-center">'+priority+'</td>'+
                            '<td class="text-center">'+item[i].created_by+'</td>'+
                            '<td class="text-center">'+item[i].created_date+'</td>'+
                            '<td class="text-center">'+item[i].resolved_by+'</td>'+
                            '<td class="text-center">'+item[i].resolved_date+'</td>'+
                            '<td class="text-center">'+statusName+'</td>'+
                            '<td class="text-center">'+btnResolved+btnEdit+btnDelete+'</td>'+
                            '</tr>');
                    }
                }
            });
        }
        function clearForm(){
            $('#frmAddNew input').each(function(){
                $(this).val('');
            });
            $("#severity").prop("checked", false);
            $("#priority").prop("checked", false);

            GID=0;
        }
        function AddNew(){
            clearForm();
            $("#frmAddNew").modal("show");
            $('#frmAddNew').on('shown.bs.modal', function () {
                $('#summary').focus();
            })
        }
        function actionEdit(Id,summary,desc,type_id,severity,priority){
            clearForm();
            GID =Id;
            $("#summary").val(summary);
            $("#description").val(desc);
            $("#ticket_type_id").val(type_id);
            if(severity == 1){
                $("#severity").prop("checked", true);
            }
            if(priority == 1){
                $("#priority").prop("checked", true);
            }
            $("#frmAddNew").modal("show");
            $('#frmAddNew').on('shown.bs.modal', function () {
                $('#summary').focus();
            })
        }
        function Save(){            
            
            var summary = $("#summary").val();
            var description = $("#description").val();
            var ticket_type_id = $("#ticket_type_id").val();
            var chb_severity = $('#severity').is(':checked'); 
            var severity=0;
            if(chb_severity == true){
                severity=1;
            }
            var chb_priority = $('#priority').is(':checked'); 
            var priority=0;
            if(chb_priority == true){
                priority=1;
            }
            if(ticket_type_id == 0){
                MSG.Validation("Please select ticket type !!!");
            }
            else if(summary == ""){
                MSG.Validation("Please input summary !!!");
            }
            else if(description == ""){
                MSG.Validation("Please input description !!!");
            }
            else{
                $.ajax({
                    type:'POST',
                    url:"{{ route('ticket.Save') }}",
                    data:{
                        id: GID,
                        summary: summary,
                        description: description,
                        ticket_type_id: ticket_type_id,
                        severity: severity,
                        priority: priority
                    },
                    success:function(result){
                        if(result.code == 0){
                            MSG.Success();
                            $("#frmAddNew").modal('hide');
                            LoadData();
                        }
                        else {
                            MSG.Error(result.message);
                        }
                    }
                });
            }
        }
        function actionResolve(_id){
            Swal.fire({
            title: 'Resolve',
            text: "Are you resolve this ticket?",
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
                        url:"{{ route('ticket.Resolve') }}",
                        data: {
                            id: _id
                        },
                        success: function (result) {
                            if(result.code ==0){
                                Swal.fire({
                                    title: 'Resolved!',
                                    text: 'Ticket was resolved.',
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
                        url:"{{ route('ticket.Delete') }}",
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
