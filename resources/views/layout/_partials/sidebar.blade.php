@php
    $base_url = url('/');
    $current = url()->current();
    $url = str_replace($base_url, '', $current);
    $url = ($url) ? explode('/', $url)[1] : '';
@endphp
<ul class="sidebar-menu">
    <li  @if (!$url || $url == 'dashboard') class="active" @endif @if ($url == 'dashboard') class="active" @endif><a class="nav-link" href="{{ url('/') }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span></a>
    </li>
    @if (session('role_id') == 1)
        <li  @if ($url == 'student') class="active" @endif><a class="nav-link" href="{{ url('student') }}">
            <i class="fas fa-user-graduate"></i>
            <span>Student</span></a>
        </li>   
        <li  @if ($url == 'teacher') class="active" @endif><a class="nav-link" href="{{ url('teacher') }}">
            <i class="fas fa-user-tie"></i>
            <span>Teacher</span></a>
        </li>   
        <li  @if ($url == 'class') class="active" @endif><a class="nav-link" href="{{ url('class') }}">
            <i class="fas fa-users"></i>
            <span>Class</span></a>
        </li>   
        <li  @if ($url == 'major') class="active" @endif><a class="nav-link" href="{{ url('major') }}">
            <i class="fas fa-clipboard"></i>
            <span>Major</span></a>
        </li>   
        <li  @if ($url == 'subject') class="active" @endif><a class="nav-link" href="{{ url('subject') }}">
            <i class="fas fa-book"></i>
            <span>Subject</span></a>
        </li> 
    @elseif(session('role_id') == 2)
        <li  @if ($url == 'subject') class="active" @endif><a class="nav-link" href="{{ url('subject') }}">
            <i class="fas fa-book"></i>
            <span>Subject</span></a>
        </li> 
        <li  @if ($url == 'classroom') class="active" @endif><a class="nav-link" href="{{ url('classroom') }}">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>Classroom</span></a>
        </li> 
    @elseif(session('role_id') == 3)    
        <li  @if ($url == 'classmate') class="active" @endif><a class="nav-link" href="{{ url('classmate') }}">
            <i class="fas fa-users"></i>
            <span>Classmate</span></a>
        </li> 
        <li  @if ($url == 'subject') class="active" @endif><a class="nav-link" href="{{ url('subject') }}">
            <i class="fas fa-book"></i>
            <span>Subject</span></a>
        </li> 
        <li  @if ($url == 'classroom') class="active" @endif><a class="nav-link" href="{{ url('classroom') }}">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>Classroom</span></a>
        </li> 
    @endif
    <li><a class="nav-link" href="{{ url('auth/logout') }}">
        <i class="fas fa-sign-out-alt"></i>
        <span>Sign out</span></a>
    </li> 
</ul>