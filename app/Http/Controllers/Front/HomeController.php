<?php

namespace App\Http\Controllers\Front;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Models\ArtikelModel;
use App\Models\BookingModel;
use App\Models\CategoriModel;
use App\Models\FavoritModel;
use App\Models\ProductModel;
use App\Models\PromoModel;
use App\Models\ReferensiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // return GlobalHelper::getsastrawi();
        // get data rekomendasi
        $rekom = GlobalHelper::getsastrawi();
        $data = [];
        $data['product'] = [];
        // get data favorit
        $data['favorit'] = FavoritModel::pluck('product_id')->toArray();
        // kondisi bila data rekomendasi lebih dari 0
        if (count($rekom) > 0) {
            // get data product berdasrakan rekomendasi
            $product = ProductModel::select()
                ->whereIn('kategori_id', $rekom)
                ->get();
            foreach ($product as $key => $v) {
                // tambah ke variavel data product
                array_push($data['product'], $v);
            }
            // foreach ($rekom as $key => $value) {
            //     if ($key <= 2) {
            //         // get data product berdasrakan rekomendasi
            //         $product = ProductModel::select()
            //             ->where('kategori_id', $value->id_kategori)
            //             ->limit($value->limit)
            //             ->get();
            //         foreach ($product as $key => $v) {
            //             // tambah ke variavel data product
            //             array_push($data['product'], $v);
            //         }
            //     }
            // }
        } else {
            // ambil data produk dengan limit 3 kondisi random
            $data['product'] = ProductModel::select()
                ->limit(3)
                ->inRandomOrder()
                ->get();
        }
        // ambil data kategori
        $data['category'] = CategoriModel::get();
        // ambil data artikel
        $data['article'] = ArtikelModel::get();
        // ambil data produk dengan limit 3
        // $data['product'] = ProductModel::select()
        //     ->limit(3)
        //     ->get();
        $data['arrival'] = ProductModel::orderBy('created_at', 'DESC')
            ->limit(3)
            ->get();
        // ambil data promo dengan limit 3
        $data['promo'] = PromoModel::limit(3)->get();
        // return $data;
        return view('front.beranda', $data);
    }

    public function katalogstudio(Request $request)
    {
        $data = [];
        $data['harga'] = '';
        $data['jumlah_orang'] = '';
        $data['kategori_id'] = '';
        // ambil data kategori
        $data['kategori'] = CategoriModel::get();
        // get data favorit
        $data['favorit'] = FavoritModel::pluck('product_id')->toArray();

        if ($request) {
            $data['harga'] = $request->harga;
            $data['jumlah_orang'] = $request->jumlah_orang;
            $data['kategori_id'] = $request->kategori;
            GlobalHelper::logkegiatan(null, $request->kategori);
        }
        // ambil data produk
        $data['product'] = ProductModel::where(function ($q) use ($request) {
            // filter harga bila ada
            if ($request->harga) {
                $e = explode('-', $request->harga);
                $q->where('harga', '<', $e[1]);
                $q->where('harga', '>', $e[0]);
            }
            // filter kategori bila ada
            if ($request->kategori) {
                $q->where('kategori_id', $request->kategori);
            }
        })
            ->paginate(6);
        // set view
        return view('front.pages.about', $data);
    }

    public function referensi()
    {
        $data = [];
        // ambil data ketegori dengan produk
        $data['kategori'] = CategoriModel::select()->with('referensi')->get();
        // return $data;
        // set view
        return view('front.pages.categories', $data);
    }
    public function referensidetail($id)
    {
        $data = [];
        // ambil data ketegori dengan produk
        $data['kategori'] = ReferensiModel::where('id', $id)->first();
        // return $data;
        // catat log kegiatan
        GlobalHelper::logkegiatan(null, $data['kategori']->kategori_id);
        // set view
        return view('front.pages.categorie_detail', $data);
    }

    public function promo()
    {
        $data = [];
        // ambil data promo
        $data['promo'] = PromoModel::select()->get();
        // return $data;
        // set view
        return view('front.pages.promo', $data);
    }

    // public function payment()
    // {
    //     return PaymentController::
    // }

    function cariproduk(Request $r)
    {
        return $r->all();
    }

    public function produkdetail($id)
    {
        $x = [];
        // ambil data produk selain id yg sama
        $x['all'] = ProductModel::whereNot('id', $id)->get();
        // ambil data produk berdasarkan id
        $x['data'] = ProductModel::where('id', $id)->first();
        // catat log kegiatan
        GlobalHelper::logkegiatan($x['data']->id, $x['data']->kategori_id);
        // menampilkan data
        return view('front.detail', $x);
    }

    public function categoridetail($id)
    {
        $data = [];
        // ambil data kategori berdasarkan id
        $data['data'] = CategoriModel::where('id', $id)->first();
        // ambil data kategori berdasarkan selain id hanya 4 data
        $data['all'] = CategoriModel::where('id', '!=', $id)->limit(4)->get();
        return view('front.detail', $data);
    }

    public function formbooking(Request $request)
    {
        // ambil produk berdasarkarn id
        $data['data'] = ProductModel::where('id', $request->id)->first();
        // bila kosong ke halaman sebelumnya
        if (!$data['data']) {
            return Redirect::back()->with('info', 'Product Not Found');
        }
        $b = BookingModel::select('jam')
            ->where('product_id', $request->id)
            ->whereDate('tanggal', '>=', Carbon::now())
            ->get();
        $data['jamterpakai'] = [];
        foreach ($b as $key => $v) {
            $e = explode('.', $v->jam);
            array_push($data['jamterpakai'], $e[0]);
        }
        // set view
        return view('front.booking', $data);
    }

    public function checktanggal(Request $request)
    {
        if ($request->tanggal) {
            return BookingModel::whereDate('tanggal', $request->tanggal)->pluck('jam')->toArray();
        }
    }

    public function prosesbooking(Request $request)
    {
        // return $request->all();
        $a = Auth::user();
        if (!$a) {
            return Redirect::back()->with('info', 'Silahkan login terlebih dahulu');
        }
        // validasi data inputan
        $valid = Validator::make($request->all(), [
            'nama' => 'required',
            'no_hp' => 'required',
            'tanggal' => 'required',
            // 'jam' => 'required',
            'jumlah_orang' => 'required',
        ]);

        // bila gagal kembali ke halaman sebelumnya
        if ($valid->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($valid)->with('info', 'Harap Cek Data Anda');
        }
        // $file = $request->file('gambar')->store('booking/' . time());
        // $exp = explode('T', $request->tanggal);
        // proses simpan
        $i = BookingModel::create([
            'nama' => $request->nama,
            'email' => Auth::user()->email,
            'nohp' => $request->no_hp,
            'tanggal' => $request->tanggal,
            'jam' => 'kosong',
            'upload' => 'kosong',
            'status' => 1,
            'jumlah_orang' => $request->jumlah_orang,
            'product_id' => $request->product_id,
            'price_total' => $request->price_total,
            'user_id' => Auth::user()->id,
            'promo_id' => 0,
        ]);

        // bila gagal
        if (!$i) {
            return Redirect::back()->withInput($request->all())->withErrors($valid)->with('info', 'Booking Tidak Tersimpan');
        }
        // set view
        return view('front.orderselesai');
    }

    public function approvebooking($id, Request $request)
    {
        // ubah status booking
        $i = BookingModel::where('id', $id)->update([
            'status' => $request->status,
        ]);

        // bila berhasil
        if ($i) {
            return Redirect::back()->with('info', 'Data Tersimpan');
        }
        // bila gagal
        return Redirect::back()->with('info', 'Data Tidak Tersimpan');
    }

    public function editjam(Request $r)
    {
        // return $r->all();
        // set variable null
        $tgl = null;
        $jam = null;
        // cek data input
        if ($r->tanggaljam) {
            $e = explode('T', $r->tanggaljam);
            $tgl = $e[0];
            $jam = $e[1];
        }
        // ubah status booking
        $i = BookingModel::where('id', $r->id_booking)->update([
            'tanggal' => $tgl,
            'jam' => $jam,
        ]);

        // bila berhasil
        if ($i) {
            return Redirect::back()->with('info', 'Data Tersimpan');
        }
        // bila gagal
        return Redirect::back()->with('info', 'Data Tidak Tersimpan');
    }

    public function setfavorit($product_id)
    {
        // ambil data login
        $auth = Auth::user();
        // cek data login
        if (!$auth) {
            return Redirect::to('login');
        }

        // cek ketersediaan favorit
        $f = FavoritModel::where('user_id', $auth->id)
            ->where('product_id', $product_id)
            ->get();
        if (count($f) > 0) {
            $f = FavoritModel::where('user_id', $auth->id)
                ->where('product_id', $product_id)
                ->delete();
            return Redirect::back()->with('info', 'Data Favorit Sudah Terhapus');
        }

        // tambah data favorit berdasarkan user
        $fm = FavoritModel::create([
            'user_id' => $auth->id,
            'product_id' => $product_id,
        ]);
        if ($fm) {
            return Redirect::back()->with('info', 'Data Favorit Tersimpan');;
        }
        return Redirect::back()->with('info', 'Data Favorit Gagal Tersimpan');
    }
}
