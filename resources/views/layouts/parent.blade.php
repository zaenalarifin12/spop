<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>@yield('title')</title>
  <!-- 
    **AUTHOR**
    ZAINAL ARIFIN
    085226370746
    github.com/zaenalarif
  -->
  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{ asset("assets/node/jqvmap.min.css")}}">
  <link rel="stylesheet" href="{{ asset("assets/node/summernote-bs4.css") }}">
  <link rel="stylesheet" href="{{ asset("assets/node/owl.carousel.min.css") }}">
  <link rel="stylesheet" href="{{ asset("assets/node/owl.theme.default.min.css") }}">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset("assets/css/style.css") }}">
  <link rel="stylesheet" href="{{ asset("assets/css/components.css") }}">

  <style>
    input{
      text-transform: uppercase !important;
    }
  </style>
  

  @yield('style')
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="{{ asset("assets/img/avatar/avatar-1.png")}}" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">{{ Auth::user()->name }}</div></a>
            <div class="dropdown-menu dropdown-menu-right">
              <a href="{{ url("/profile/".Auth::user()->nip)}}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> Profile
              </a>
              <div class="dropdown-divider"></div>
              <form action="{{ url("/logout") }}" method="post" style="display:inline">
                @csrf
                <button type="submit" class="dropdown-item has-icon text-danger"> Logout</button>
              </form>
            </div>
          </li>
        </ul>
      </nav>
      <div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="{{ url("/") }}">Stisla</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url("/") }}">St</a>
          </div>
            <ul class="sidebar-menu">
              <li class="menu-header">Dashboard</li>

              <li><a class="nav-link" href="{{ url("/home") }}"><i class="fas fa-pencil-ruler"></i>Home</a></li>

              <li><a class="nav-link" href="{{ url("/perekaman/create") }}"><i class="fas fa-pencil-ruler"></i> Daftar Objek baru</a></li>

              <li><a class="nav-link" href="{{ url("/pemutakhiran/cari") }}"><i class="fas fa-pencil-ruler"></i> Daftar Perubahan Data</a></li>

              <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Data Usulan</span></a>
                <ul class="dropdown-menu">
                  <li><a class="nav-link" href="{{ url("/perekaman") }}">Objek baru</a></li>
                  <li><a class="nav-link" href="{{ url("/pemutakhiran") }}"> Perubahan Data</a></li>
                </ul>
              </li>

              @if (Auth::user()->role == 1)
              <li><a class="nav-link" href="{{ url("/users") }}"><i class="fas fa-user"></i> Pengguna</a></li>
              @endif

              <li>
                <form action="{{ ("/logout") }}" method="post" class="nav-link">
                  @csrf
                <input type="submit" value="Logout" class="btn btn-primary btn-block">
                </form>
              </li>
            </ul>
        </aside>
      </div>

      @yield('content')

      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; {{ date("Y") }} <div class="bullet"></div> By <a href="">BPKAD</a>
        </div>
      </footer>
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="{{ asset("assets/js/stisla.js")}}"></script>

  <!-- JS Libraies -->
  <script src="{{ asset("assets/node/jquery.sparkline.min.js")}}"></script>
  <script src="{{ asset("assets/node/Chart.min.js")}}"></script>
  <script src="{{ asset("assets/node/owl.carousel.min.js")}}"></script>
  <script src="{{ asset("assets/node/summernote-bs4.js")}}"></script>
  <script src="{{ asset("assets/node/jquery.chocolat.min.js")}}"></script>

  <!-- Template JS File -->
  <script src="{{ asset("assets/js/scripts.js")}}"></script>
  <script src="{{ asset("assets/js/custom.js")}}"></script>

  <!-- Page Specific JS File -->
  {{-- <script src="{{ asset("assets/js/page/index.js")}}"></script> --}}

  <script>
    $(function(){
      $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
    });
  </script>
  @yield('script')

  <script>
    $(document).ready(function () {  
        $("input[type=text]").keyup(function () {  
            $(this).val($(this).val().toUpperCase());  
        });  
    }); 
  </script>
  
</body>
</html>
