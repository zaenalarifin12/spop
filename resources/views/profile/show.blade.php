@extends('layouts.parent')

@section('title')
    perekaman data
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
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Forms</a></div>
              <div class="breadcrumb-item">Advanced Forms</div>
            </div>
          </div>
          
          <div class="section-body" >
            <div class="container">
              <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                  <div class="card">
                    <div class="card-header">
                      <h4>Profil Anda</h4>
                    </div>
                    <div class="card-body">
                          <div class="row">
                            <div class="form-group col-6">
                              <label for="">NIP</label>
                              <input id="" type="text" class="form-control" value="{{ $user->nip }}">
                            </div>
                            <div class="form-group col-6">
                              <label for="last_name">Nama</label>
                              <input id="last_name" type="text" class="form-control" name="last_name" value="{{ $user->name }}">
                            </div>
                          </div>
        
                          <div class="form-group">
                            <label for="email">Instansi</label>
                            <input id="email" type="text" class="form-control" name="email" value="{{ $user->instansi }}">
                            <div class="invalid-feedback">
                            </div>
                          </div>
        
                          <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                              Edit
                            </button>
                          </div>
                        </form>
                      </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </section>
      </div>
@endsection

@section('script')
    
    {{-- import --}}

    <!-- Page Specific JS File -->
    <script src="{{ asset("assets/js/page/forms-advanced-forms.js")}}"></script>
@endsection