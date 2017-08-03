@extends('layouts.app')

@section('content')
<div class="container" style = "background: #f2f2f2">
    <br><br>
    <div class="row">
        <div class="col-md-3"> <!-- avatar -->
            <div class="thumbnail avatar">
                <img src="{{asset($user->avatar)}}" class="profile_avatar">
                @if ((Auth::user()->id) == $user->id)
                <a href="#">
                <span class="glyphicon glyphicon-edit edit_avatar">edit your avatar</span>
                </a>
                @endif
                <div class="caption">
                        <h4 class="text-center"> <strong class="name"> {{$user->name}} </strong> </h4>
                        <h4 style="margin-left: 20px"> 15 joined trips </h4>
                        <h4 style="margin-left: 20px"> 20 created trips </h4>
                        <h4 style="margin-left: 20px"> 10 follow trips </h4>
                </div>
            </div>  
        </div> <!-- end avatar -->

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
            <button class="btn btn-primary" data-toggle="modal" data-target="#updateModal" > 
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
                        <form>
                        <br>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control name" name="name" value="{{ $user->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">
                            <label for="birthday" class="col-md-4 control-label">Birthday</label>

                            <div class="col-md-8">
                                <input id="birthday" type="text" class="form-control birthday" name="birthday" value="{{ $user->birthday }}" required autofocus>

                                @if ($errors->has('birthday'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('birthday') }}</strong>
                                    </span>
                                @endif
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

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group{{ $errors->has('work') ? ' has-error' : '' }}">
                            <label for="work" class="col-md-4 control-label">Work</label>

                            <div class="col-md-8">
                                <input id="work" type="text" class="form-control work" name="work" value="{{ $user->work }}" required autofocus>

                                @if ($errors->has('work'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('work') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group{{ $errors->has('about') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Descripion about yourself</label>

                            <div class="col-md-8">
                                <input id="about" type="textarea" class="form-control about" name="about" value="{{ $user->about }}" required>

                                @if ($errors->has('about'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('about') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        </form>
                        
                    </div>
                    <div class="modal-footer">
                        <br><br>
                        <button type="button" class="btn btn-success" data-dismiss="modal">Reset</button>
                        <button type="button" id = "save_update" class="btn btn-success" data-dismiss="modal" value="{{$user->id}}">Save</button>
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
          <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#planning_created" aria-controls="home" role="tab" data-toggle="tab">Planning</a>
            </li>
            <li role="presentation">
                <a href="#inprogress_created" aria-controls="profile" role="tab" data-toggle="tab">In Progress</a>
            </li>
            <li role="presentation">
                <a href="#completed_created" aria-controls="messages" role="tab" data-toggle="tab">Completed</a>
            </li>
        </ul>

          <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="planning_created"></div>
            <div role="tabpanel" class="tab-pane" id="inprogress_created"></div>
            <div role="tabpanel" class="tab-pane" id="completed_created">sdfi</div>
        </div>
    </div>

    <!-- list joined trip -->
    <div>
        <h3> <strong> Joined trips </strong> </h3>
          <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#planning_joined" aria-controls="home" role="tab" data-toggle="tab">Planning</a>
            </li>
            <li role="presentation">
                <a href="#inprogress_joined" aria-controls="profile" role="tab" data-toggle="tab">In Progress</a>
            </li>
            <li role="presentation">
                <a href="#completed_joined" aria-controls="messages" role="tab" data-toggle="tab">Completed</a>
            </li>
        </ul>

          <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="planning_joined">
            </div>
            <div role="tabpanel" class="tab-pane" id="inprogress_joined">join 2</div>
            <div role="tabpanel" class="tab-pane" id="completed_joined">sdfi</div>
        </div>
    </div>

    <!-- list follow trips -->
    <div>
        <h3> <strong> Followed trips </strong> </h3>
          <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#planning_followed" aria-controls="home" role="tab" data-toggle="tab">Planning</a>
            </li>
            <li role="presentation">
                <a href="#inprogress_followed" aria-controls="profile" role="tab" data-toggle="tab">In Progress</a>
            </li>
            <li role="presentation">
                <a href="#completed_followed" aria-controls="messages" role="tab" data-toggle="tab">Completed</a>
            </li>
        </ul>

          <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="planning_followed"></div>
            <div role="tabpanel" class="tab-pane" id="inprogress_followed"></div>
            <div role="tabpanel" class="tab-pane" id="completed_followed">
                 <section class="regular slider">
      <div>
        <img src="http://placehold.it/350x300?text=1">
      </div>
      <div>
        <img src="http://placehold.it/350x300?text=2">
      </div>
      <div>
        <img src="http://placehold.it/350x300?text=3">
      </div>
      <div>
        <img src="http://placehold.it/350x300?text=4">
      </div>
      <div>
        <img src="http://placehold.it/350x300?text=5">
      </div>
      <div>
        <img src="http://placehold.it/350x300?text=6">
      </div>
    </section>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jq_lib') <!-- thu vien cua jequerry -->
<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
@endsection

@section('sc')
<script src="{{ asset('js/handleApp/profile.js') }}"></script>
@endsection