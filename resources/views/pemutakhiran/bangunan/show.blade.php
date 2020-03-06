@extends('layouts.parent')

@section('title')
    Data Per Bangunan
@endsection

@section('style')
    
@endsection

@section('content')
     <!-- Main Content -->
     <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1 class="text-info text-uppercase">Bangunan Data</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Forms</a></div>
              <div class="breadcrumb-item">Advanced Forms</div>
            </div>
          </div>

            @if (session("msg"))
                <div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {{ session("msg") }}
                    </div>
                </div>
            @endif
            
          <div class="section-body" >
            <div class="container-fluid">
              <div class="row parent_bangunan">
                <div class="col-12 col-md-12 col-lg-12">
                  <div class="card card-danger">
                    <div class="card-header">
                      <h4>Bangunan </h4>
                    </div>
  
                    <div class="card-body">
                        <div class="alert alert-info">
                          <p class="text-center">Rincian Data Bangunan</p> 
                      </div>
    
                      <div class="form-group">
                        <label class="form-label">
                            Jenis Penggunaan Bangunan
                        </label>
                        <div class="selectgroup selectgroup-pills">
                            @foreach ($jenisPenggunaanBangunans as $item)
                                <label class="selectgroup-item">
                                    <input disabled type="radio" name="penggunaan" value="{{ $item->id }}" class="selectgroup-input" 
                                    @if($item->id == $rincianDataBangunan->jenisPenggunaanBangunan->id)
                                        checked   
                                    @endif
                                    >
                                    <span class="selectgroup-button">{{ $item->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
  
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Luas Bangunan</label>
                                <input disabled type="text" class="form-control" name="luas_bangunan" value="{{ $rincianDataBangunan->luas_bangunan }}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Jumlah Lantai</label>
                                <input disabled type="text" class="form-control" name="jumlah_lantai" value="{{ $rincianDataBangunan->jumlah_lantai }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Tahun Dibangun</label>
                                <input disabled type="text" class="form-control" name="tahun_dibangun" value="{{ $rincianDataBangunan->tahun_dibangun }}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Tahun Direnovasi</label>
                                <input disabled type="text" class="form-control" name="tahun_renovasi" value="{{ $rincianDataBangunan->tahun_renovasi }}" >
                            </div>
                        </div>
                    </div>
  
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Daya Listrik Terpasang (WATT)</label>
                                <input disabled type="number" min="1" class="form-control" name="daya" value="{{ $rincianDataBangunan->daya_listrik }}" >
                            </div>
                        </div>
                    </div>
  
                    <div class="form-group">
                        <label class="form-label">
                            Kondisi Pada Umumnya
                        </label>
                        <div class="selectgroup selectgroup-pills">
                            @foreach ($kondisis as $kondisi)
                                <label class="selectgroup-item">
                                    <input disabled type="radio" name="kondisi" value="{{ $kondisi->id }}" class="selectgroup-input" 
                                    @if($kondisi->id == $rincianDataBangunan->kondisi->id)
                                        checked   
                                    @endif
                                    >
                                    <span class="selectgroup-button">{{ $kondisi->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
  
                    <div class="form-group">
                        <label class="form-label">
                            Konstruksi
                        </label>
                        <div class="selectgroup selectgroup-pills">
                            @foreach ($konstruksis as $item)
                                <label class="selectgroup-item">
                                    <input disabled type="radio" name="konstruksi" value="{{ $item->id }}" class="selectgroup-input" 
                                    @if($item->id == $rincianDataBangunan->konstruksi->id)
                                        checked   
                                    @endif
                                    >
                                    <span class="selectgroup-button">{{ $item->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
  
                    <div class="form-group">
                        <label class="form-label">
                            Atap
                        </label>
                        <div class="selectgroup selectgroup-pills">
                            @foreach ($ataps as $item)
                                <label class="selectgroup-item">
                                    <input disabled type="radio" name="atap" value="{{ $item->id }}" class="selectgroup-input" 
                                    @if($item->id == $rincianDataBangunan->atap->id)
                                        checked   
                                    @endif
                                    >
                                    <span class="selectgroup-button">{{ $item->nama }}</span>
                                </label>
                            @endforeach 
                        </div>
                    </div>
  
                    <div class="form-group">
                        <label class="form-label">
                            Dinding
                        </label>
                        <div class="selectgroup selectgroup-pills">
                            @foreach ($dindings as $item)
                                <label class="selectgroup-item">
                                    <input disabled type="radio" name="dinding" value="{{$item->id}}" class="selectgroup-input" 
                                    @if($item->id == $rincianDataBangunan->dinding->id)
                                        checked   
                                    @endif
                                    >
                                    <span class="selectgroup-button">{{$item->nama}}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
  
                    <div class="form-group">
                        <label class="form-label">
                            Lantai
                        </label>
                        <div class="selectgroup selectgroup-pills">
                            @foreach ($lantais as $item)
                                <label class="selectgroup-item">
                                    <input disabled type="radio" name="lantai" value="{{$item->id}}" class="selectgroup-input" 
                                    @if($item->id == $rincianDataBangunan->lantai->id)
                                        checked   
                                    @endif
                                    >
                                    <span class="selectgroup-button">{{$item->nama}}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
  
                    <div class="form-group">
                        <label class="form-label">
                            LANGIT-LANGIT
                        </label>
                            <div class="selectgroup selectgroup-pills">
                                @foreach ($langits as $item)
                                    <label class="selectgroup-item">
                                        <input disabled type="radio" name="langit" value="{{$item->id}}" class="selectgroup-input" 
                                        @if($item->id == $rincianDataBangunan->langit->id)
                                            checked   
                                        @endif
                                        >
                                        <span class="selectgroup-button">{{$item->nama}}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <a href="{{ url("/pemutakhiran/".$rincianDataBangunan->spop->uuid) }}" class="btn btn-info btn-block">Kembali</a>
                                </div>
                                <div class="col">
                                    <a href="{{ url("/pemutakhiran/".$rincianDataBangunan->spop->uuid."/bangunan/create") }}" class="btn btn-success btn-block">Tambah Bangunan baru</a>
                                </div>
                            </div>
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
@endsection