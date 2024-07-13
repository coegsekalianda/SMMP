@extends('dosen.template')
@section('content')
<style>
    #lbname {
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
        color: white;
    }

    .profile-pic {
        position: relative;
        width: 200px;
    }

    .image {
        display: block;
        width: 100%;
        height: auto;
    }

    .middle {
        border-radius: 50%;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        width: 100%;
        opacity: 0;
        transition: .5s ease;
        background-color: hsla(0, 0%, 0%, 0.3);
    }

    .profile-pic:hover .middle {
        opacity: 1;
    }

    .icon {
        color: white;
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        text-align: center;
    }
</style>
<div class="row flex-grow">
    <div class="col-md-6 col-lg-6 grid-margin stretch-card">
        <div class="card card-rounded">
            <div class="card-body card-rounded text-center">
                <center>
                    <form id="form" action="/edit-pp/{{$profile->id}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <input style="display:none" type="file" name="img" id="img">
                    </form>
                    <div class="profile-pic" onclick="document.getElementById('img').click()">
                        <img class="image p-3 mt-3" style="width:200px; height:200px; object-fit:cover; border-radius:50%; border: 1px solid grey" src="{{asset('/assets/img/pp/'.$profile->img)}}" alt="">
                        <div class="middle"><i class="icon mdi mdi-camera icon-lg"></i></div>
                    </div>
                </center>
                <div class="table-responsive mt-3">
                    <table class="table" style="text-align:start">
                        <tr>
                            <td>Name</td>
                            <td>:</td>
                            <td id="input-name" hidden="hidden">
                                <form id="form-name" action="/edit-name/{{$profile->id}}" method="post">
                                    @csrf
                                    @method('put')
                                    <input id="name" type="text" class="form-control" name="name" value="{{$profile->name}}" autofocus autocomplete="off">
                                    @error('name')
                                    <div class="alert alert-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                            </td>
                            <td id="submit-name" hidden="hidden">
                                <button type="submit" class="btn-sm btn-primary me-2">Submit</button>
                                </form>
                            </td>
                            <td id="profile-name">{{$profile->name}} <i id="pen-edit" style="opacity:0.5; cursor:pointer" class="icon-sm mdi mdi-border-color"></i></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td>{{$profile->email}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 grid-margin stretch-card">
        <div class="card card-rounded">
            <div class="card-body card-rounded">
                <h4 class="card-title">Change Password</h4>
                <form method="POST" action="/edit-password/{{$profile->id}}" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label>Old password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="old_password" placeholder="Old password" autofocus autocomplete="off">
                        @error('old_password')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>New password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" placeholder="New password" autofocus autocomplete="new-password">
                        @error('password')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Confirm new password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm new password" autofocus autocomplete="new-password">
                        @error('confirm_password')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById("img").onchange = function() {
        document.getElementById("form").submit();
    };

    document.getElementById("pen-edit").onclick = function() {
        document.getElementById("profile-name").setAttribute('hidden', 'hidden');
        document.getElementById("input-name").removeAttribute('hidden', 'hidden');
        document.getElementById("submit-name").removeAttribute('hidden', 'hidden');
    }

    document.getElementById("name").onchange = function() {
        document.getElementById("form-name").submit();
        document.getElementById("profile-name").removeAttribute('hidden', 'hidden');
        document.getElementById("input-name").setAttribute('hidden', 'hidden');
        document.getElementById("submit-name").setAttribute('hidden', 'hidden');
    };
</script>
@endsection