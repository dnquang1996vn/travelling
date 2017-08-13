@extends('layouts.app')

@section('content')
<div class="container" style = "background: #f2f2f2">
    <br><br>
    <button id="testbtn">dshfiu</button>
    <div class="row">
        <div class="col-md-3"> <!-- avatar -->
            <div class="thumbnail avatar" id = "avatarFrame"> 
                
                <img src="{{asset($user->avatar)}}" class="profile_avatar" id = "avatar">
                @if ((Auth::user()->id) == $user->id)
                <a href="#">
                    <span class="glyphicon glyphicon-edit edit_avatar" id = "editAvatarBtn">
                        edit your avatar
                    </span>
                </a>
                @endif
            
                <div class="caption">
                        <h4 class="text-center"> <strong class="name"> {{$user->name}} </strong> </h4>
                        <h4 style="margin-left: 20px"> {{$joined_trips->count()}} joined trips </h4>
                        <h4 style="margin-left: 20px"> {{$created_trips->count()}} created trips </h4>
                        <h4 style="margin-left: 20px"> {{$followed_trips->count()}} follow trips </h4>
                </div>
            </div>  
        </div> <!-- end avatar -->
        <!-- modal edit avatar -->
        <div id="editAvatarModal" class="modal fade" role="dialog">
            
            <div class="modal-dialog">
            <!-- modal content -->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update Avatar</h4>
                    </div>
                    <div class="modal-body">
                        <span style="color: red" id = "avatarError"></span>
                        <form enctype="multipart/form-data" id = "editAvatarForm" method="post" action="{{route('uploadAvatar',$user->id)}}">
                            <input type="file" name="avatarInput" id="avatarInput">
                            <img src="{{asset($user->avatar)}}" id="avatarDisplay" width="200px" />
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id = "saveAvatar">
                            Save 
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8"><!--  detail profile -->
            <div class="profile" style="border: solid">
                <div class="form-group">
                    <label class = "col-lg-2 col-lg-offset-1 control-label">Name:</label>
                    <label class = "control-label name" >{{$user->name}}</label>  
                </div>
                <div class="form-group">
                    <label class = "col-lg-2 col-lg-offset-1 control-label">Birthday:</label>
                    <label class = "control-label birthday">{{$user->birthday}}</label>  
                </div>
                <div class="form-group">
                    <label class = "col-lg-2 col-lg-offset-1 control-label">Gender:</label>
                    <label class = "control-label gender">
                        @if ($user->gender == 0)
                        male
                        @else
                        female
                        @endif
                    </label>  
                </div>
                <div class="form-group">
                    <label class = "col-lg-2 col-lg-offset-1 control-label">
                        Phone
                    </label>
                    <label class = "control-label phone">{{$user->phone}}</label>  
                </div>
                <div class="form-group">
                    <label class = "col-lg-2 col-lg-offset-1 control-label">
                        Work
                    </label>
                    <label class = "control-label work">{{$user->work}}</label>  
                </div>
                <div class="form-group">
                    <label class = "col-lg-2 col-lg-offset-1 control-label">Description:</label>
                    <label class = "control-label about">
                    {{$user->about}}
                    </label>  
                </div>
            </div>
        @if ((Auth::user()->id) == $user->id)
            <button class="btn btn-primary" id = "updateBtn" > 
            update
        </button>
        @endif
        </div>
        <br>
        <!-- Modal update-->
        <div id="updateModal" class="modal fade modal-lg" role="dialog">
            <div class="modal-dialog">
            <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update imformation</h4>
                    </div>
                    <div class="modal-body">
                        <form><br>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control name" name="name" value="{{ $user->name }}" required autofocus>
                                <span style="color: red" id = "nameError"></span>
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">
                            <label for="birthday" class="col-md-4 control-label">Birthday</label>

                            <div class="col-md-8">
                                <input id="birthday" type="text" class="form-control birthday" name="birthday" value="{{ $user->birthday }}" required autofocus>
                                <span style="color: red" id = "birthdayError"></span>
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Gender</label>

                            <div class="col-md-8">
                                <select id = "gender" name = "gender" class="form-control">
                                    <option value="0">Male</option>
                                    <option value="1">Female</option>
                                </select>
                               
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-4 control-label">Phone</label>

                            <div class="col-md-8">
                                <input id="phone" type="number" class="form-control phone" name="phone" value="{{ $user->phone }}" required autofocus>
                                <span style="color: red" id = "phoneError"></span>
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group{{ $errors->has('work') ? ' has-error' : '' }}">
                            <label for="work" class="col-md-4 control-label">Work</label>

                            <div class="col-md-8">
                                <input id="work" type="text" class="form-control work" name="work" value="{{ $user->work }}" required autofocus>
                                <span style="color: red" id = "workError"></span>
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group{{ $errors->has('about') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Descripion about yourself</label>

                            <div class="col-md-8">
                                <input id="about" type="textarea" class="form-control about" name="about" value="{{ $user->about }}" required>
                            <span style="color: red" id = "aboutError"></span>
                            </div>
                        </div>
                        
                        </form>
                        
                    </div>
                    <div class="modal-footer">
                        <br><br>
                        <button type="button" id = "save_update" class="btn btn-success" value="{{$user->id}}">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end modal -->
    </div>
     <!-- end details user -->

   <!--  list trip -->
    <!-- list created -->
    <div>
        <hr style="border-top: 3px double #8c8b8b;">
        <h3> <strong> Created trips </strong> </h3>
        @if ($created_trips->count() == 0)
            <h5> <strong> No created trips </strong> </h5>
        @else
        <section class="regular slider">
            @foreach($created_trips as $new)
                <div class="thumbnail">
                    <img src="{{asset($new->cover)}}" alt="">
                    <div class="caption">
                        <h4><a href="{{route('trip',$new->id)}}"><center>{{$new->name}}</center></a>
                        <h5 class="text-center">{{$new->starting_time}} to {{$new->ending_time}}</h5>
                        </h4>
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <h5 class="text-center"> owner: </h5>
                                <h5 class="text-center">
                                    <a href="{{route('profile',$new->owner->id)}}">{{$new->owner->name}}</a>
                                </h5>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <h5 class="text-center">
                                {{$new->joined_trips->count()}} persons joined</h5>
                                <h5 class="text-center">
                                {{$new->followed_trips->count()}} persons followed
                                </h5>
                            </div>
                        </div>  
                        <p></p>
                    </div>
                </div> <!-- end col-lo=g-4 -->
            @endforeach
        </section>
        @endif
    </div>

    <div>
        <hr style="border-top: 3px double #8c8b8b;">
        <h3> <strong> Followed trips </strong> </h3>
        @if ($followed_trips->count() == 0)
            <h5><strong>No followed trips</strong></h5>
        @else
        <section class="regular slider">
            @foreach($followed_trips as $new)
                <div class="thumbnail">
                    <img src="{{asset($new->trip->cover)}}" alt="">
                    <div class="caption">
                        <h4><a href="{{route('trip',$new->trip->id)}}"><center>{{$new->trip->name}}</center></a>
                        <h5 class="text-center">{{$new->trip->starting_time}} to {{$new->trip->ending_time}}</h5>
                        </h4>
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <h5 class="text-center"> owner: </h5>
                                <h5 class="text-center">
                                    <a href="{{route('profile',$new->trip->owner->id)}}">{{$new->trip->owner->name}}</a>
                                </h5>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <h5 class="text-center">
                                {{$new->trip->joined_trips->count()}} persons joined</h5>
                                <h5 class="text-center">
                                {{$new->trip->followed_trips->count()}} persons followed
                                </h5>
                            </div>
                        </div>  
                        <p></p>
                    </div>
                </div> <!-- end col-lo=g-4 -->
            @endforeach
        </section>
        @endif
    </div>

    <div>
        <hr style="border-top: 3px double #8c8b8b;">
        <h3> <strong> Joined trips </strong> </h3>
        @if ($joined_trips->count() == 0)
            <h5><strong>No joined trip</strong></h5>
        @else
        <section class="regular slider">
            @foreach($joined_trips as $new)
                <div class="thumbnail">
                    <img src="{{asset($new->trip->cover)}}" alt="">
                    <div class="caption">
                        <h4><a href="{{route('trip',$new->trip->id)}}"><center>{{$new->trip->name}}</center></a>
                        <h5 class="text-center">{{$new->trip->starting_time}} to {{$new->trip->ending_time}}</h5>
                        </h4>
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <h5 class="text-center"> owner: </h5>
                                <h5 class="text-center">
                                    <a href="{{route('profile',$new->trip->owner->id)}}">{{$new->trip->owner->name}}</a>
                                </h5>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <h5 class="text-center">
                                {{$new->trip->joined_trips->count()}} persons joined</h5>
                                <h5 class="text-center">
                                {{$new->trip->followed_trips->count()}} persons followed
                                </h5>
                            </div>
                        </div>  
                        <p></p>
                    </div>
                </div> <!-- end col-lo=g-4 -->
            @endforeach
        </section>
        @endif
    </div>
    
</div>

@endsection

@section('jq_lib') <!-- thu vien cua jequerry -->
<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
<script src="http://malsup.github.com/jquery.form.js"></script>
@endsection

@section('sc')
<script src="{{ asset('js/handleApp/profile.js') }}"></script>
@endsection