
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'eNCOD') }}</title>
    <link rel="icon" href="/assets/images/logo.png" type="image/png" />
	<!--plugins-->
	   <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!--Plug in-->
    <script src="/assets/js/jquery.min.js"></script>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Hanuman&display=swap');
    </style>
    <style>
        body {
            font-family: 'Hanuman', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
        }
    </style>
</head>
<body class="bg-login">
        <!--wrapper-->
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="mb-4 text-center">
                            <img src="/assets/images/logo.png" width="120" alt="logo icon" />
                        </div>
                        <div class="card radius-30">
                            <!-- <div class="card-header" style="text-align:center;border-radius:30px !important;background-color: white;">
                                <label style="font-family: 'Hanuman'; font-size:20px;font-weight: bold;">ប្រព័ន្ធអេឡិចត្រូនិចស្តីពីការជូនដំណឹង និងកត់ត្រាមរណភាព</label>
                            </div> -->
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                    </div>     
                                    <div class="card-header" style="text-align:center;border-radius:30px !important;background-color: white;">
                                        <label style="font-size:26px; font-weight: bold;">KSL Ticket</label>
                                    </div>                               
                                    <div class="form-body" style="padding-top:20px;">
                                        <form class="row g-3" method="POST" action="{{ route('login') }}">
                                        @csrf
                                            <div class="col-12">                                              
                                                <label for="username" class="form-label">Username</label>
                                                <input placeholder="Please enter your username" id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autofocus>
                                                @error('username')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>                                          
                                            <div class="col-12">
                                                <label for="password" class="form-label">Password</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input placeholder="Please enter your password" id="password" type="password" class="form-control border-end-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                                    <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>                                            
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-success"><i class="bx bxs-lock-open"></i>
                                                    Login
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
    </div>
     <!-- Bootstrap JS -->
     <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script>
            $(document).ready(function () {
                $("#show_hide_password a").on('click', function (event) {
                    event.preventDefault();
                    if ($('#show_hide_password input').attr("type") == "text") {
                        $('#show_hide_password input').attr('type', 'password');
                        $('#show_hide_password i').addClass("bx-hide");
                        $('#show_hide_password i').removeClass("bx-show");
                    } else if ($('#show_hide_password input').attr("type") == "password") {
                        $('#show_hide_password input').attr('type', 'text');
                        $('#show_hide_password i').removeClass("bx-hide");
                        $('#show_hide_password i').addClass("bx-show");
                    }
                });
            });
        </script>
</body>
       