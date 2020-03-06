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
        <h1 class="text-info">Daftar Perubahan Data</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
          <div class="breadcrumb-item"><a href="#">Forms</a></div>
          <div class="breadcrumb-item">Advanced Forms</div>
        </div>
      </div>

      @if (session("err"))
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
            <button class="close" data-dismiss="alert">
                <span>×</span>
            </button>
            {{ session("err") }}
            </div>
        </div>
    @endif

      <div class="section-body" >
        <div class="container">
          <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
              <div class="card">
                  <div class="card-body">
                      <div class="row">
                          <div class="form-group">
                            <label>Cari NOP</label>
                              <form action="{{ url("/pemutakhiran/cari") }}" method="get">
                                <div class="input-group mb-2">
                                  <input type="text" name="pr"      class="form-control" disabled value="33"/>
                                  <input type="text" name="dtii"    class="form-control" disabled value="18"/>
                                  <input type="text" name="kec"     autocomplete="off" pattern=".{3,3}" minlength="3" maxlength="3" class="form-control" value="{{ old("kec") }}"/>
                                  <input type="text" name="des"     autocomplete="off" pattern=".{3,3}" minlength="3" maxlength="3" class="form-control" value="{{ old("des") }}"/>
                                  <input type="text" name="blok"    autocomplete="off" pattern=".{3,3}" minlength="3" maxlength="3" class="form-control" value="{{ old("blok") }}"/>
                                  <input type="text" name="no_urut" autocomplete="off" pattern=".{4,4}" minlength="4" maxlength="4" class="form-control" value="{{ old("no_urut") }}"/>
                                  <input type="text" name="kode"    autocomplete="off" pattern=".{1,1}" minlength="1" maxlength="1" class="form-control" value="{{ old("kode") }}"/>
                                </div>
                              <button type="submit" class="form-control btn btn-outline-primary">Cari </button>
                              </form>
                          </div>
                      </div>
                      @if (isset($rujukan))

                            
                                <p>Data Rujukan</p>
                            <div class="form-group">
                              <label>Tahun</label>
                              <input type="text" disabled class="form-control" value="{{ $rujukan->tahun }}">
                            </div>

                            <div class="form-group">
                              <label>NOP</label>
                              <input type="text" disabled class="form-control" value="{{ $rujukan->nop }}">
                            </div>

                            <div class="form-group">
                                <label>Nama Subjek Pajak</label>
                                <textarea disabled class="form-control" cols="30" rows="10">{{ $rujukan->nama_subjek_pajak }}</textarea>
                            </div>
          
                            <div class="form-group">
                              <label>Alamat wajib pajak</label>
                              <textarea disabled class="form-control" cols="30" rows="10">{{ $rujukan->alamat_wp }}</textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Alamat Objek Pajak</label>
                                <input type="text" disabled class="form-control" value="{{ $rujukan->alamat_op }}">
                            </div>
        
                            <div class="form-group">
                                <label>Luas Bumi SPPT</label>
                                <input type="text" disabled class="form-control" value="{{ $rujukan->luas_bumi_sppt }}">
                            </div>
        
                            <div class="form-group">
                              <label>Luas Bangunan SPPT</label>
                              <input type="text" disabled class="form-control" value="{{ $rujukan->luas_bng_sppt }}">
                            </div>
                          
                            <div class="form-group">
                                <label>NJOP Bumi SPPT</label>
                                <input type="text" disabled class="form-control" value="{{ $rujukan->njop_bumi_sppt}}">
                            </div>

                            <div class="form-group">
                              <label>PBB</label>
                              <input type="text" disabled class="form-control" value="{{ $rujukan->pbb }}">
                            </div>

                            <div class="form-group">
                              <a href="{{ url("/pemutakhiran/create/$rujukan->uuid") }}" class="btn btn-primary btn-block">Usulan perubahan</a>
                            </div>
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