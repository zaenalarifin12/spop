@extends('layouts.parent')

@section('title')
    Edit Data objek pajak
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset("assets/node/select2.min.css")}}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

@endsection

@section('content')
     <!-- Main Content -->
     <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1 class="text-info text-uppercase">Edit Data objek pajak</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Home</a></div>
              <div class="breadcrumb-item"><a href="#">objek pajak</a></div>
              <div class="breadcrumb-item">Edit</div>
            </div>
          </div>

          <div class="section-body" >
        <form action="{{ url("/perekaman/". $spop->uuid) }}" method="post" enctype="multipart/form-data">  
            <div class="container-fluid" id="parent">
              <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    @if (session("msg"))
                        <div class="alert alert-danger alert-dismissible show fade">
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
                      <h4>Objek Pajak </h4>
                    </div>
                    <div class="card-body">

                    @if (Auth::user()->role == 1)
                        <div class="form-group">
                            <label>NOP</label>
                            <div class="input-group mb-2">
                                <input type="text" name="pr"      class="form-control" disabled value="33"/>
                                <input type="text" name="dtii"    class="form-control" disabled value="18"/>
                                <input type="text" name="nop_kec"     autocomplete="off" pattern=".{3,3}" minlength="3" maxlength="3" class="form-control" value="{{ old("kec")     ? old("kec")    : substr($spop->nop, 4,3) }}"/>
                                <input type="text" name="nop_des"     autocomplete="off" pattern=".{3,3}" minlength="3" maxlength="3" class="form-control" value="{{ old("des")     ? old("des")    : substr($spop->nop, 7,3) }}"/>
                                <input type="text" name="nop_blok"    autocomplete="off" pattern=".{3,3}" minlength="3" maxlength="3" class="form-control" value="{{ old("blok")    ? old("blok")   : substr($spop->nop, 10,3) }}"/>
                                <input type="text" name="nop_no_urut" autocomplete="off" pattern=".{4,4}" minlength="4" maxlength="4" class="form-control" value="{{ old("no_urut") ? old("no_urut"): substr($spop->nop, 13,4) }}"/>
                                <input type="text" name="nop_kode"    autocomplete="off" pattern=".{1,1}" minlength="1" maxlength="1" class="form-control" value="{{ old("kode")    ? old("kode")   : substr($spop->nop, 17,18) }}"/>
                            </div>
                        </div>
                    @else
                    <div class="form-group">
                        <label>NOP</label>
                        <div class="input-group mb-2">
                            <input type="text" name="pr"          class="form-control" disabled value="33"/>
                            <input type="text" name="dtii"        class="form-control" disabled value="18"/>
                            <input type="text" name="nop_kec"     pattern=".{3,3}" minlength="3" maxlength="3" class="form-control" disabled value="{{ old("kec") }}" />
                            <input type="text" name="nop_des"     pattern=".{3,3}" minlength="3" maxlength="3" class="form-control" disabled value="{{ old("des") }}" />
                            <input type="text" name="nop_blok"    pattern=".{3,3}" minlength="3" maxlength="3" class="form-control" disabled value="{{ old("blok") }}" />
                            <input type="text" name="nop_no_urut" pattern=".{4,4}" minlength="4" maxlength="4" class="form-control" disabled value="{{ old("no_urut") }}" />
                            <input type="text" name="nop_kode"    pattern=".{1,1}" minlength="1" maxlength="1" class="form-control" disabled value="{{ old("kode") }}" />
                        </div>
                    </div>
                    @endif

                    <div class="form-group">
                        <label>NOP ASAL</label>
                        <div class="input-group">                            
                            <input type="text" disabled name="pr"               required class="form-control" value="{{ substr($spop->nop_asal, 0,2) }}">
                            <input type="text" disabled name="dt"               required class="form-control" value="{{ substr($spop->nop_asal, 2,2) }}">
                            <input type="text" name="nop_asal_kec"              pattern=".{3,3}" minlength="3" maxlength="3" class="form-control" value="{{ substr($spop->nop_asal, 4,3) }}">
                            <input type="text" name="nop_asal_des"              pattern=".{3,3}" minlength="3" maxlength="3" class="form-control" value="{{ substr($spop->nop_asal, 7,3) }}">
                            <input type="text" name="nop_asal_blok"             pattern=".{3,3}" minlength="3" maxlength="3" class="form-control" value="{{ substr($spop->nop_asal, 10,3) }}">
                            <input type="text" name="nop_asal_no_urut"          pattern=".{4,4}" minlength="4" maxlength="4" class="form-control" value="{{ substr($spop->nop_asal, 13,4) }}">
                            <input type="text" name="nop_asal_kode"             pattern=".{1,1}" minlength="1" maxlength="1" class="form-control" value="{{ substr($spop->nop_asal, 17,18) }}">
                        </div>
                    </div>
  
                      <div class="alert alert-info">
                          <p class="text-lg-center">Data Letak Objek Pajak</p> 
                      </div>

                    <div class="form-group">
                        <label>Nama Jalan</label>
                        <textarea name="dlop_nama_jalan" class="form-control @error("dlop_nama_jalan") is-invalid @enderror" id="" cols="30" rows="10">{{ old("dlop_nama_jalan") ? old("dlop_nama_jalan") : $spop->dataLetakObjek->nama_jalan }}</textarea>
                        @error("dlop_nama_jalan")
                            <div class="invalid-feedback"> 
                                Nama Jalan Harus Di isi
                            </div>
                        @enderror
                      </div>
  
                      <div class="form-group">
                          <label>Blok / KAV Nomor</label>
                          <textarea name="dlop_blok" class="form-control @error("dlop_blok") is-invalid @enderror" id="" cols="30" rows="10">{{ old("dlop_blok") ? old("dlop_blok") : $spop->dataLetakObjek->blok_kav }}</textarea>
                            @error("dlop_blok")
                                <div class="invalid-feedback"> 
                                    Nama Blok harus di isi
                                </div>
                            @enderror
                      </div>
  

                    <div class="ui-widget form-group">
                        <label for="desa">Desa: </label>
                        <input id="desa" name="dlop_desa" class="form-control" value="{{ old("dlop_desa") ? old("dlop_desa"): $spop->dataLetakObjek->desa->nama }}">
                    </div>
  
                      <div class="row">
                          <div class="col">
                              <div class="form-group">
                                  <label>RW</label>
                                  <input type="text" minlength="2" maxlength="2" class="form-control @error("dlop_rw") is-invalid @enderror" name="dlop_rw" id="" value="{{ old('dlop_rw') ? old('dlop_rw') : $spop->dataLetakObjek->rw}}">
                                    @error("dlop_rw")
                                        <div class="invalid-feedback"> 
                                            RW harus di isi, harus 2 angka
                                        </div>
                                    @enderror
                              </div>
                          </div>
                          <div class="col">
                              <div class="form-group">
                                  <label>RT</label>
                                  <input type="text" minlength="3" maxlength="3" class="form-control @error("dlop_rt") is-invalid @enderror" name="dlop_rt" id="" value="{{ old('dlop_rt') ? old('dlop_rt') : $spop->dataLetakObjek->rt}}">
                                    @error("dlop_rt")
                                        <div class="invalid-feedback"> 
                                            RW harus di isi, harus 3 angka
                                        </div>
                                    @enderror
                              </div>
                          </div>
                      </div>
                      
                      <div class="alert alert-info">
                         <p class="text-center">Data Subjek Pajak</p> 
                      </div>
                        
                      <div class="form-group is-invalid">
                          <label class="form-label">
                              Status
                                @error("status")
                                    <span class="text-danger">Belum di pilih</span>
                                @enderror
                            </label>
                          <div class="selectgroup selectgroup-pills">
                              @foreach ($statuses as $status)
                                <label class="selectgroup-item">
                                    <input type="radio" name="status" value="{{ $status->id }}" 
                                        class="selectgroup-input" 
                                        @if (old("status") == $status->id)
                                            checked
                                        @elseif ($spop->dataSubjekPajak->status->id == $status->id)
                                            checked
                                        @endif
                                        >
                                    <span class="selectgroup-button">{{ $status->nama }}</span>
                                </label>      
                              @endforeach
                          </div>
                      </div>
  
                      <div class="form-group">
                            <label class="form-label">
                              Pekerjaan
                                @error("pekerjaan")
                                    <span class="text-danger">Belum di pilih</span>
                                @enderror
                            </label>
                          <div class="selectgroup selectgroup-pills">
                              @foreach ($pekerjaans as $pekerjaan)
                                <label class="selectgroup-item">
                                    <input type="radio" name="pekerjaan" value="{{ $pekerjaan->id}}" class="selectgroup-input" 
                                    @if (old("pekerjaan") == $pekerjaan->id)
                                        checked
                                    @elseif($spop->dataSubjekPajak->status->id == $pekerjaan->id)
                                        checked
                                    @endif
                                    >
                                    <span class="selectgroup-button">{{ $pekerjaan->nama}}</span>
                                </label>
                              @endforeach
                          </div>
                      </div>
  
                      <div class="form-group">
                            <label>Nama Subjek Pajak</label>
                            <input type="text" class="form-control @error('dsp_nama_subjek_pajak') is-invalid @enderror" name="dsp_nama_subjek_pajak" value="{{ old("dsp_nama_subjek_pajak") ? old("dsp_nama_subjek_pajak") : $spop->dataSubjekPajak->nama_subjek_pajak }}">
                            @error("dsp_nama_subjek_pajak")
                                <div class="invalid-feedback"> 
                                    Nama subjek pajak harus di isi
                                </div>
                            @enderror
                      </div>
  
                      <div class="form-group">
                            <label>Nama Jalan</label>
                            <input type="text" class="form-control @error('dsp_nama_jalan') is-invalid @enderror" name="dsp_nama_jalan" value="{{ old('dsp_nama_jalan') ? old('dsp_nama_jalan') : $spop->dataSubjekPajak->nama_jalan }}">
                            @error("dsp_nama_jalan")
                                <div class="invalid-feedback"> 
                                    Nama subjek pajak harus di isi
                                </div>
                            @enderror
                      </div>

                    <div class="ui-widget form-group">
                        <label for="desa2">Desa: </label>
                        <input id="desa2" name="dsp_desa" class="form-control @error('dsp_desa') is-invalid @enderror" value="{{ old('dsp_desa') ? old('dsp_desa') : $spop->dataSubjekPajak->desa }}" />
                        @error("dsp_desa")
                            <div class="invalid-feedback"> 
                                Desa harus di isi
                            </div>
                        @enderror
                    </div>

                    <div class="ui-widget form-group">
                        <label for="kab">Kabupaten: </label>
                        <input id="kab" name="dsp_kabupaten" class="form-control @error('dsp_kabupaten') is-invalid @enderror" value="{{ old('dsp_kabupaten') ? old('dsp_kabupaten') : $spop->dataSubjekPajak->kabupaten }}" />
                        @error("dsp_kabupaten")
                            <div class="invalid-feedback"> 
                                Kabupaten harus di isi
                            </div>
                        @enderror
                    </div>

                      <div class="row">
                          <div class="col">
                              <div class="form-group">
                                    <label>RW</label>
                                    <input type="number" class="form-control @error('dsp_rw') is-invalid @enderror" name="dsp_rw" value="{{ old("dsp_rw") ? old("dsp_rw") : $spop->dataSubjekPajak->rw }}">
                                    @error("dsp_rw")
                                        <div class="invalid-feedback"> 
                                            RW harus di isi, harus 2 angka
                                        </div>
                                    @enderror
                              </div>
                          </div>
                          <div class="col">
                              <div class="form-group">
                                    <label>RT</label>
                                    <input type="number" class="form-control @error('dsp_rt') is-invalid @enderror" name="dsp_rt" value="{{ old("dsp_rt") ? old("dsp_rt") : $spop->dataSubjekPajak->rt }}">
                                    @error("dsp_rt")
                                        <div class="invalid-feedback"> 
                                            RT harus di isi, harus 3 angka
                                        </div>
                                    @enderror
                              </div>
                          </div>
                      </div>
                      
                      <div class="form-group">
                            <label>Nomor KTP</label>
                            <input type="number" class="form-control @error('dsp_no_ktp') is-invalid @enderror" name="dsp_no_ktp" value="{{ old("dsp_no_ktp") ? old("dsp_no_ktp") : $spop->dataSubjekPajak->nomor_ktp }}">
                            @error("dsp_no_ktp")
                                <div class="invalid-feedback"> 
                                    No KTP harus di isi, harus 16 karakter , dan berupa nomor
                                </div>
                            @enderror
                      </div>
  
                    <div class="form-group">
                        <label>Nomor HP</label>
                        <input type="text" class="form-control @error('dsp_no_hp') is-invalid @enderror" name="dsp_no_hp" value="{{ old("dsp_no_hp") ? old("dsp_no_hp") : $spop->dataSubjekPajak->nomor_hp }}">
                    </div>
                    
                    @foreach ($spop->gambars as $item)
                            <div class="form-group">
                                <label for="">{{ $item->kategori->nama }}</label>
                                <img src="{{asset("storage/data_spop/$item->nama")}}" alt="" style="width:100%" />
                            </div>
                    @endforeach


                    @foreach ($kategori as $item)
                        <div class="form-group">
                            <label>{{ $item->nama }}</label>
                            <input type="file" id="gallery-photo-add-{{ $item->id }}" multiple class="form-control @error("$item->id") is-invalid @enderror" name="gambar[{{ $item->id }}][]" value="{{ old("$item->id") }}" accept="image/*">
                            <p  class="btn btn-danger mt-1" id="reset-file-{{ $item->id }}">Reset</p>
                        </div>
                        <div class="gallery-{{ $item->id }}"></div>
                    <br>
                        <script>
                        $(function() {
                            // Multiple images preview in browser
                            var imagesPreview = function(input, placeToInsertImagePreview) {
                                
                                if (input.files) {
                                    var filesAmount = input.files.length;
                                    
                                    for (i = 0; i < filesAmount; i++) {
                                        var reader = new FileReader();
                        
                                        reader.onload = function(event) {
                                            $($.parseHTML('<img>')).attr('src', event.target.result).attr('width', "100%").appendTo(placeToInsertImagePreview);
                                        }
                        
                                        reader.readAsDataURL(input.files[i]);
                                    }
                                }
                        
                            };
                        
                            $("#gallery-photo-add-{{ $item->id }}").on('change', function() {
                                imagesPreview(this, 'div.gallery-{{ $item->id }}');
                            });

                            $("#reset-file-{{ $item->id }}").on("click", function(){
                                $("#gallery-photo-add-{{ $item->id }}").val("")
                                $(".gallery-{{ $item->id }} img").remove()
                            });
                        });
                        </script>
                    @endforeach
                    
                      <div class="alert alert-info">
                          <p class="text-center">Data Tanah</p> 
                      </div>
  
                      <div class="form-group">
                            <label>Luas Tanah</label>
                            <input type="number" class="form-control @error('dsp_luas_tanah') is-invalid @enderror" name="dsp_luas_tanah" value="{{ old("dsp_luas_tanah") ? old("dsp_luas_tanah") : $spop->dataTanah->luas_tanah }}">
                            @error("dsp_luas_tanah")
                                <div class="invalid-feedback"> 
                                    Luas Tanah harus di isi, dan erupa nomor
                                </div>
                            @enderror
                      </div>
  
                      <div class="form-group">
                            <label class="form-label">
                              Jenis Tanah
                            @error("jenis_tanah")
                                    <span class="text-danger">Jenis tanah yang di pilih harus {{$jenisTanah[0]["nama"]}}</span>
                            @enderror
                            </label>
                          <div class="selectgroup selectgroup-pills">
                            @foreach ($jenisTanah as $item)
                                @if ($item->id == 1)
                                    <label class="selectgroup-item">
                                        <input id="tanah" type="radio" name="jenis_tanah" value="{{ $item->id }}" class="selectgroup-input" 
                                        @if (old("jenis_tanah") == $item->id)
                                            checked
                                        @elseif($spop->dataTanah->jenis_tanah_id == $item->id)
                                            checked
                                        @endif
                                        >
                                        <span  class="selectgroup-button" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">{{ $item->nama }}</span>
                                  </label>
                                @else
                                    <label class="selectgroup-item">
                                        <input type="radio" name="jenis_tanah" value="{{ $item->id }}" class="selectgroup-input tanah" 
                                        @if (old("jenis_tanah") == $item->id)
                                            checked
                                        @elseif($spop->dataTanah->jenis_tanah_id == $item->id)
                                            checked
                                        @endif
                                        >
                                        <span class="selectgroup-button" >{{ $item->nama }}</span>
                                    </label>
                                @endif      
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
                <div class="row">
                    <div class="col-6 col-md-6 col-lg-6">
                        <input type="submit" name="action" class="btn btn-dark btn-block" value="save" >
                    </div>
                </div>

                <div class="row my-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <a href="{{url("/")}}" class="btn btn-danger btn-block"> Batal</a>
                    </div>
                </div>
                
            </div>
        </form>
          </div>
        </section>
      </div>
@endsection

@section('script')

    <!-- Page Specific JS File -->
    <script src="{{ asset("assets/js/page/forms-advanced-forms.js")}}"></script>
    <script src="{{ asset("assets/node/select2.full.min.js")}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    {{-- custom --}}

    <script>

        var value_bangunan = $("#value_bangunan").val() // mengambil nilai bangunan

        $('#tanah').on('click', function () {
            if($("#collapseExample").hasClass("show") == false){
                $("#collapseExample").addClass("show").show("slow")
                if(value_bangunan == 1){
                    $(`#bangunan_${value_bangunan}`).removeClass("d-none")
                    $(`#bangunan_${value_bangunan}`).addClass("d-block")
                }
                $(`.parent_bangunan`).removeClass("d-none")
                $(`.parent_bangunan`).addClass("d-block")
            }
        })

        $('.tanah').on('click', function () {
            $("#collapseExample").removeClass("show").hide("slow")
            $(`.parent_bangunan`).removeClass("d-block")
            $(`.parent_bangunan`).addClass("d-none")
        })

        // untuk mengurangi elemen js
        $("#minus").on("click", function(e) {
        if(value_bangunan > 1){
            $(`#bangunan_${value_bangunan}`).remove()
            value_bangunan--;
            $("#value_bangunan").val(value_bangunan)
            
        }else{
            alert("nilai minimal 1")
        }
        });

        // untuk menambah element js
        $("#plus").on("click", function() {

            value_bangunan++;
            $("#value_bangunan").val(value_bangunan)
            /*
            $("#parent").append(`
            <div class="row parent_bangunan" id="bangunan_${value_bangunan}">
                <div class="col-12 col-md-12 col-lg-12">
                  <div class="card card-danger">
                    <div class="card-header">
                      <h4>Bangunan Ke - ${value_bangunan}</h4>
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
                                    <input type="radio" name="penggunaan" value="{{ $item->id }}" class="selectgroup-input">
                                    <span class="selectgroup-button">{{ $item->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
  
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Luas Bangunan</label>
                                <input type="text" class="form-control" name="luas_bangunan" id="">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Jumlah Lantai</label>
                                <input type="text" class="form-control" name="jumlah_lantai" id="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Tahun Dibangun</label>
                                <input type="text" class="form-control" name="tahun_dibangun" id="">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Tahun Direnovasi</label>
                                <input type="text" class="form-control" name="tahun_renovasi" id="" >
                            </div>
                        </div>
                    </div>
  
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Daya Listrik Terpasang (WATT)</label>
                                <input type="text" class="form-control" name="daya" id="" >
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
                                    <input type="radio" name="dinding" value="{{$item->id}}" class="selectgroup-input">
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
                                    <input type="radio" name="dinding" value="{{$item->id}}" class="selectgroup-input">
                                    <span class="selectgroup-button">{{$item->nama}}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    </div>
                  </div>
                </div>
                </div>
            `)
            */
        });
    </script>

    <script type="text/javascript">
        $( function() {
            var DesaTags = {!! strtoupper($desas) !!}
            
            $( "#desa" ).autocomplete({
                source: DesaTags
            });
        } );
    </script>
@endsection