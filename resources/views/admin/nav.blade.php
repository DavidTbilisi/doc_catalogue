<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{route('dashboard')}}">
            <img src="https://archive.gov.ge/images/front/logo_ge.png" alt="logo">
        </a>
       <div class="right w-25">
               <a href="#"><span class="material-icons md-light">add</span></a>
               <a href="#"><span class="material-icons md-light">edit</span></a>
               <a href="#"><span class="material-icons md-light">file_download</span></a>
               <a href="#"><span class="material-icons md-light">settings_applications</span></a>
                <button type="button" class="btn btn-light ms-5">
                    {{$user->name}}
                    <span class="material-icons md-light">account_circle</span>
                </button>
       </div>
    </div>
</nav>