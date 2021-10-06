<nav class="uk-navbar uk-background-secondary" uk-navbar >
    <div class="uk-navbar-left">
        <a class="uk-navbar-item uk-logo uk-padding-small" href="{{route('dashboard')}}"> <img src="https://archive.gov.ge/images/front/logo_ge.png" alt="logo"></a>
        <ul class="uk-navbar-nav">
            <li>
                <a href="#" class="text-white">Browse</a>
                <div class="uk-navbar-dropdown">
                    <ul class="uk-nav uk-navbar-dropdown-nav">
                        <li class="uk-active"><a href="#">Active</a></li>
                        <li><a href="#">Item</a></li>
                        <li class="uk-nav-header">Header</li>
                        <li><a href="#">Item</a></li>
                        <li><a href="#">Item</a></li>
                        <li class="uk-nav-divider"></li>
                        <li><a href="#">Item</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    <div class="uk-navbar-right">
        <ul class="uk-navbar-nav uk-margin-right">
            <li><a href="#"><span class="material-icons md-light">add</span></a></li>
            <li><a href="#"><span class="material-icons md-light">edit</span></a></li>
            <li><a href="#"><span class="material-icons md-light">file_download</span></a></li>
            <li><a href="#"><span class="material-icons md-light">settings_applications</span></a></li>
            <div uk-dropdown="pos: bottom-right; mode: click">
                <ul class="uk-nav uk-dropdown-nav">
                    <li><a href="{{route('users')}}">Users</a></li>
                    <li><a href="{{route('groups')}}">Groups</a></li>
                    <li><a href="{{route('permissions')}}">Permissions</a></li>
                </ul>
            </div>


            <div class="uk-flex uk-margin-large-left">
                <span>{{$user->name}} </span>
                <span class="material-icons md-light">person</span>
            </div>
            <div uk-dropdown="pos: bottom-right; mode: click;">
                <ul class="uk-nav uk-dropdown-nav">
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Log out</a></li>
                </ul>
            </div>
        </ul>
    </div>
</nav>