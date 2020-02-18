@extends('layouts.parent')

@section('title')
    Cari Nop
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.bootstrap4.min.css">
@endsection

@section('content')


<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1 class="text-info">Home</h1>
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
                  <div class="card-body">
                      <div class="row">
                          <div class="form-group">
                            <label>Pick Your Color</label>
                              <form action="{{ url("/pemutakhiran/cari") }}" method="get" class="input-group">
                                <input type="text" name="search" class="form-control" />
                                <div class="input-group-append">
                                    <button type="submit" class="form-control">Cari </button>
                                </div>
                              </form>
                          </div>
                      </div>
                      @if (isset($rujukan))
                          lakslakslkas
                      @endif

                    </div>
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

    <!-- Page Specific JS File -->
    <script src="{{ asset("assets/js/page/forms-advanced-forms.js")}}"></script>
@endsection