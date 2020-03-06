@extends('layouts.parent')

@section('title')
    Edit profil anda
@endsection

@section('style')
    
@endsection

@section('content')
     <!-- Main Content -->
     <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1 class="text-info text-uppercase">Profil Anda</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Home</a></div>
              <div class="breadcrumb-item"><a href="#">Profile</a></div>
              <div class="breadcrumb-item">Edit</div>
            </div>
          </div>
          
          <div class="section-body" >
            <div class="container">
              <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                  <div class="card">
                    <div class="card-header">
                      <h4>Edit Profil Anda</h4>
                    </div>
                    <form action="{{ url("/profile/$user->nip") }}" method="post">
                      <div class="card-body">
                          <div class="row">
                            <div class="form-group col-6">
                              <label for="">NIP</label>
                                <input id="" type="text" class="form-control" name="nip" value="{{ old("nip") ? old("nip") : $user->nip }}">
                            </div>
                            <div class="form-group col-6">
                              <label for="last_name">Nama</label>
                              <input id="last_name" type="text" class="form-control" name="name" value="{{ old("name") ? old("name") : $user->name }}">
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="email">Instansi</label>
                            <input id="email" type="text" class="form-control" name="instansi" value="{{ old("instansi") ? old("instansi") : $user->instansi }}">
                            <div class="invalid-feedback">
                            </div>
                          </div>

                          @if (Auth::user()->role == 1)
                            <div class="form-group">
                              <label for="password" class="text-danger" >Ganti Password</label>
                              <input id="password" type="text" class="form-control" name="password" value="">
                              <div class="invalid-feedback">
                              </div>
                            </div>
                          @endif
          
                          <div class="form-group">
                            @csrf
                            @method("PUT")
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                              Edit
                            </button>
                          </div>
                        </div>
                    </form>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </section>
      </div>
@endsection

@section('script')
    <!-- Page Specific JS File -->
    <script src="{{ asset("assets/js/page/forms-advanced-forms.js")}}"></script>
@endsection