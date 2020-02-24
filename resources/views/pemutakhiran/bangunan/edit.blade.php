@extends('layouts.parent')

@section('title')
    Edit Bangunan
@endsection

@section('style')
    
@endsection

@section('content')
     <!-- Main Content -->
     <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1 class="text-info text-uppercase">Edit Bangunan Data</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Forms</a></div>
              <div class="breadcrumb-item">Advanced Forms</div>
            </div>
          </div>

          <div class="section-body" >
        <form action="{{ url("/pemutakhiran/".$rincianDataBangunan->spop->uuid."/bangunan/".$rincianDataBangunan->uuid) }}" method="post">  
            <h2 class="section-title">Surat Pemberitahuan Objek Pajak</h2>
            <p class="section-lead">Jenis Transaksi <b>edit bangunan<b></p>

            <div class="container-fluid">
  
              <div class="row parent_bangunan">
                <div class="col-12 col-md-12 col-lg-12">
                  <div class="card card-danger">
                    <div class="card-header">
                      <h4>Bangunan</h4>
                    </div>
  
                    <div class="card-body">
                        <div class="alert alert-info">
                          <p class="text-center">Rincian Data Bangunan</p> 
                      </div>
    
                      <div class="form-group">
                        <label class="form-label">
                            Jenis Penggunaan Bangunan
                            @error("penggunaan")
                                <span class="text-danger">Belum di pilih</span>
                            @enderror
                        </label>
                        <div class="selectgroup selectgroup-pills">
                            @foreach ($jenisPenggunaanBangunans as $item)
                                <label class="selectgroup-item">
                                    <input type="radio" name="penggunaan" value="{{ $item->id }}" class="selectgroup-input" 
                                    @if (old("penggunaan") == $item->id)
                                        checked
                                    @elseif($item->id == $rincianDataBangunan->jenisPenggunaanBangunan->id)
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
                                <input type="text" class="form-control @error('luas_bangunan') is-invalid @enderror" name="luas_bangunan" value="{{ old("luas_bangunan") ? old("luas_bangunan") : $rincianDataBangunan->luas_bangunan }}">
                                @error("luas_bangunan")
                                <div class="invalid-feedback"> 
                                    Luas bangunan harus di isi
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Jumlah Lantai</label>
                                <input type="text" class="form-control @error("jumlah_lantai") is-invalid @enderror" name="jumlah_lantai" value="{{ old("jumlah_lantai") ? old("jumlah_lantai") : $rincianDataBangunan->jumlah_lantai }}">
                                @error("jumlah_lantai")
                                    <div class="invalid-feedback"> 
                                        Jumlah Lantai harus di isi
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Tahun Dibangun</label>
                                <input type="text" class="form-control @error('tahun_dibangun') is-invalid @enderror" name="tahun_dibangun" value="{{ old("tahun_dibangun") ? old("tahun_dibangun") : $rincianDataBangunan->tahun_dibangun}}">
                                @error("tahun_dibangun")
                                    <div class="invalid-feedback"> 
                                        Tahun dibangun harus di isi
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Tahun Direnovasi</label>
                                <input type="text" class="form-control @error('tahun_renovasi') is-invalid @enderror" name="tahun_renovasi" value="{{ old("tahun_renovasi") ? old("tahun_renovasi") : $rincianDataBangunan->tahun_renovasi }}" >
                                @error("tahun_renovasi")
                                    <div class="invalid-feedback"> 
                                        tahun renovasi harus di isi
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
  
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Jumlah Bangunan</label>
                                <input type="number" min="1" class="form-control @error('jumlah_bangunan') is-invalid @enderror" name="jumlah_bangunan" value="{{ old("jumlah_bangunan") ? old("jumlah_bangunan") : $rincianDataBangunan->jumlah_bangunan}}">
                                @error("jumlah_bangunan")
                                    <div class="invalid-feedback"> 
                                        Jumlah bangunan harus di isi
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Daya Listrik Terpasang (WATT)</label>
                                <input type="number" min="1" class="form-control @error('daya') is-invalid @enderror" name="daya" value="{{ old("daya") ? old("daya") : $rincianDataBangunan->daya_listrik }}" >
                                @error("daya")
                                    <div class="invalid-feedback"> 
                                        Daya harus di isi
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
  
                    <div class="form-group">
                        <label class="form-label">
                            Kondisi Pada Umumnya
                            @error("kondisi")
                                <span class="text-danger">Belum di pilih</span>
                            @enderror
                        </label>
                        <div class="selectgroup selectgroup-pills">
                            @foreach ($kondisis as $kondisi)
                                <label class="selectgroup-item">
                                    <input type="radio" name="kondisi" value="{{ $kondisi->id }}" class="selectgroup-input" 
                                    @if (old("kondisi") == $kondisi->id)
                                        checked
                                    @elseif($kondisi->id == $rincianDataBangunan->kondisi->id)
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
                            @error("konstruksi")
                                <span class="text-danger">Belum di pilih</span>
                            @enderror
                        </label>
                        <div class="selectgroup selectgroup-pills">
                            @foreach ($konstruksis as $item)
                                <label class="selectgroup-item">
                                    <input type="radio" name="konstruksi" value="{{ $item->id }}" class="selectgroup-input" 
                                    @if (old("konstruksi") == $item->id)
                                        checked
                                    @elseif($item->id == $rincianDataBangunan->konstruksi->id)
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
                            @error("atap")
                                <span class="text-danger">Belum di pilih</span>
                            @enderror
                        </label>
                        <div class="selectgroup selectgroup-pills">
                            @foreach ($ataps as $item)
                                <label class="selectgroup-item">
                                    <input type="radio" name="atap" value="{{ $item->id }}" class="selectgroup-input" 
                                    @if (old("atap") == $item->id)
                                        checked
                                    @elseif($item->id == $rincianDataBangunan->atap->id)
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
                            @error("dinding")
                                <span class="text-danger">Belum di pilih</span>
                            @enderror
                        </label>
                        <div class="selectgroup selectgroup-pills">
                            @foreach ($dindings as $item)
                                <label class="selectgroup-item">
                                    <input type="radio" name="dinding" value="{{$item->id}}" class="selectgroup-input" 
                                    @if (old("dinding") == $item->id)
                                        checked
                                    @elseif($item->id == $rincianDataBangunan->dinding->id)
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
                            @error("lantai")
                                <span class="text-danger">Belum di pilih</span>
                            @enderror
                        </label>
                        <div class="selectgroup selectgroup-pills">
                            @foreach ($lantais as $item)
                                <label class="selectgroup-item">
                                    <input type="radio" name="lantai" value="{{$item->id}}" class="selectgroup-input" 
                                    @if (old("lantai") == $item->id)
                                        checked
                                    @elseif($item->id == $rincianDataBangunan->lantai->id)
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
                            @error("langit")
                                <span class="text-danger">Belum di pilih</span>
                            @enderror
                        </label>
                            <div class="selectgroup selectgroup-pills">
                                @foreach ($langits as $item)
                                    <label class="selectgroup-item">
                                        <input type="radio" name="langit" value="{{$item->id}}" class="selectgroup-input" 
                                        @if (old("langit") == $item->id)
                                            checked
                                        @elseif($item->id == $rincianDataBangunan->langit->id)
                                            checked   
                                        @endif
                                        >
                                        <span class="selectgroup-button">{{$item->nama}}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>

            <div class="container-fluid">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="col-12">
                        <input type="submit" name="action" class="btn btn-dark btn-block" value="Edit" >
                    </div>
                </div>
            </div>
        </form>
          </div>
        </section>
      </div>
@endsection

@section('script')
    
    {{-- import --}}

    <!-- Page Specific JS File -->
    <script src="{{ asset("assets/js/page/forms-advanced-forms.js")}}"></script>
@endsection