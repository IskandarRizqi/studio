{{-- @extends('admin.layout.main')

@section('content')

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <form action="{{route('admin.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="page-header">
                <div class="page-title">
                    <a href="{{route('admin.index')}}" class="btn btn-primary btn-sm">Kembali</a>
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
                                    <label for="form-control">Email</label>
                                    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="email" value="{{$edit['email']}}">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="form-control">No Hp</label>
                                    <input type="text" name="nohp" class="form-control @error('nohp') is-invalid @enderror" placeholder="" value="{{$edit['nohp']}}">
                                    @error('nohp')
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