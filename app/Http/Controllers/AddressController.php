<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;
use Validator;
use Auth;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'  => 'required|string|max:191',
            'mobile'    => 'required|integer',
            'province'  => 'required|string|max:191',
            'city'      => 'required|string|max:191',
            'district'  => 'required|string|max:191',
            'detail'    => 'required|string|max:191',
            'postcode'  => 'required|integer',
        ]);

        if ($validator->fails()) {
            $reason = $validator->errors()->all();
            return response()->json(['status' => 'failed', 'reason' => $reason[0]]);
        }

        $user = Auth::user();

        $address = new Address;
        $address->username = $request->username;
        $address->mobile = $request->mobile;
        $address->province = $request->province;
        $address->city = $request->city;
        $address->district = $request->district;
        $address->detail = $request->detail;
        $address->postcode = $request->postcode;
        $address->user_id = $user->id;

        $address->save();

        if ($user->address_id == null) {
            $user->address_id = $address->id;
            $user->save();
        }

        return response()->json(['status' => 'success', 'address'=> $address]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
