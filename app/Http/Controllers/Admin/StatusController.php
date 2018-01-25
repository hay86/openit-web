<?php
/**
 * Created by PhpStorm.
 * User: xukf
 * Date: 17/04/2017
 * Time: 10:47 AM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Status;
use App\Order;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $ids = [];
        for ($i=0; $i<14; $i++) {
            $ids[] = intval(date('Ymd', strtotime('-' . $i . ' days')));
        }
        $rows = Status::whereIn('id', $ids)->orderBy('id', 'desc')->get();

        $statuses = array_keys(order_status('*'));
        foreach ($statuses as $status) {
            $rows[0]['s'.$status] = Order::where('status', $status)->count();
        }

        return view('admin.statuses.index', ['statuses' => $statuses, 'rows' => $rows]);
    }
}