@extends('layouts.app')
@section('content')
<form data-action="{{ route('users.updateProfile') }}" method="POST" enctype="multipart/form-data" id="frmProfile">
@csrf
<div class="container">
    <div class="main-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="{{$user[0]->image}}" alt="User Profile" id="imgPhoto" class="rounded-circle p-1 bg-primary" width="110">
                            <div class="mt-3">
                                <h4>{{$user[0]->username}}</h4>               
                                <input type="file" class="form-control" id="image" name="image" onchange="SelectPhoto();" />      
                                <input type="hidden" id="dPhoto" name="Photo" />
                                <br>
                                <button type="submit" class="btn btn-outline-success"><i class="bx bxs-save"></i> Submit</button>                                       
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body"> 
                        <div class="row mb-3">
                            <div class="form-group">
                                <lable>ឈ្មោះ</lable>
                                <input type="text" class="form-control" maxlength="30"  id="name" name="name" value="{{$user[0]->name}}">
                            </div>                            
                        </div>
                        <div class="row mb-3">
                            <div class="form-group">
                                <lable>ភេទ</lable>
                                <select class="form-select" id="sex" name="sex">
                                    <option value="0">-- select --</option>
                                    @foreach($sex as $sex)
                                        @if($sex->item_id == $user[0]->sex)
                                            <option value="{{$sex->item_id}}" selected>{{$sex->name_kh}}</option>
                                        @else
                                            <option value="{{$sex->item_id}}">{{$sex->name_kh}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>                            
                        </div>
                        <div class="row mb-3">
                            <div class="form-group">
                                <lable>លេខទូរស័ព្ទ</lable>
                                <input type="text" class="form-control" id="phone" maxlength="15" name="phone" value="{{$user[0]->phone}}">
                            </div>                            
                        </div>
                    </div>
                </div>                    
            </div>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready(function (){
        $('#frmProfile').on('submit', function(event){
            event.preventDefault();

            var url = $('#frmProfile').attr('data-action');

            $.ajax({
                url: url,
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(result){
                    if(result.code == 0){
                        MSG.Success();
                        location.reload();
                    }
                    else{
                        MSG.Error(result.msg);
                    }
                }
            });
        });
    })
    function SelectPhoto(){

        var f = document.getElementById('image').files;
        var _size = f[0].size;
        if (_size > 12602695) {
            MSG.Error("File is too large!!!");
            document.getElementById('image').value="";
            return;
        }
        else {
            var file = f[0];
            var fileType = file["type"];
            var validImageTypes = ["image/gif", "image/jpeg", "image/png", "image/bmp"];
            if ($.inArray(fileType, validImageTypes) < 0) {
                MSG.Error("Allow upload image only!!!!");
                document.getElementById('image').value="";
                return;
            }
        }
        
        var selected_file = $('#image').get(0).files[0];
        selected_file = window.URL.createObjectURL(selected_file); 
        $('#imgPhoto').attr('src' , selected_file); 
        document.getElementById('dPhoto').value = $('#image').get(0).files[0];
    }

    function Save(){

        var formData = new FormData();
        var name = $("#name").val();
        var sex = $("#sex").val();
        var phone = $("#phone").val();
        var image = document.getElementById('image').files;

        if(name == ""){
            MSG.Validation("សូមបញ្ចូលឈ្មោះ ");
        }
        else if(sex == 0){
            MSG.Validation("សូមជ្រើសរើសភេទ ");
        }
        else if(phone == ""){
            MSG.Validation("សូមបញ្ចូលលេខទូរស័ព្ទ ");
        }
        else{
            formData.append("name", name);
            formData.append("sex", sex);
            formData.append("phone", phone);
            formData.append("image", image);

            $.ajax({
                type:'POST',
                url: "{{ route('users.updateProfile')}}",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success:function(result){
                    if(result.code == 0){
                        MSG.Success();
                        location.reload();
                    }
                    else{
                        MSG.Error(result.msg);
                    }
                }
            });
        }
        
    }
</script>
   
@endsection