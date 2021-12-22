<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{route('dashboard')}}">
            <img src="https://archive.gov.ge/images/front/logo_ge.png" alt="logo">
        </a>
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="leftmenudropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Browse
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="leftmenudropdown">
                        <li><a class="dropdown-item" href="#">ქმედება</a></li>
                        <li><a class="dropdown-item" href="#">სხვა ქმედება</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">კიდევ სხვა რამე</a></li>
                    </ul>
                </li>
            </ul>
            <div class="right w-25 d-flex justify-content-around align-items-center">
                <div class=" d-flex justify-content-around align-items-center">
                    <a class="me-2" href="#"><span class="material-icons md-light">add</span></a>
                    <a class="me-2" href="#"><span class="material-icons md-light">edit</span></a>
                    <a class="me-2" href="#"><span class="material-icons md-light">file_download</span></a>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="material-icons md-light">settings_applications</span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="{{route('users.index')}}">მომხმარებლები</a></li>
                            <li><a class="dropdown-item" href="{{route('groups.index')}}">ადმინისტრირების ჯგუფები</a></li>
                            <li><a class="dropdown-item" href="{{route('permissions.index')}}">პრივილეგიები</a></li>
                            <li><a class="dropdown-item" href="{{route('types.index')}}">ობიექტების ტიპები</a></li>
                        </ul>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="profile" data-bs-toggle="dropdown" aria-expanded="false">
                        {{\Illuminate\Support\Facades\Auth::user()->name}}
                        <span class="material-icons md-light">account_circle</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="profile">
                        <li><a class="dropdown-item" href="{{route('profile')}}">პროფილი</a></li>
                        <li><a class="dropdown-item" onclick="logout()" href="#">გამოსვლა</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>


<script>

    function logout() {
        axios({
            method:"post",
            url: "{{ route('logout') }}",
            data: {
                "csrf-token": "{{ csrf_token()}}"
            }
        }).then((response)=>{
            console.log(response)
            location.reload()
        }).catch((err)=>{
            console.log(err)
        })
    }



</script>