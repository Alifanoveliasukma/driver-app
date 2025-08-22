<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UtamaController extends Controller
{
    public function index()
    {


        //         // driver
        $driver = DB::table('mzl.ad_user as u')
            ->join('mzl.c_bpartner as b', 'u.c_bpartner_id', '=', 'b.c_bpartner_id')
            ->join('mzl.xm_driver as d', 'u.c_bpartner_id', '=', 'd.c_bpartner_id')
            ->where('u.value', session('username'))
            ->select('u.c_bpartner_id', 'd.name as driver_name', 'xm_driver_id')
            ->first();

        $customer = DB::table('mzl.c_bpartner as u')
            ->join('mzl.xx_transorder as t', 'u.c_bpartner_id', '=', 't.c_bpartner_id')->first();
        dd($customer);

        $customer_id = $customer->c_bpartner_id ?? null;
        $customername = $customer->name ?? null;
        // dd($customername);

        //         $xm_driver_id = $driver->xm_driver_id ?? null;
        //         // dd($xm_driver_id);

        //         $order = DB::table('mzl.xx_transorder as t')
        //                 ->where('t.xm_driver_id', '=', $xm_driver_id)

        //                 ->first();
        //         dd($order);

        //         // customer
        //         $customer = DB::table('mzl.c_bpartner as u')
        //             ->join('mzl.xx_transorder as t', 'u.c_bpartner_id', '=', 't.c_bpartner_id')->first();
        //         dd($customer);


        //         // menangkap nama driver
        //         // menambah partner_id di kolom c_bpartner_id pada table transorder
        //         $driver2 = DB::table('mzl.ad_user as u')
        //             ->join('mzl.c_bpartner as b', 'u.c_bpartner_id', '=', 'b.c_bpartner_id')
        //             ->join('mzl.xm_driver as d', 'u.c_bpartner_id', '=', 'd.c_bpartner_id')
        //             ->where('u.value', session('username'))
        //             ->select('u.c_bpartner_id', 'd.name as driver_name')
        //             ->first();

        //          //Ambil daftar order (transorder) milik driver yang login
        //         $orders = DB::table('mzl.xx_transorder as t')
        //             ->join('mzl.ad_user as u', 'u.c_bpartner_id', '=', 't.c_bpartner_id')
        //             ->join('mzl.xm_driver as d', 'd.c_bpartner_id', '=', 't.c_bpartner_id')
        //             ->where('u.value', session('username'))
        //              ->get();
        //         // dd($orders);

        //         // Mengambil Order aktif tanpa select (semua data)
        //         $orderaktif = DB::table('mzl.xx_transorder as t')
        //             ->join('mzl.ad_user as u', 'u.c_bpartner_id', '=', 't.c_bpartner_id')
        //             ->where('u.value', session('username'))
        //             ->where(function($q) {
        //                 $q->whereNull('t.status')
        //                 ->orWhere('t.status', '<>', 'FINISHED');
        //             })
        //         ->first();
        //         // dd($orderaktif);

        //         // Ambil order aktif dengan select (data tertentu)
        //         $order1 = DB::table('mzl.xx_transorder as t')
        //             ->join('mzl.ad_user as u', 'u.c_bpartner_id', '=', 't.c_bpartner_id')
        //             ->join('mzl.m_product as p', 't.m_product_id', '=', 'p.m_product_id')
        //             ->where('u.value', session('username'))
        //             ->where(function($q) {
        //                 $q->whereNull('t.status')
        //                 ->orWhere('t.status', '<>', 'FINISHED');
        //             })
        //             ->select(
        //                 't.xx_transorder_id',
        //                 't.m_product_id', //
        //                 'p.name as product_name',
        //                 't.c_bpartner_id', // bisa untuk ambil nama customer dan driver, untuk ambil customer maka c_bpartner join dengan xx_transorder  untuk ambil driver c_bpartner join dengan x_driver dan xx_transorder
        //                 't.c_order_id',
        //                 't.customer_id', // ambil nama customer dan alamat customer
        //                 't.eta',
        //                 't.etd',
        //                 't.route', // alamat pengambilan
        //                 't.value', // surat jalan
        //                 't.xm_driver_id',
        //                 't.xm_fleet_id',
        //                 't.cubication',
        //                 't.tonnage',
        //                 't.status',
        //                 't.loaddatestart', // loading date
        //                 't.unloaddate', // unloading date
        //                 't.loaddatestart', // Loading date (start)
        //                 't.unloaddatestart', // unloadding date (start)
        //                 't.outloaddate', // loading date (out)
        //                 't.outunloaddate', // unloading date (out)
        //                 't.loadstd', // loading standby
        //             )
        //         ->first();


        //         $c_bpartner_id = DB::table('mzl.ad_user')
        //             ->where('value', session('username'))
        //             ->value('c_bpartner_id');
        //             // dd($c_bpartner_id);

        //         // ambil c_bpartner_id dari user login
        // $c_bpartner_id = DB::table('mzl.ad_user')
        //     ->where('value', session('username')) // asumsinya login simpan 'username'
        //     ->value('c_bpartner_id');

        // // query order berdasarkan c_bpartner_id (driver)
        //         $order = DB::table('mzl.xx_transorder as t')
        //         ->join('mzl.xm_driver as d', 't.xm_driver_id', '=', 'd.xm_driver_id')
        //         ->join('mzl.c_bpartner as c_driver', 'd.c_bpartner_id', '=', 'c_driver.c_bpartner_id')
        //         ->join('mzl.ad_user as u', 'u.c_bpartner_id', '=', 'c_driver.c_bpartner_id') // user driver
        //         ->join('mzl.c_bpartner as c_customer', 't.c_bpartner_id', '=', 'c_customer.c_bpartner_id')
        //         ->where('u.value', session('username')) // filter pakai akun login
        //         ->where(function($q) {
        //             $q->whereNull('t.status')
        //             ->orWhere('t.status', '<>', 'FINISHED');
        //         })
        //         ->select(
        //             't.xx_transorder_id',
        //             'd.xm_driver_id',
        //             'c_driver.name as driver_name',
        //             'c_customer.name as customer_name',
        //             'u.ad_user_id as user_id',
        //             'u.name as user_name'
        //         )
        //         ->first();
        //         dd($order);


        //         $order2 = DB::table('mzl.xx_transorder as t')
        //             ->join('mzl.ad_user as u', 'u.c_bpartner_id', '=', 't.c_bpartner_id')
        //             ->join('mzl.c_bpartner as c_customer', 't.c_bpartner_id', '=', 'c_customer.c_bpartner_id')
        //             ->join('mzl.xm_driver as d', 't.xm_driver_id', '=', 'd.xm_driver_id')
        //             ->join('mzl.c_bpartner as c_driver', 'd.c_bpartner_id', '=', 'c_driver.c_bpartner_id')
        //             ->where('u.value', session('username'))
        //             ->where(function($q) {
        //                 $q->whereNull('t.status')
        //                 ->orWhere('t.status', '<>', 'FINISHED');
        //             })
        //             ->select(
        //                 't.xx_transorder_id',
        //                 'c_customer.name as customer_name',
        //                 'c_driver.name as driver_name'
        //             )
        //         ->first();
        //         // dd($order2);

        return view('menu.utama.konfirmasi-berangkat');
    }
}
