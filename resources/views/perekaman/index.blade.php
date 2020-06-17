@extends('layouts.parent')

@section('title')
    Daftar Objek Baru
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
            <h1 class="text-info">Daftar Objek Baru</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Daftar Objek Baru</a></div>
              <div class="breadcrumb-item">Daftar</div>
            </div>
          </div>

            <div class="section-body">
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-header">
                        <h4>Daftar Objek Baru</h4>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-striped" id="example">
                            <thead>
                              <tr>
                                <th>Nop</th>
                                <th>Nop Asal</th>
                                <th>Pengisi</th>
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
        </section>
      </div>
@endsection

@section('script')

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
            ajax: 'perekaman/json', 
            columns: [
              { data: 'nop',           name: 'nop' },
              { data: 'nop_asal',      name: 'nop_asal'},
              { data: 'user.name',     name: 'user'},
              { data: 'action',        name: 'action'}
            ]
          } );
      } );
    </script>
@endsection