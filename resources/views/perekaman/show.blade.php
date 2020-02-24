@extends('layouts.parent')

@section('title')
    Perekaman Data
@endsection

@section('style')
    
@endsection

@section('content')
     <!-- Main Content -->
     <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1 class="text-info text-uppercase">Perekaman Data</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Home</a></div>
              <div class="breadcrumb-item"><a href="#">Perekaman</a></div>
              <div class="breadcrumb-item">Lihat</div>
            </div>
          </div>
          
          
          <div class="section-body" >
            <div class="container">
                <div class="col-12 col-md-12 col-lg-12">

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

                    <div class="card">
                      <div class="card-header">
                        <h4>Data Perekaman</h4>
                      </div>
                      <div class="card-body">
                        <div id="accordion">
                          <div class="accordion">
                            <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-1" aria-expanded="true">
                              <h4>Data SPOP</h4>
                            </div>
                            <div class="accordion-body collapse show" id="panel-body-1" data-parent="#accordion">
                                <div class="row">
                                    <div class="col-12 col-md-12 col-lg-12">
                                      <div class="card">
                                        <div class="card-body">

                                        <div class="form-group">
                                        <label>NOP</label>
                                            <div class="input-group">                            
                                                <input type="text" disabled name="pr"        required class="form-control" value="{{ substr($spop->nop, 0,2) }}">
                                                <input type="text" disabled name="dt"        required class="form-control" value="{{ substr($spop->nop, 2,2) }}">
                                                <input type="text" disabled name="kec"       required class="form-control" value="{{ substr($spop->nop, 4,3) }}">
                                                <input type="text" disabled name="des"       required class="form-control" value="{{ substr($spop->nop, 7,3) }}">
                                                <input type="text" disabled name="blok"      required class="form-control" value="{{ substr($spop->nop, 10,3) }}">
                                                <input type="text" disabled name="no_urut"   required class="form-control" value="{{ substr($spop->nop, 13,4) }}">
                                                <input type="text" disabled name="kode"      required class="form-control" value="{{ substr($spop->nop, 17,18) }}">
                                            </div>
                                        </div>

                                          <div class="form-group">
                                            <label>NOP</label>
                                                <div class="input-group">                            
                                                    <input type="text" disabled name="pr"        required class="form-control" value="{{ substr($spop->nop_asal, 0,2) }}">
                                                    <input type="text" disabled name="dt"        required class="form-control" value="{{ substr($spop->nop_asal, 2,2) }}">
                                                    <input type="text" disabled name="kec"       required class="form-control" value="{{ substr($spop->nop_asal, 4,3) }}">
                                                    <input type="text" disabled name="des"       required class="form-control" value="{{ substr($spop->nop_asal, 7,3) }}">
                                                    <input type="text" disabled name="blok"      required class="form-control" value="{{ substr($spop->nop_asal, 10,3) }}">
                                                    <input type="text" disabled name="no_urut"   required class="form-control" value="{{ substr($spop->nop_asal, 13,4) }}">
                                                    <input type="text" disabled name="kode"      required class="form-control" value="{{ substr($spop->nop_asal, 17,18) }}">
                                                </div>
                                          </div>
                        
                                            <div class="alert alert-info">
                                                <p class="text-center">Data Letak Objek Pajak</p> 
                                            </div>

                                            <div class="form-group">
                                                <label>Nama Jalan</label>
                                                <textarea disabled name="dlop_nama_jalan" class="form-control"  cols="30" rows="10">{{ $spop->dataLetakObjek->nama_jalan }}</textarea>
                                            </div>
                        
                                            <div class="form-group">
                                                <label>Blok / KAV Nomor</label>
                                                <textarea disabled name="dlop_blok" class="form-control"  cols="30" rows="10">{{ $spop->dataLetakObjek->blok_kav }}</textarea>
                                            </div>
                        
                                            <div class="ui-widget form-group">
                                                <label for="kab">Desa: </label>
                                                <input id="kab" disabled class="form-control" value="{{ $spop->dataLetakObjek->desa->nama }}" />
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>RW</label>
                                                        <input disabled type="text" class="form-control" name="dlop_rw"  value="{{ $spop->dataLetakObjek->rw }}">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>RT</label>
                                                        <input type="text" disabled class="form-control" name="dlop_rt"  value="{{ $spop->dataLetakObjek->rt }}">
                                                    </div>
                                                </div>
                                            </div>
                                     
                                            <div class="alert alert-info">
                                                <p class="text-center">Data Subjek Pajak</p> 
                                            </div>
                      
                                            <div class="form-group">
                                                <label class="form-label">Status</label>
                                                <div class="selectgroup selectgroup-pills">
                                                    @foreach ($statuses as $status)
                                                        <label class="selectgroup-item">
                                                            <input type="radio" disabled name="status" value="{{ $status->id }}" class="selectgroup-input" {{ $spop->dataSubjekPajak->status->id == $status->id ? "checked" : null}}>
                                                            <span class="selectgroup-button">{{ $status->nama }}</span>
                                                        </label>      
                                                    @endforeach
                                                </div>
                                            </div>
                      
                                            <div class="form-group">
                                                <label class="form-label">Pekerjaan</label>
                                                <div class="selectgroup selectgroup-pills">
                                                    @foreach ($pekerjaans as $pekerjaan)
                                                        <label class="selectgroup-item">
                                                            <input disabled type="radio" name="pekerjaan" value="{{ $pekerjaan->id}}" class="selectgroup-input" {{ $spop->dataSubjekPajak->status->id == $pekerjaan->id ? "checked" : null}}>
                                                            <span class="selectgroup-button">{{ $pekerjaan->nama}}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                      
                                          <div class="form-group">
                                              <label>Nama Subjek Pajak</label>
                                              <input type="text" disabled class="form-control" name="dsp_nama_subjek_pajak"  value="{{$spop->dataSubjekPajak->nama_subjek_pajak}}">
                                          </div>
                      
                                          <div class="form-group">
                                              <label>Nama Jalan</label>
                                              <input type="text" disabled class="form-control" name="dsp_nama_jalan"  value="{{ $spop->dataSubjekPajak->nama_jalan }}">
                                          </div>
                      
                                        <div class="ui-widget form-group">
                                            <label for="desa_">Desa: </label>
                                            <input id="desa_" disabled class="form-control" value="{{ $spop->dataSubjekPajak->desa }}" />
                                        </div>
                      
                                        <div class="ui-widget form-group">
                                            <label for="kab">Kabupaten: </label>
                                            <input id="kab" disabled class="form-control" value="{{ $spop->dataSubjekPajak->kabupaten }}" />
                                        </div>
                      
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>RW</label>
                                                    <input type="text" disabled class="form-control" name="dsp_rw"  value="{{ $spop->dataSubjekPajak->rw }}">
                                                </div>
                                              </div>
                                              <div class="col">
                                                  <div class="form-group">
                                                      <label>RT</label>
                                                      <input type="text" disabled class="form-control" name="dsp_rt"  value="{{ $spop->dataSubjekPajak->rt }}" >
                                                  </div>
                                              </div>
                                          </div>
                                          
                                          <div class="form-group">
                                              <label>Nomor KTP</label>
                                              <input type="text" disabled class="form-control" name="dsp_no_ktp"  value="{{ $spop->dataSubjekPajak->nomor_ktp }}">
                                          </div>
                                          
                                          <div class="alert alert-info">
                                              <p class="text-center">Data Tanah</p> 
                                          </div>
                      
                                          <div class="form-group">
                                              <label>Luas Tanah</label>
                                              <input type="text" disabled class="form-control" name="dsp_luas_tanah"  value="{{ $spop->dataTanah->luas_tanah }}">
                                          </div>

                                          {{-- relasi data tanah masih belum --}}
                                          <div class="form-group">
                                              <label class="form-label">Jenis Tanah</label>
                                              <div class="selectgroup selectgroup-pills">
                                                @foreach ($jenisTanah as $item)
                                                    <label class="selectgroup-item">
                                                        <input type="radio" disabled name="jenis_tanah" value="{{ $item->id }}" class="selectgroup-input tanah" {{ $spop->dataTanah->jenis_tanah_id == $item->id ? "checked" : null}} />
                                                        <span class="selectgroup-button" >{{ $item->nama }}</span>
                                                    </label>
                                                @endforeach
                                              </div>
                                          </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col">
                                                    <a href="{{ url("perekaman/$spop->uuid/edit") }}" class="btn btn-primary btn-block">Edit</a>
                                                </div>
                                            </div>
                                        </div>
                      
                                          <!-- for tanah dan bangunan -->
                                          <div class="collapse" id="collapseExample">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                            </div>
                          </div>
                        
                        @foreach ($spop->rincianDataBangunans as $rincianDataBangunan)
                        <div class="accordion">
                            <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-{{$loop->iteration+1}}">
                              <h4>Bangunan {{$loop->iteration}}</h4>
                            </div>
                            <div class="accordion-body collapse" id="panel-body-{{$loop->iteration+1}}" data-parent="#accordion">
                                <div class="col-12 col-md-12 col-lg-12">
                                    <div class="card card-info">
                                      <div class="card-header">
                                        <h4>Bangunan Ke - {{$loop->iteration}}</h4>
                                      </div>
                                      <div class="card-body">
                                        
                                        <div class="alert alert-info">
                                            <p class="text-center">Rincian Data Bangunan</p> 
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Jenis Penggunaan Bangunan</label>
                                            <div class="selectgroup selectgroup-pills">
                                                @foreach ($jenisPenggunaanBangunans as $item)
                                                    <label class="selectgroup-item">
                                                        <input type="radio" disabled name="penggunaan{{ $rincianDataBangunan->id }}" value="{{ $item->id }}" class="selectgroup-input" {{ $rincianDataBangunan->jenisPenggunaanBangunan->id == $item->id ? "checked" : null}} />
                                                        <span class="selectgroup-button">{{ $item->nama }}</span>
                                                    </label>    
                                                @endforeach
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Luas Bangunan</label>
                                                    <input type="text" disabled class="form-control" name="luas_bangunan" value="{{ $rincianDataBangunan->luas_bangunan}}">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Jumlah Lantai</label>
                                                    <input type="text" disabled class="form-control" name="jumlah_lantai" value="{{ $rincianDataBangunan->jumlah_lantai}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Tahun Dibangun</label>
                                                    <input type="text" disabled class="form-control" name="tahun_dibangun" value="{{$rincianDataBangunan->tahun_dibangun}}">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Tahun Direnovasi</label>
                                                    <input type="text" disabled class="form-control" name="tahun_renovasi"  value="{{$rincianDataBangunan->tahun_renovasi}}">
                                                </div>
                                            </div>
                                        </div>
                        
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Jumlah Bangunan</label>
                                                    <input type="text" disabled class="form-control" name="jumlah_bangunan" value="{{ $rincianDataBangunan->jumlah_bangunan }}" >
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Daya Listrik Terpasang (WATT)</label>
                                                    <input type="text" disabled  class="form-control" name="daya" value="{{$rincianDataBangunan->daya_listrik}}">
                                                </div>
                                            </div>
                                        </div>
                        
                                        <div class="form-group">
                                            <label class="form-label">Kondisi Pada Umumnya</label>
                                            <div class="selectgroup selectgroup-pills">
                                                @foreach ($kondisis as $item)
                                                    <label class="selectgroup-item">
                                                        <input type="radio" disabled name="kondisi{{ $rincianDataBangunan->id }}" value="{{ $item->id }}" class="selectgroup-input" {{ $rincianDataBangunan->kondisi->id == $item->id ? "checked" : null}}>
                                                        <span class="selectgroup-button">{{ $item->nama }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                        
                                        <div class="form-group">
                                            <label class="form-label">Konstruksi</label>
                                            <div class="selectgroup selectgroup-pills">
                                                @foreach ($konstruksis as $item)
                                                    <label class="selectgroup-item">
                                                        <input type="radio" disabled name="konstruksi{{ $rincianDataBangunan->id }}" value="{{ $item->id }}" class="selectgroup-input" {{ $rincianDataBangunan->konstruksi->id == $item->id ? "checked" : null}}>
                                                        <span class="selectgroup-button">{{ $item->nama }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                        
                                        <div class="form-group">
                                            <label class="form-label">Atap</label>
                                            <div class="selectgroup selectgroup-pills">
                                                @foreach ($ataps as $item)
                                                    <label class="selectgroup-item">
                                                        <input type="radio" disabled name="atap{{ $rincianDataBangunan->id }}" value="{{ $item->id }}" class="selectgroup-input" {{ $rincianDataBangunan->atap->id == $item->id ? "checked" : null}}>
                                                        <span class="selectgroup-button">{{ $item->nama }}</span>
                                                    </label>
                                                @endforeach 
                                            </div>
                                        </div>
                    
                                        <div class="form-group">
                                            <label class="form-label">Dinding</label>
                                            <div class="selectgroup selectgroup-pills">
                                                @foreach ($dindings as $item)
                                                    <label class="selectgroup-item">
                                                        <input type="radio" disabled name="dinding{{ $rincianDataBangunan->id }}" value="{{$item->id}}" class="selectgroup-input" {{ $rincianDataBangunan->dinding->id == $item->id ? "checked" : null}}>
                                                        <span class="selectgroup-button">{{$item->nama}}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                    
                                        <div class="form-group">
                                            <label class="form-label">Lantai</label>
                                            <div class="selectgroup selectgroup-pills">
                                                @foreach ($lantais as $item)
                                                    <label class="selectgroup-item">
                                                        <input type="radio" disabled name="lantai{{ $rincianDataBangunan->id }}" value="{{$item->id}}" class="selectgroup-input" {{ $rincianDataBangunan->lantai->id == $item->id ? "checked" : null}}>
                                                        <span class="selectgroup-button">{{$item->nama}}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                        
                                        <div class="form-group">
                                            <label class="form-label">Langit-langit</label>
                                                <div class="selectgroup selectgroup-pills">
                                                    @foreach ($langits as $item)
                                                        <label class="selectgroup-item">
                                                            <input type="radio" disabled name="langit{{ $rincianDataBangunan->id }}" value="{{$item->id}}" class="selectgroup-input" {{ $rincianDataBangunan->langit->id == $item->id ? "checked" : null}}>
                                                            <span class="selectgroup-button">{{$item->nama}}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col">
                                                    <form action="{{ url("perekaman/$spop->uuid/bangunan/$rincianDataBangunan->uuid") }}" method="post" style="display:inline">
                                                        <button type="submit" class="btn btn-danger btn-block">Hapus</button>
                                                        @csrf
                                                        @method("DELETE")
                                                    </form>
                                                </div>
                                                <div class="col">
                                                    <a href="{{ url("perekaman/$spop->uuid/bangunan/$rincianDataBangunan->uuid/edit") }}" class="btn btn-info btn-block">Edit</a>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                        @if ($spop->dataTanah->jenis_tanah_id == 1)
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <a href="{{ url("/perekaman/$spop->uuid/bangunan/create") }}" class="btn btn-info btn-block">Tambah Bngunan bangunan</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <a href="{{ url("/") }}" class="btn btn-info btn-block">Home</a>
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