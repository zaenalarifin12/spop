@extends('layouts.parent')

@section('title')
    Daftar Data Rujukan
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
            <h1 class="text-info">Data Rujukan</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Forms</a></div>
              <div class="breadcrumb-item">Advanced Forms</div>
            </div>
          </div>

          <div class="section-body" >
            <div class="section-body">
    
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-header">
                        <h4>Daftar Rujukan</h4>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-striped" id="example">
                            <thead>
                              <tr>
                                <th>Tahun</th>
                                <th>Nop</th>
                                <th>Nama Subjek Pajak</th>
                                <th>Alamat WP</th>
                                <th>Alamat OP</th>
                                <th>Luas Bumi SPPT</th>
                                <th>Luas Bangunan SPPT</th>
                                <th>NJOP BUMI SPPT</th>
                                <th>NJOP BANGUNAN SPPT</th>
                                <th>PBB</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
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
          ajax: 'rujukan/json',
          columns: [
        { data: 'tahun',              name: 'tahun' },
        { data: 'nop',                name: 'nop'},
        { data: 'nama_subjek_pajak',  name: 'nama_subjek_pajak'},
        { data: 'alamat_wp',          name: 'alamat_wp'},
        { data: 'alamat_op',          name: 'alamat_op'},
        { data: 'luas_bumi_sppt',     name: 'luas_bumi_sppt'},
        { data: 'luas_bng_sppt',      name: 'luas_bng_sppt'},
        { data: 'njop_bumi_sppt',     name: 'njop_bumi_sppt'},
        { data: 'njop_bng_sppt',      name: 'njop_bng_sppt'},
        { data: 'pbb',                name: 'pbb'},
        { data: 'action',             name: 'action'}
          ]
        } );
    } );
    </script>
@endsection