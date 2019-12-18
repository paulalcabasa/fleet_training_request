<section class="sidebar">
    <div class="user-panel">
        <div class="pull-left image">
            <img src="{{ url('public/images/avatar5.png') }}" class="img-circle" alt="User Image">
        </div>

        <div class="pull-left info">
            <p >{{ Session::get('full_name') }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
    </div>

    <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                    <i class="fa fa-search"></i>
                </button>
            </span>
        </div>
    </form>
    
    <ul class="sidebar-menu" data-widget="tree">
        <li class="header">REQUESTS</li>
        <li id="training_requests_tab"><a href="{{ route('training_requests') }}">
            <i class="fa fa-dashboard"></i>
            <span>Requests Overview</span>
        </a></li>

        <li class="header">PEOPLE</li>
        <li id="dealer_tab"><a href="{{ route('dealers') }}">
            <i class="fa fa-caret-right"></i>&nbsp; 
            <span>Dealers</span>
        </a></li>
        <!-- <li id="trainor_tab"><a href="{{ route('trainors') }}">
            <i class="fa fa-caret-right"></i>&nbsp; 
            <span>Trainors</span>
        </a></li>
        <li id="approver_tab">
            <a href="{{ route('approvers') }}">
                <i class="fa fa-caret-right"></i>&nbsp; 
                <span>Approvers</span>
            </a>
        </li> -->

        <li id="persons_tab">
            <a href="{{ route('persons') }}">
                <i class="fa fa-caret-right"></i>&nbsp; 
                <span>Persons</span>
            </a>
        </li>

        <li class="header">PROGRAM OFFERINGS</li>
        <li id="training_program_tab"><a href="{{ route('training_programs') }}">
            <i class="fa fa-caret-right"></i>&nbsp; 
            <span>Training Programs</span>
        </a></li>
        <li id="special_training_tab"><a href="{{ route('special_trainings') }}">
            <i class="fa fa-caret-right"></i>&nbsp; 
            <span>Special Training Programs</span>
        </a></li>

        <li class="header">VEHICLES</li>
        <li id="unit_model_tab"><a href="{{ route('unit_models') }}">
            <i class="fa fa-caret-right"></i>&nbsp; 
            <span>Unit Models</span>
        </a></li>
    </ul>
</section>