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

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <table class="table-responsive">
                <table class="table table-striped table-bordered table-sm" id="tblUser">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-left">Full Name</th>
                        <th class="text-left">Username</th>
                        <th class="text-center">Role</th>
                        <th class="text-center"></th>
                    </tr>
                    <tbody id="bodyUser"></tbody>
                </table>
            </table>
        </div>
    </div>


    <div class="modal fade" id="frmAddNew" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <input type="hidden" id="txtId" value="0" data-required="0">
                                <label>Full Name<span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" id="txtName" data-required="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>Username <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" id="txtUsername" data-required="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>Password <span class="text-danger">(*)</span></label>
                                <input type="password" class="form-control" id="txtPassword" data-required="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>Role <span class="text-danger">(*)</span></label>
                                <select class="form-select select2" id="txtRole" data-required="1">
                                </select>
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

    <div class="modal fade" id="frmReset" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="hidden" id="txtUserId" value="0">
                            <input type="password" id="txtDefaultPassword" class="form-control"> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="SaveReset()"><i class="bx bxs-save"></i> Save</button>
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
        var usernameList=[];
        var prevUsername ="";
        $(document).ready(function (){
            $.ajax({
                type:'GET',
                url:"{{ route('users.GetInitPage') }}",
                data:{},
                success:function(result){
                    for(i=0; i<result.username.length; i++){
                        usernameList.push(result.username[i].username);
                    }
                    console.log(usernameList);
                    var role = result.role;
                    role.unshift({ id: 0, text:'-- select --'});
                    $('#txtRole').select2({data: role, width: '100%', dropdownParent: $("#frmAddNew") });
                  
                }
            });
            LoadData();
        })

     
        function LoadData(){
            $("#bodyUser").html("");
            $.ajax({
                type:'GET',
                url:"{{ route('users.GetData') }}",
                data:{},
                success:function(data){
                    // console.log(data);
                    var item =data;
                    var index=0;
                    for (var i = 0; i < item.length; i++) {
                        var btnEdit ='<span class="text-primary" style="cursor: pointer;font-size: 24px" title="Edit" '+
                            'onclick="actionEdit('+item[i].id+',\''+item[i].name+'\',\''+item[i].username+'\','+item[i].role_id+','+item[i].sex+',\''+item[i].phone+'\','+item[i].province_id+','+item[i].district_id+','+item[i].hf_id+')">'+
                            '<i class="bx bx-edit"></i> </span>';
                        var btnReset ='<span class="text-success" style="cursor: pointer;font-size: 24px" title="Reset" onclick="actionReset('+item[i].id+')"><i class="bx bx-reset"></i></span>';
                        var btnActive ="";
                      
                        if(item[i].id != 1){
                            index++;
                            $("#bodyUser").append('<tr>'+
                                '<td class="text-center">'+index+'</td>'+
                                '<td class="text-left">'+item[i].name+'</td>'+
                                '<td class="text-left">'+item[i].username+'</td>'+
                                '<td class="text-center">'+item[i].role_name+'</td>'+
                                '<td class="text-center">'+btnEdit+btnReset+'</td>'+
                            '</tr>');
                        }
                        
                    }
                }
            });
        }
        function clearForm(){
            $('#frmAddNew input').each(function(){
                $(this).val('');
                $(this).removeClass('highlight');
            });
            $('#frmAddNew select').each(function(){
                $(this).siblings(".select2-container").css('border', "none");
                $(this).css('border', "1px solid #ccc");
                $(this).val(0).trigger('change');
            });
            $("#txtId").val(0);

        }
        function AddNew(){
            clearForm();
            $("#frmAddNew").modal("show");
            $('#frmAddNew').on('shown.bs.modal', function () {
                $('#txtName').focus();
            });
            $("#txtPassword").prop("disabled",false);
        }
        function actionEdit(Id,Name,Username,RoleId,sex,phone,province_id, district_id, hf_id){

            $("#txtId").val(Id);
            $("#txtName").val(Name);
            $("#txtUsername").val(Username);
            $("#txtRole").val(RoleId).trigger("change");
            $("#txtPassword").val(12345678);
            $("#txtPassword").prop("disabled", true);
            prevUsername = Username;
           
            $("#frmAddNew").modal("show");
            $('#frmAddNew').on('shown.bs.modal', function () {
                $('#txtName').focus();
            })
            

        }
        function Save(){
            var Id = $("#txtId").val();
            var Name = $("#txtName").val();
            var Username = $("#txtUsername").val();
            var Role = $("#txtRole").val();     
            var password= $("#txtPassword").val();
            const index = usernameList.indexOf(prevUsername);
            if (index > -1) { // only splice array when item is found
                usernameList.splice(index, 1); // 2nd parameter means remove one item only
            }

            if(Name == ""){
                MSG.Validation("Please input full name !!!");
            }          
            else if(Username == ""){
                MSG.Validation("Please input Username !!!");
            }
            else if(password == ""){
                MSG.Validation("Please input Password !!!");
            }
            else if(Role == 0){
                MSG.Validation("Please select role !!!");
            }          
            else if(usernameList.includes(Username)){
                MSG.Validation("This username is already exist !!!");
            }
            else{
                $.ajax({
                    type:'POST',
                    url:"{{ route('users.Save') }}",
                    data:{
                        id: Id,
                        name: Name,
                        username: Username,
                        password: password,
                        role_id: Role
                    },
                    success:function(result){
                        if(result.code == 0){
                            MSG.Success();
                            $("#frmAddNew").modal('hide');
                            LoadData();
                        }
                        else{
                            MSG.Validation("This username is already exist !!!");
                        }
                    }
                });
            }
        }
        
        function actionReset(id){
           $("#txtUserId").val(id);
           $("#frmReset").modal("show");
           $("#frmReset").on('shown.bs.modal', function(){
                $(this).find('#txtDefaultPassword').focus();
            });
        }
        function SaveReset(){

            var user_id = $("#txtUserId").val();
            var password = $("#txtDefaultPassword").val();

            if(user_id == 0){
                MSG.Validation("Please select user");
            }
            else if(password == ""){
                MSG.Validation("Please input password");
            }
            else{
                $.ajax({
                    async: false,
                    dataType: "json",
                    type: "POST",
                    url:"/ResetPassword",
                    data: {
                        id: user_id,
                        password: password
                    },
                    success: function (result) {
                        MSG.Success();
                        $("#frmReset").modal("hide");
                    },
                    error: function (e) {
                        MSG.Validation(e.message);
                    }
                });
            }
            
        }
    </script>
@endsection
