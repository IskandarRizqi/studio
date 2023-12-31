{{-- @extends('admin.layout.main')

@section('content')

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <form action="{{route('categori.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="page-header">
                <div class="page-title">
                    <a href="{{route('categori.index')}}" class="btn btn-primary btn-sm">Kembali</a>
                    <button class="btn btn-success btn-sm" type="submit">Simpan</button>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                    <div class="widget widget-content-area br-4">
                        <div class="widget-one">

                            <div class="row m-2">
                                <div class="col-lg-6">
                                    <label for="form-control">Nama</label>
                                    <input type="hidden" name="idnama" value="{{$edit->id}}">
                                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="" value="{{$edit->nama}}">
                                    @error('nama')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-lg-6">
                                    <label for="gambar" style="color: black;">Upload Foto</label>
                                    <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror" style="width: 50%; height: 50px;">
                                    @error('gambar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>  
                                    @enderror
                                  
                                </div>

                                
                                <div class="col-lg-6">
                                    <label for="form-control">Harga</label>
                                    <input type="text" name="harga" class="form-control @error('harga') is-invalid @enderror" placeholder="harga" value="{{$edit['harga']}}">
                                    @error('harga')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="form-control">Deskripsi</label>
                                    <input type="text" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="" value="{{$edit['deskripsi']}}">
                                    @error('deskripsi')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>




        <!-- CONTENT AREA -->

    </div>
</div>
<!--  END CONTENT AREA  -->
@endsection --}}