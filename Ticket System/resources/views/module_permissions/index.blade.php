@extends('layouts.app')
@section('content')
    <style>
        #tblPermission td{
            vertical-align: middle;
        }
    </style>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">System Security</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><i class="bx bx-home-alt"></i></li>
                    <li class="breadcrumb-item active" aria-current="page">Permission</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-md-4">
            <label>Role</label>
            <select class="form-select" id="txtRoleFilter" onchange="LoadData()">
                <option value="0">-- select --</option>
                @foreach($role as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <br>
            <button type="button" class="btn btn-success" id="btnSave" onclick="Save()"><i class="bx bxs-save"></i> Save</button>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered table-sm" id="tblPermission">
                <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-left">Module Name</th>
                    <th class="text-center">All</th>
                    <th class="text-center">List</th>
                    <th class="text-center">Create</th>
                    <th class="text-center">Update</th>
                    <th class="text-center">Delete</th>
                </tr>
                </thead>
                <tbody id="bodyPermission"></tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function (){
        })
        function LoadData(){
            $("#bodyPermission").html("");
            $("#btnSave").prop("disabled",true);
            var role_id = $("#txtRoleFilter").val()
            if(role_id == 0){
                MSG.Validation("Please select role");
                return false;
            }
            $.ajax({
                type:'GET',
                url:"{{ route('module_permissions.GetData') }}",
                data:{
                    role_id: role_id
                },
                success:function(data){
                    // console.log(data);
                    $("#btnSave").prop("disabled",false);
                    var item =data;
                    for (var i = 0; i < item.length; i++) {

                        var all_check ="";
                        if(item[i].a_read == 1 && item[i].a_create == 1 && item[i].a_update == 1 && item[i].a_delete == 1){
                            all_check ='checked';
                        }
                        var all = '<input type="checkbox" id="a_all'+i+'" name="a_all" '+all_check+' ' +
                            'onchange=\'iSFullPermission("a_all' + i + '","a_read_' + i + '","a_create' + i + '","a_update' + i + '","a_delete' + i + '")\'>';

                        var r_checked ="";
                        if(item[i].a_read == 1){
                            r_checked ='checked';
                        }
                        var read = '<input type="checkbox" id="a_read_'+i+'" name="a_read" value="'+item[i].a_read+'" '+r_checked+'>';

                        var c_checked ="";
                        if(item[i].a_create == 1){
                            c_checked ='checked';
                        }
                        var create = '<input type="checkbox" id="a_create'+i+'" name="a_create" value="'+item[i].a_create+'" '+c_checked+'>';

                        var u_checked ="";
                        if(item[i].a_update == 1){
                            u_checked ='checked';
                        }
                        var update = '<input type="checkbox" id="a_update'+i+'" name="a_update" value="'+item[i].a_update+'" '+u_checked+'>';

                        var d_checked ="";
                        if(item[i].a_delete == 1){
                            d_checked ='checked';
                        }
                        var a_delete = '<input type="checkbox" id="a_delete'+i+'" name="a_delete" value="'+item[i].a_delete+'" '+d_checked+'>';
                        $("#bodyPermission").append('<tr>'+
                            '<td class="text-center">'+
                            '<input type="hidden" name="permission_id" value="'+item[i].permission_id+'">'+(i+1)+''+
                            '<input type="hidden" name="module_id" value="'+item[i].module_id+'">'+
                            '</td>'+
                            '<td class="text-left">'+item[i].module_name+'</td>'+
                            '<td class="text-center">'+all+'</td>'+
                            '<td class="text-center">'+read+'</td>'+
                            '<td class="text-center">'+create+'</td>'+
                            '<td class="text-center">'+update+'</td>'+
                            '<td class="text-center">'+a_delete+'</td>'+
                            '</tr>');
                    }
                }
            });
        }
        function iSFullPermission(fullName,ListName, AddName , EditName, DeleteName){

            if(document.getElementById(fullName).checked)
            {
                document.getElementById(AddName).checked = true;
                document.getElementById(EditName).checked = true;
                document.getElementById(DeleteName).checked = true;
                document.getElementById(ListName).checked = true;

            }
            else{

                document.getElementById(AddName).checked = false;
                document.getElementById(EditName).checked = false;
                document.getElementById(DeleteName).checked = false;
                document.getElementById(ListName).checked = false;


            }
        }

        function Save(){
            var permission_id = document.getElementsByName("permission_id");
            var a_read = document.getElementsByName("a_read");
            var a_create = document.getElementsByName("a_create");
            var a_update = document.getElementsByName("a_update");
            var a_delete = document.getElementsByName("a_delete");
            var module_id = document.getElementsByName("module_id");
            var obj={
                permission:[]
            };
            for(i = 0; i < permission_id.length; i++){
                var a_read_ =0;
                if ($(a_read[i]).is(':checked')) {
                    a_read_ =1;
                }

                var a_create_ =0;
                if ($(a_create[i]).is(':checked')) {
                    a_create_ =1;
                }
                var a_update_ =0;
                if ($(a_update[i]).is(':checked')) {
                    a_update_ =1;
                }
                var a_delete_ =0;
                if ($(a_delete[i]).is(':checked')) {
                    a_delete_ =1;
                }
                var per = {
                    permission_id: permission_id[i].value,
                    module_id: module_id[i].value,
                    role_id: $("#txtRoleFilter").val(),
                    a_read: a_read_,
                    a_create: a_create_,
                    a_update: a_update_,
                    a_delete: a_delete_,
                }
                obj.permission.push(per);
            }
            console.log(obj);
            $.ajax({
                type:'POST',
                url:"{{ route('module_permissions.Save') }}",
                data:JSON.stringify(obj),
                success:function(result){
                    console.log(result);
                    if(result.code == 0){
                        MSG.Success();
                    }
                    else{
                        MSG.Error(result.msg);
                    }
                }
            });
        }
    </script>
@endsection
