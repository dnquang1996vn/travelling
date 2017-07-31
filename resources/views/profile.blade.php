@extends('layouts.app')

@section('content')
<div class="container" style = "background: #f2f2f2">
<br><br>
    <section>
        <div class="row">
            <div class="col-md-3"> <!-- avatar -->
                <div class="thumbnail avatar">
                    <img src="{{asset($user->avatar)}}" class="profile_avatar">
                    <a href="#">
                    <span class="glyphicon glyphicon-edit edit_avatar">edit your avatar</span>
                    </a>
                    <div class="caption">
                            <h4 class="text-center"> <strong> {{$user->name}} </strong> </h4>
                            <h4 style="margin-left: 20px"> 15 joined trips </h4>
                            <h4 style="margin-left: 20px"> 20 created trips </h4>
                            <h4 style="margin-left: 20px"> 10 follow trips </h4>
                    </div>
                </div>  
            </div> <!-- end avatar -->

            <div class="col-md-8"><!--  detail profile -->
                <div class="profile">
                    <div class="form-group">
                        <label class = "col-lg-2 col-lg-offset-1 control-label">Name:</label>
                        <label class = "control-label">{{$user->name}}</label>  
                    </div>
                    <div class="form-group">
                        <label class = "col-lg-2 col-lg-offset-1 control-label">Birthday:</label>
                        <label class = "control-label">{{$user->birthday}}</label>  
                    </div>
                    <div class="form-group">
                        <label class = "col-lg-2 col-lg-offset-1 control-label">Gender:</label>
                        <label class = "control-label">
                            @if ($user->gender == 0)
                            male
                            @else
                            female
                            @endif
                        </label>  
                    </div>
                    <div class="form-group">
                        <label class = "col-lg-2 col-lg-offset-1 control-label">
                            {{$user->work}}
                        </label>
                        <label class = "control-label">Stdent</label>  
                    </div>
                    <div class="form-group">
                        <label class = "col-lg-2 col-lg-offset-1 control-label">Description:</label>
                        <label class = "control-label">
                        {{$user->about}}
                        </label>  
                    </div>
                </div>
            <button class="btn btn-primary"> update</button>
            </div>

        </div>
    </section>

   <!--  list trip -->
    <!-- list created -->
    <div>
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