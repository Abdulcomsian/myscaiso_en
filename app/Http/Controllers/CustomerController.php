<?php

namespace App\Http\Controllers;

use App\customers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
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
    public function index(Request $request)
    {
        $userid=Auth::user()->id;
        $customers=customers::where('user_id',$userid)->orderBy('id','DESC')->get();
            //   dd($customers);
        return view('dashboard.form_records.customer',compact('customers'));
    }
    //check if customer exist are not
    public function check_customer(Request $request)
    {
        $custid=$request->cusid;
        $check_customer=customers::where('user_id',Auth::user()->id)->where('idNumber',$custid)->first();
        $name='';
        if($check_customer)
        {
            $name=$check_customer->name;
        }
        echo $name;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try{
            $is_admin = $request->input('is_admin');
            if($is_admin=="admin"){
                $customer=customers::where('user_id',$request->input('user_id'))->where('idNumber',$request->input('idNumber'))->get();
                    if(count($customer)>0){
                     return back()->with("Error","Record was not saved. The Customer ID already exists!");
                     } else {
                         $customer= new customers();

                       # $is_admin = $request->input('is_admin');
                        if($is_admin=="admin"){
                                $customer->user_id = $request->input('user_id');
                        }else{
                                $customer->user_id=Auth()->user()->id;
                        }

                        $customer->idNumber=$request->input('idNumber');

                        $customer->name=$request->input('name');
                        $customer->address=$request->input('address');
                        $customer->phoneNumber=$request->input('create_phone_number');
                        if($request->input('create_phone_number_flag')=="preferred" || $request->input('create_phone_number_flag')==NULL){
                            $phoneflag = "us";
                            $phonecode="+1";
                        }else{
                            $phoneflag=$request->input('create_phone_number_flag');
                            $phonecode=$request->input('create_phone_number_country_code');
                        }

                        $customer->phonecode=$phonecode;
                        $customer->phoneflag= $phoneflag;


                        $customer->Email=$request->input('Email');
                        $customer->contactName=$request->input('contactName');

                        $customer->save();
                    }//else customer
             }else{
                  $customer=customers::where('user_id',Auth::user()->id)->where('idNumber',$request->input('idNumber'))->get();
                    if(count($customer)>0){
                     return back()->with("Error","Record was not saved. The Customer ID already exists!");
                     } else {
                         $customer= new customers();

                        $is_admin = $request->input('is_admin');
                        if($is_admin=="admin"){
                                $customer->user_id = $request->input('user_id');
                        }else{
                                $customer->user_id=Auth()->user()->id;
                        }

                        $customer->idNumber=$request->input('idNumber');

                        $customer->name=$request->input('name');
                        $customer->address=$request->input('address');
                        $customer->phoneNumber=$request->input('create_phone_number');
                        if($request->input('create_phone_number_flag')=="preferred" || $request->input('create_phone_number_flag')==NULL){
                            $phoneflag = "us";
                            $phonecode="+1";
                        }else{
                            $phoneflag=$request->input('create_phone_number_flag');
                            $phonecode=$request->input('create_phone_number_country_code');
                        }

                        $customer->phonecode=$phonecode;
                        $customer->phoneflag= $phoneflag;


                        $customer->Email=$request->input('Email');
                        $customer->contactName=$request->input('contactName');

                        $customer->save();
                    }//else customer
                }
            //  Toastr->success('Have fun storming the castle!', 'Miracle Max Says');
             return back()->with("Success","Data Save Successfully");
          }catch(Exception $exc){
              return back()->with("Error","Error");
          }

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
    public function update(Request $request)
    {
       if(Auth::check() && Auth::user()->role_type == "admin"){
            $the_id = intval($request->input('user_id'));
       }else{
           $the_id = Auth()->user()->id;
       }
        $id=$request->id;
        $customer=customers::find($id);
        $customer->user_id=$the_id;

        $customer->idNumber=$request->input('idNumber');
        $customer->name=$request->input('name');
        $customer->address=$request->input('address');
        $phonecode = $request->input('edit_phone_code');

        $customer->phoneNumber=$request->input('edit_phone_number');
        if($request->input('edit_phone_flag')=="preferred" || $request->input('edit_phone_flag') == null){
            $phoneflag = "us";
            $phonecode="+1";
        }else{
            $phoneflag=$request->input('edit_phone_flag');
            $phonecode=$request->input('edit_phone_code');
        }

        $customer->phoneflag= $phoneflag;
        $customer->phonecode= $phonecode;


        $customer->Email=$request->input('Email');
        $customer->contactName=$request->input('contactName');

         $customer->save();
          return back()->with("Success","Data Saved Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        echo $id;
    }

    public function check_customer_number(Request $request)
    {
        $is_admin = $request->input('is_admin');
        if($is_admin=="admin"){
            $customer=customers::where('user_id',$request->input('user_id'))->where('idNumber',$_GET['number'])->get();
        }else{
            $customer=customers::where('user_id',Auth::user()->id)->where('idNumber',$_GET['number'])->get();
        }
        if(count($customer)>0)
        {
            echo"exist";
        }

    }
}
