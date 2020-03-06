@extends('layouts.parent')

@section('title')
    pemutakhiran data
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
            <h1 class="text-info text-uppercase">Pemutakhiran Data</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Home</a></div>
              <div class="breadcrumb-item"><a href="#">Pemutakhiran</a></div>
              <div class="breadcrumb-item">Buat</div>
            </div>
          </div>

        <div class="section-body" >
        <form action="{{ url("/pemutakhiran/create/". $rujukan->uuid) }}" method="post" enctype="multipart/form-data">  
            <div class="container-fluid" id="parent">
              <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    @if(session("msg"))
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
                      <h4>PEMUTAKHIRAN DATA </h4>
                    </div>
                    <div class="card-body">
                      <div class="form-group">
                        <label>NOP</label>
                        <div class="input-group">
                            <input type="text" disabled name="pr"        required class="form-control" value="{{ $my_nop[0] }}">
                            <input type="text" disabled name="dt"        required class="form-control" value="{{ $my_nop[1] }}">
                            <input type="text" disabled name="kec"       required class="form-control" value="{{ $my_nop[2] }}">
                            <input type="text" disabled name="des"       required class="form-control" value="{{ $my_nop[3] }}">
                            <input type="text" disabled name="blok"      required class="form-control" value="{{ $my_nop[4] }}">
                            <input type="text" disabled name="no_urut"   required class="form-control" value="{{ $my_nop[5] }}">
                            <input type="text" disabled name="kode"      required class="form-control" value="{{ $my_nop[6] }}">
                        </div>
                      </div>
  
                      <div class="alert alert-info">
                          <p class="text-lg-center">Data Letak Objek Pajak</p> 
                      </div>
  
                    <div class="form-group">
                        <label>Data Lama</label>
                        <textarea disabled class="form-control" cols="30" rows="10">{{  $rujukan->alamat_op }}</textarea>
                    </div>

                      <div class="form-group">
                            <label>Nama Jalan</label>
                            <textarea name="dlop_nama_jalan" class="form-control @error("dlop_nama_jalan") is-invalid @enderror" id="" cols="30" rows="10">{{  old("dlop_nama_jalan") }}</textarea>
                            @error("dlop_nama_jalan")
                                <div class="invalid-feedback"> 
                                    Nama Jalan Harus Di isi
                                </div>
                            @enderror
                      </div>
  
                      <div class="form-group">
                          <label>Blok / KAV Nomor</label>
                          <textarea name="dlop_blok" class="form-control @error("dlop_blok") is-invalid @enderror" id="" cols="30" rows="10">{{ old("dlop_blok") }}</textarea>
                            @error("dlop_blok")
                                <div class="invalid-feedback"> 
                                    Nama Blok harus di isi
                                </div>
                            @enderror
                      </div>
  

                    <div class="ui-widget form-group">
                        <label for="desa">Desa: </label>
                        <input id="desa" name="dlop_desa" class="form-control" value="{{ $wp_desa }}">
                    </div>
  
                      <div class="row">
                          <div class="col">
                              <div class="form-group">
                                  <label>RW</label>
                                  <input type="text" minlength="2" maxlength="2" class="form-control @error("dlop_rw") is-invalid @enderror" name="dlop_rw" id="" value="{{ old('dlop_rw') ? old('dlop_rw') : $op_rw}}">
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
                                  <input type="text" minlength="3" maxlength="3" class="form-control @error("dlop_rt") is-invalid @enderror" name="dlop_rt" id="" value="{{ old('dlop_rt') ? old('dlop_rt') : $op_rt}}">
                                    @error("dlop_rt")
                                        <div class="invalid-feedback"> 
                                            RW harus di isi , harus 3 angka
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
                                        class="selectgroup-input" {{ old("status") == $status->id ? "checked" : null }}
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
                                    <input type="radio" name="pekerjaan" value="{{ $pekerjaan->id}}" class="selectgroup-input" {{ (old("pekerjaan") == $pekerjaan->id) ? "checked" : null }}>
                                    <span class="selectgroup-button">{{ $pekerjaan->nama}}</span>
                                </label>
                              @endforeach
                          </div>
                      </div>
  
                      <div class="form-group">
                            <label>Nama Subjek Pajak</label>
                            <input type="text" class="form-control @error('dsp_nama_subjek_pajak') is-invalid @enderror" name="dsp_nama_subjek_pajak" value="{{ old("dsp_nama_subjek_pajak") ? old("dsp_nama_subjek_pajak") : $rujukan->nama_subjek_pajak }}">
                            @error("dsp_nama_subjek_pajak")
                                <div class="invalid-feedback"> 
                                    Nama subjek pajak harus di isi
                                </div>
                            @enderror
                      </div>
  
                      <div class="form-group">
                            <label>Nama Jalan</label>
                            <input type="text" class="form-control @error('dsp_nama_jalan') is-invalid @enderror" name="dsp_nama_jalan" value="{{ old('dsp_nama_jalan') }}">
                            @error("dsp_nama_jalan")
                                <div class="invalid-feedback"> 
                                    Nama subjek pajak harus di isi
                                </div>
                            @enderror
                      </div>

                    <div class="ui-widget form-group">
                        <label for="desa2">Desa: </label>
                        <input id="desa2" name="dsp_desa" class="form-control @error('dsp_desa') is-invalid @enderror" value="{{ old('dsp_desa') }}" />
                        @error("dsp_desa")
                            <div class="invalid-feedback"> 
                                Desa harus di isi
                            </div>
                        @enderror
                    </div>

                    <div class="ui-widget form-group">
                        <label for="kab">Kabupaten: </label>
                        <input id="kab" name="dsp_kabupaten" class="form-control @error('dsp_kabupaten') is-invalid @enderror" value="{{ old('dsp_kabupaten') }}" />
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
                                    <input type="text" minlength="2" maxlength="2" class="form-control @error('dsp_rw') is-invalid @enderror" name="dsp_rw" value="{{ old("dsp_rw") ? old("dsp_rw") : $wp_rw }}">
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
                                    <input type="text" minlength="3" maxlength="3" class="form-control @error('dsp_rt') is-invalid @enderror" name="dsp_rt" value="{{ old("dsp_rt") ? old("dsp_rt") : $wp_rt }}">
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
                            <input type="text" minlength="16" maxlength="16" class="form-control @error('dsp_no_ktp') is-invalid @enderror" name="dsp_no_ktp" value="{{ old("dsp_no_ktp") }}">
                            @error("dsp_no_ktp")
                                <div class="invalid-feedback"> 
                                    No KTP harus di isi, harus 16 karakter , dan berupa nomor
                                </div>
                            @enderror
                      </div>
  
                      <div class="form-group">
                        <label>Nomor HP</label>
                        <input type="text" class="form-control @error('dsp_no_hp') is-invalid @enderror" name="dsp_no_hp" value="{{ old("dsp_no_hp") }}">
                    </div>

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
                            <input type="number" class="form-control @error('dsp_luas_tanah') is-invalid @enderror" name="dsp_luas_tanah" value="{{ old("dsp_luas_tanah") ? old("dsp_luas_tanah") : $rujukan->luas_bumi_sppt }}">
                            @error("dsp_luas_tanah")
                                <div class="invalid-feedback"> 
                                    Luas Tanah harus di isi, dan berupa nomor
                                </div>
                            @enderror
                      </div>
  
                      <div class="form-group">
                            <label class="form-label">
                              Jenis Tanah
                            @error("jenis_tanah")
                                    <span class="text-danger">Jenis tanah yang di pilih harus</span>
                                    {{-- {{$jenisTanah[0]["nama"] --}}
                            @enderror
                            </label>
                          <div class="selectgroup selectgroup-pills">
                            @foreach ($jenisTanah as $item)
                                @if ($item->id == 1)
                                    <label class="selectgroup-item">
                                        <input id="tanah" type="radio" name="jenis_tanah" value="{{ $item->id }}" class="selectgroup-input" {{ old("jenis_tanah") == $item->id ? "checked" : null}}>
                                        <span  class="selectgroup-button" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">{{ $item->nama }}</span>
                                  </label>
                                @else
                                    <label class="selectgroup-item">
                                        <input type="radio" name="jenis_tanah" value="{{ $item->id }}" class="selectgroup-input tanah" {{ old("jenis_tanah") == $item->id ? "checked" : null}}>
                                        <span class="selectgroup-button" >{{ $item->nama }}</span>
                                    </label>
                                @endif      
                            @endforeach
                          </div>
                      </div>
  
  
                      <!-- for tanah dan bangunan -->
                      <div class="collapse {{ old("jenis_tanah") == 1 ? "show" : null}}" id="collapseExample">
  
                          <div class="alert alert-info">
                              <p class="text-center">Data Bangunan</p> 
                          </div>
  
                      </div>
                    </div>
                  </div>
                </div>
              </div>
  
            <div class="row parent_bangunan {{ old("jenis_tanah") == 1 ? "d-block" : "d-none"}}" id="bangunan_1">
                <div class="col-12 col-md-12 col-lg-12">
                  <div class="card card-danger">
                    <div class="card-header">
                      <h4>Bangunan Ke - 1</h4>
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
                                    <input type="radio" name="penggunaan" value="{{ $item->id }}" class="selectgroup-input" {{ (old("penggunaan") == $item->id) ? "checked" : null}}>
                                    <span class="selectgroup-button">{{ $item->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
  
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Luas Bangunan</label>
                                <input type="text" class="form-control @error('luas_bangunan') is-invalid @enderror" name="luas_bangunan" value="{{ old("luas_bangunan") }}">
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
                                <input type="text" class="form-control @error("jumlah_lantai") is-invalid @enderror" name="jumlah_lantai" value="{{ old("jumlah_lantai") }}">
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
                                <input type="text" minlength="4" maxlength="4" class="form-control @error('tahun_dibangun') is-invalid @enderror" name="tahun_dibangun" value="{{ old("tahun_dibangun") }}">
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
                                <input type="text" minlength="4" maxlength="4" class="form-control @error('tahun_renovasi') is-invalid @enderror" name="tahun_renovasi" value="{{ old("tahun_renovasi") }}" >
                                @error("tahun_renovasi")
                                    <div class="invalid-feedback"> 
                                        tahun renovasi harus di isi
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
  
                        <div class="col">
                            <div class="form-group">
                                <label>Daya Listrik Terpasang (WATT)</label>
                                <input type="number" min="1" class="form-control @error('daya') is-invalid @enderror" name="daya" value="{{ old("daya") }}" >
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
                                    <input type="radio" name="kondisi" value="{{ $kondisi->id }}" class="selectgroup-input" {{ old("kondisi") == $kondisi->id ? "checked" : null}} >
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
                                    <input type="radio" name="konstruksi" value="{{ $item->id }}" class="selectgroup-input" {{ old("konstruksi") == $item->id ? "checked" : null}}>
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
                                    <input type="radio" name="atap" value="{{ $item->id }}" class="selectgroup-input" {{ old("atap") == $item->id ? "checked" : null}}>
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
                                    <input type="radio" name="dinding" value="{{$item->id}}" class="selectgroup-input" {{ old("dinding") == $item->id ? "checked" : null}}>
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
                                    <input type="radio" name="lantai" value="{{$item->id}}" class="selectgroup-input" {{ old("lantai") == $item->id ? "checked" : null}}>
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
                                        <input type="radio" name="langit" value="{{$item->id}}" class="selectgroup-input" {{ old("langit") == $item->id ? "checked" : null}}>
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
                <div class="row">
                    <div class="col-6 col-md-6 col-lg-6">
                        <input type="submit" name="action" class="btn btn-dark btn-block" value="save" >
                    </div>
                    <div class="col-6 col-md-6 col-lg-6">
                        <input type="submit" name="action" class="btn btn-dark btn-block" value="tambah" >
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

    <script>

        var value_bangunan = $("#value_bangunan").val() // mengambil nilai bangunan

        $('#tanah').on('click', function () {
            if($("#collapseExample").hasClass("show") == false){
                $("#collapseExample").addClass("show").show("slow")

                if(value_bangunan == 1){
                    $(`#bangunan_${value_bangunan}`).removeClass("d-none")
                    $(`#bangunan_${value_bangunan}`).addClass("d-block")
                }

                $(".parent_bangunan").removeClass("d-none")
                $(".parent_bangunan").addClass("d-block")
            }
        })

        $('.tanah').on('click', function () {
            $("#collapseExample").removeClass("show").hide("slow")
            $(".parent_bangunan").removeClass("d-block")
            $(".parent_bangunan").addClass("d-none")
        })

        // untuk mengurangi elemen js
        $("#minus").on("click", function(e) {
            if(value_bangunan > 1){
                $(`#bangunan_${value_bangunan}`).remove()
                value_bangunan--;
                $("#value_bangunan").val(value_bangunan)
                
            }else{
                alert("nilai minimal 1");
            }
        });

        // untuk menambah element js
        $("#plus").on("click", function() {
            value_bangunan++;
            $("#value_bangunan").val(value_bangunan);
        });
    </script>
@endsection