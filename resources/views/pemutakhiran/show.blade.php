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
            <h1 class="text-info text-uppercase">Perekaman Data</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Forms</a></div>
              <div class="breadcrumb-item">Advanced Forms</div>
            </div>
          </div>
          
          {{ dd($spop) }}
          <div class="section-body" >
            <h2 class="section-title">Surat Pemberitahuan Objek Pajak</h2>
            <p class="section-lead">Jenis Transaksi <b>Pemutakhiran Data<b></p>

            <div class="container">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                      <div class="card-header">
                        <h4>Data </h4>
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
                                        <div class="card-header">
                                          <h4>Data</h4>
                                        </div>
                                        <div class="card-body">
                                          <div class="form-group">
                                            <label>NOP</label>
                                            <input type="text" name="nop" required class="form-control" value="{{ $spop->nop }}">
                                          </div>
                      
                                          <div class="alert alert-info">
                                              <p class="text-center">Data Letak Objek Pajak</p> 
                                          </div>

                                            <div class="form-group">
                                                <label>Nama Jalan</label>
                                                {{ dd($spop->dataLetakObjek) }}
                                                <textarea name="dlop_nama_jalan" class="form-control"  cols="30" rows="10">{{ $spop->dataLetakObjek->nama_jalan }}</textarea>
                                            </div>
                        
                                            <div class="form-group">
                                                <label>Blok / KAV Nomor</label>
                                                <textarea name="dlop_blok" class="form-control"  cols="30" rows="10">{{ $spop->dataLetakObjek->blok_kav }}</textarea>
                                            </div>
                        
                                            <div class="form-group">
                                                <label>Kecamatan</label>
                                                <select class="form-control" name="dlop_kecamatan">
                                                <option>Kecamatan 1</option>
                                                <option>Kecamatan 2</option>
                                                <option>Kecamatan 3</option>
                                                </select>
                                            </div>
                        
                                            <div class="form-group">
                                                <label>Desa</label>
                                                <select class="form-control" name="dlop_desa">
                                                <option>Desa 1</option>
                                                <option>Desa 2</option>
                                                <option>Desa 3</option>
                                                </select>
                                            </div>
                        
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>RW</label>
                                                        <input type="text" class="form-control" name="dlop_rw"  value="{{ $spop->dataLetakObjek->rw }}">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>RT</label>
                                                        <input type="text" class="form-control" name="dlop_rt"  value="{{ $spop->dataLetakObjek->rt }}">
                                                    </div>
                                                </div>
                                            </div>
                                     
                                          {{-- {{ dd($spop) }} --}}
                                          <div class="alert alert-info">
                                             <p class="text-center">Data Subjek Pajak</p> 
                                          </div>
                      
                                          <div class="form-group">
                                              <label class="form-label">Status</label>
                                              <div class="selectgroup selectgroup-pills">
                                                  @foreach ($statuses as $status)
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="status" value="{{ $status->id }}" class="selectgroup-input">
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
                                                        <input type="radio" name="pekerjaan" value="{{ $pekerjaan->id}}" class="selectgroup-input" >
                                                        <span class="selectgroup-button">{{ $pekerjaan->nama}}</span>
                                                    </label>
                                                  @endforeach
                                              </div>
                                          </div>
                      
                                          <div class="form-group">
                                              <label>Nama Subjek Pajak</label>
                                              <input type="text" class="form-control" name="dsp_nama_subjek_pajak"  value="{{$spop->dataSubjekPajak->nama_subjek_pajak}}">
                                          </div>
                      
                                          <div class="form-group">
                                              <label>Nama Jalan</label>
                                              <input type="text" class="form-control" name="dsp_nama_jalan"  value="{{ $spop->dataSubjekPajak->nama_jalan }}">
                                          </div>
                      
                                          <div class="form-group">
                                              <label>Kecamatan</label>
                                              <select class="form-control" name="dsp_kecamatan">
                                                <option>Kecamatan 1</option>
                                                <option>Kecamatan 2</option>
                                                <option>Kecamatan 3</option>
                                              </select>
                                          </div>
                      
                                          <div class="form-group">
                                              <label>Desa</label>
                                              <select class="form-control" name="dsp_desa">
                                                <option>Desa 1</option>
                                                <option>Desa 2</option>
                                                <option>Desa 3</option>
                                              </select>
                                          </div>
                      
                                          <div class="row">
                                              <div class="col">
                                                  <div class="form-group">
                                                      <label>RW</label>
                                                      <input type="text" class="form-control" name="dsp_rw"  value="{{ $spop->dataSubjekPajak->rw }}">
                                                  </div>
                                              </div>
                                              <div class="col">
                                                  <div class="form-group">
                                                      <label>RT</label>
                                                      <input type="text" class="form-control" name="dsp_rt"  {{ $spop->dataSubjekPajak->rt }}>
                                                  </div>
                                              </div>
                                          </div>
                                          
                                          <div class="form-group">
                                              <label>Nomor KTP</label>
                                              <input type="text" class="form-control" name="dsp_no_ktp"  value="{{ $spop->dataSubjekPajak->nomor_ktp }}">
                                          </div>
                                          
                                          <div class="alert alert-info">
                                              <p class="text-center">Data Tanah</p> 
                                          </div>
                      
                                          <div class="form-group">
                                              <label>Luas Tanah</label>
                                              {{-- <input type="text" class="form-control" name="dsp_luas_tanah"  value="{{$spop->dataTanah->luas_tanah}}"> --}}
                                          </div>
                      
                                          <div class="form-group">
                                              <label class="form-label">Jenis Tanah</label>
                                              <div class="selectgroup selectgroup-pills">
                                                <label class="selectgroup-item">
                                                  <input id="tanah" type="radio" name="jenis_tanah" value="1" class="selectgroup-input">
                                                  <span  class="selectgroup-button" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Tanah + Bangunan</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                  <input type="radio" name="jenis_tanah" value="2" class="selectgroup-input tanah">
                                                  <span class="selectgroup-button" >Kavling Siap Bangun</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                  <input type="radio" name="jenis_tanah" value="3" class="selectgroup-input tanah">
                                                  <span class="selectgroup-button" >Tanah Kosong</span>
                                                </label>
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
                        
                        @foreach ($spop->rincianDataBangunans as $item)
                        <div class="accordion">
                            <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-{{$loop->iteration}}">
                              <h4>Bangunan {{$loop->iteration}}</h4>
                            </div>
                            <div class="accordion-body collapse" id="panel-body-{{$loop->iteration}}" data-parent="#accordion">
                                <div class="col-12 col-md-12 col-lg-12">
                                    <div class="card card-danger">
                                      <div class="card-header">
                                        <h4>Bangunan Ke - {{$loop->iteration}}</h4>
                                      </div>
                                      <div class="card-body">
                                        
                                        <div class="alert alert-info">
                                            <p class="text-center">Rincian Data Bangunan</p> 
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Icon input</label>
                                            <div class="selectgroup selectgroup-pills">
                                              <label class="selectgroup-item">
                                                <input type="radio" name="icon-input" value="1" class="selectgroup-input" checked="">
                                                <span class="selectgroup-button selectgroup-button-icon"><i class="fas fa-sun"></i></span>
                                              </label>
                                              <label class="selectgroup-item">
                                                <input type="radio" name="icon-input" value="2" class="selectgroup-input">
                                                <span class="selectgroup-button selectgroup-button-icon"><i class="fas fa-moon"></i></span>
                                              </label>
                                              <label class="selectgroup-item">
                                                <input type="radio" name="icon-input" value="3" class="selectgroup-input">
                                                <span class="selectgroup-button selectgroup-button-icon"><i class="fas fa-cloud-rain"></i></span>
                                              </label>
                                              <label class="selectgroup-item">
                                                <input type="radio" name="icon-input" value="4" class="selectgroup-input">
                                                <span class="selectgroup-button selectgroup-button-icon"><i class="fas fa-cloud"></i></span>
                                              </label>
                                            </div>
                                          </div>
                                        <div class="form-group">
                                            <label class="form-label">Jenis Penggunaan Bangunan</label>
                                            <div class="selectgroup selectgroup-pills">
                                                @foreach ($jenisPenggunaanBangunans as $items)
                                                    @if ($item->jenisPenggunaanBangunan->id == $items->id)
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="penggunaan" value="{{ $items->id }}" class="selectgroup-input" checked="checked">
                                                            <span class="selectgroup-button">{{ $items->nama }}</span>
                                                        </label>
                                                    @else
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="penggunaan" value="{{ $items->id }}" class="selectgroup-input" checked="checked">
                                                            <span class="selectgroup-button">{{ $items->nama }}</span>
                                                        </label>    
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Luas Bangunan</label>
                                                    <input type="text" class="form-control" name="luas_bangunan" >
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Jumlah Lantai</label>
                                                    <input type="text" class="form-control" name="jumlah_lantai" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Tahun Dibangun</label>
                                                    <input type="text" class="form-control" name="tahun_dibangun" >
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Tahun Direnovasi</label>
                                                    <input type="text" class="form-control" name="tahun_renovasi"  >
                                                </div>
                                            </div>
                                        </div>
                        
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Jumlah Bangunan</label>
                                                    <input type="text" class="form-control" name="jumlah_bangunan"  >
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Daya Listrik Terpasang (WATT)</label>
                                                    <input type="text" class="form-control" name="daya"  >
                                                </div>
                                            </div>
                                        </div>
                        
                                        <div class="form-group">
                                            <label class="form-label">Kondisi Pada Umumnya</label>
                                            <div class="selectgroup selectgroup-pills">
                                                @foreach ($kondisis as $kondisi)
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="kondisi" value="{{ $kondisi->id }}" class="selectgroup-input" >
                                                        <span class="selectgroup-button">{{ $kondisi->nama }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                        
                                        <div class="form-group">
                                            <label class="form-label">Konstruksi</label>
                                            <div class="selectgroup selectgroup-pills">
                                                @foreach ($konstruksis as $item)
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="konstruksi" value="{{ $item->id }}" class="selectgroup-input">
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
                                                        <input type="radio" name="atap" value="{{ $item->id }}" class="selectgroup-input">
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
                                                        <input type="radio" name="dinding" value="{{$item->id}}" class="selectgroup-input">
                                                        <span class="selectgroup-button">{{$item->nama}}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                    
                                        <div class="form-group">
                                            <label class="form-label">LANTAI</label>
                                            <div class="selectgroup selectgroup-pills">
                                                @foreach ($lantais as $item)
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="lantai" value="{{$item->id}}" class="selectgroup-input">
                                                        <span class="selectgroup-button">{{$item->nama}}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                        
                                        <div class="form-group">
                                            <label class="form-label">LANGIT-LANGIT</label>
                                                <div class="selectgroup selectgroup-pills">
                                                    @foreach ($langits as $item)
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="langit" value="{{$item->id}}" class="selectgroup-input">
                                                            <span class="selectgroup-button">{{$item->nama}}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col">
                                                    <button type="submit" class="btn btn-danger btn-block">Hapus</button>
                                                </div>
                                                <div class="col">
                                                    <button type="submit" class="btn btn-info btn-block">Edit</button>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                          <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-info btn-block">Add bangunan</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-info btn-block">Home</button>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-success btn-block">Tambah Nop Baru</button>
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