<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Courier;
use App\Models\Province;
use Illuminate\Http\Request;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $province   = $this->getProvince();
        $courier    = $this->getCourier();
        return view('home', compact('province', 'courier'));
    }

    public function getProvince()
    {
        // key = code, value = title
        return Province::pluck('title', 'code');
    }

    // get all city by province
    public function getCities($id)
    {
        return City::where('province_code', $id)->pluck('title', 'code');
    }

    // get city by code
    public function getCity($code)
    {
        return City::where('code', $code)->first();
    }

    public function searchCities(Request $request)
    {
        // ambil input search dari ajax select2
        $search = $request->search;

        if (empty($search)) {
            $cities = City::orderBy('title', 'asc')->select('id', 'title')->limit(5)->get();
        } else {
            $cities = City::orderBy('title', 'asc')->where('title', 'like', '%' . $search . '%')->select('id', 'title')->limit(5)->get();
        }

        $response = [];

        foreach ($cities as $city) {
            $response[] = [
                'id' => $city->id,
                'text' => $city->title
            ];
        }

        return json_encode($response);
    }

    public function getCourier()
    {
        return Courier::all();
    }

    public function store(Request $request)
    {
        // ambil seluruh data kurir terpilih
        $courier = $request->input('courier');

        if ($courier) {
            $data = [
                'origin'        => $this->getCity($request->origin_city),
                'destination'   => $this->getCity($request->city_destination),
                'weight'        => 1300,
                'result'        => []
            ];

            // lakukan perulangan sebanyak data kurir yang dipilih
            foreach ($courier as $row) {
                $ongkir = RajaOngkir::ongkosKirim([
                    'origin'        => $request->origin_city,      // ID kota/kabupaten asal
                    'destination'   => $request->city_destination, // ID kota/kabupaten tujuan
                    'weight'        => $data['weight'],                       // berat barang dalam gram
                    'courier'       => $row       // kode kurir pengiriman: ['jne', 'tiki', 'pos'] untuk starter
                ])->get();

                $data['result'][] = $ongkir;
            }

            return view('costs')->with($data);
        }

        return back();
    }
}
