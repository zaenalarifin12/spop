@extends('layouts.parent')

@section('title')
    Daftar Pemutakhiran Anda
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.bootstrap4.min.css">
@endsection

@section('content')
     <!-- Main Content -->
     <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1 class="text-info text-uppercase">Pemutakhiran</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Pemutakhiran</a></div>
              <div class="breadcrumb-item">Daftar</div>
            </div>
          </div>

          <div class="section-body" >
            <div class="section-body">
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-header">
                        <h4>Daftar Pemutakhiran</h4>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-striped" id="example">
                            <thead>
                              <tr>
                                <th>NOP</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                          </table>
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
    
    {{-- import --}}

    <!-- Page Specific JS File -->
    <script src="{{ asset("assets/js/page/forms-advanced-forms.js")}}"></script>

    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>

    <script>
      $(document).ready(function() {
          $('#example').DataTable( {
            processing: true,
            serverSide: true,
            ajax: 'pemutakhiran/json',
            columns: [
          { data: 'nop',                  name: 'nop' },
          { data: 'action',               name: 'action'}
            ]
          } );
      } );
      </script>

@endsection