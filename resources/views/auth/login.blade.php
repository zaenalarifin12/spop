<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login &mdash; BPKAD</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="../node_modules/selectric/public/selectric.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset("assets/css/style.css")}}">
  <link rel="stylesheet" href="{{ asset("assets/css/components.css")}}">
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">

            <div class="card card-primary">
              <div class="card-header"><h4>Login</h4></div>

              <div class="card-body">
                <form method="POST" action=" {{ url("/login") }}" >
                  <div class="row">
                    <div class="form-group col-12">
                      <label for="nip">NIP</label>
                      <input id="nip" type="text" class="form-control @error("nip") is-invalid @enderror" name="nip" autofocus>
                      @error('nip')
                          <span class="invalid-feedback">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-12">
                      <label for="password" class="d-block">Password</label>
                      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                      @error('password')
                          <span class="invalid-feedback">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
                  </div>
                  @csrf
                  <div class="form-group">
                    <div class="row">
                        <div class="col-12 pull-left">
                          <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Login
                          </button>
                        </div>               
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                          <span>belum punya akun ?</span>
                          <a href="{{ url("/register") }}" class="">Register</a>
                        </div>     
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="simple-footer">
              Copyright &copy; BPKAD PATI
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="{{ asset("assets/js/stisla.js")}}"></script>

  <!-- JS Libraies -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pwstrength-bootstrap/3.0.5/pwstrength-bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/selectric@1.13.0/public/jquery.selectric.min.js"></script>

  <!-- Template JS File -->
  <script src="{{ asset("assets/js/scripts.js")}}"></script>
  <script src="{{ asset("assets/js/custom.js")}}"></script>

  <!-- Page Specific JS File -->
  <script src="{{ asset("assets/js/page/auth-register.js")}}"></script>
</body>
</html>
