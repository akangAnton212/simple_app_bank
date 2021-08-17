<?php

namespace App\Http\Services;

use App\Support\Collection;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

use App\Models\MAccountCustomer;
use App\Models\MCustomer;

class CustomerService {
    public function listCustomers($request){
        try {
            $data = MCustomer::where('enabled', true)
                    // ->whereHas('customer_accounts')
                    ->select(
                        'id',
                        'NIK',
                        'name',
                        'DOB',
                        'POB',
                        'email',
                        'address',
                        'province',
                        'city',
                        'postal_code',
                        'register_date',
                        'uid_register_by',
                        'uid')
                    ->with([
                        'customer_accounts',
                        'user_register'
                    ])
                    ->orderBy('created_at', 'DESC');

            $cari = $request->query('search');
            if(!empty($cari)){
                $data = $data->whereCustomer($cari);
            }

            // $data = $data->get();

            $data = $data->get()
                ->map(function($key){
                return [
                    'id'                    => $key->id,
                    'NIK'                   => $key->NIK,
                    'name'                  => $key->name,
                    'DOB'                   => $key->DOB,
                    'POB'                   => $key->POB,
                    'email'                 => $key->email,
                    'address'               => $key->address,
                    'province'              => $key->province,
                    'city'                  => $key->city,
                    'postal_code'           => $key->postal_code,
                    'register_date'         => $key->register_date,
                    'uid_register_by'       => $key->uid_register_by,
                    'register_by'           => $key->user_register->name,
                    'customer_accounts'     => count($key->customer_accounts) > 0 ? $key->customer_accounts
                                                ->map(function($keys){
                                                    return [
                                                        "id_customer_account"   => $keys->id,
                                                        "account_number"        => $keys->account_number,
                                                        "card_number"           => $keys->card_number,
                                                        "balance"               => $keys->balance,
                                                        "uid_customer_account"  => $keys->uid,
                                                    ];
                                                }) : [],
                    'uid'                   => $key->uid
                ];
            });

            $data = (new Collection($data))->paginate(2);
            
            return response()->json([
                'status'        => count($data) > 0 ? true : false,
                'message'       => count($data) > 0 ? 'Data Found' : 'Data Not Found',
                'data'          => count($data) > 0 ? $data : []
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'message'       => $e->getMessage(),
                'data'          => null
            ], $e->getCode());
        }
    }

    public function addCustomer($request){
        $validator = Validator::make($request->all(), [
            'NIK'           => 'required|max:16|min:16',
            'name'          => 'required',
            'DOB'           => 'required',
            'POB'           => 'required',
            'email'         => 'required',
            'address'       => 'required',
            'province'      => 'required',
            'city'          => 'required',
            'postal_code'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'        => false,
                'message'       => $validator->errors(),
                'data'          => []
            ], 422);
        }

        try {
            $data = "";
            if($request->input("uid")){
                $update = MCustomer::where('uid', $request->input("uid"))
                ->update([
                    'NIK'           => $request->input('NIK'),
                    'name'          => $request->input('name'),
                    'DOB'           => $request->input('DOB'),
                    'POB'           => $request->input('POB'),
                    'email'         => $request->input('email'),
                    'address'       => $request->input('address'),
                    'province'      => $request->input('province'),
                    'city'          => $request->input('city'),
                    'postal_code'   => $request->input('postal_code'),
                    'enabled'       => $request->input('enabled'),
                ]);

                $data = MCustomer::where([
                            'enabled'   => true,
                            'uid'       => $request->input("uid")
                        ])->with('customer_accounts')->first();
            }else{
                $checking_nik = MCustomer::where([
                    'enabled'           => true,
                    'NIK'               => $request->input('NIK')
                ])->exists();

                if($checking_nik == true){
                    return response()->json([
                        'status'        => false,
                        'message'       => 'NIK '. $request->input('NIK'). ' Already Exists!',
                        'data'          => []
                    ], 409);
                }else{
                    $create = MCustomer::create([
                        'NIK'           => $request->input('NIK'),
                        'name'          => $request->input('name'),
                        'DOB'           => $request->input('DOB'),
                        'POB'           => $request->input('POB'),
                        'email'         => $request->input('email'),
                        'address'       => $request->input('address'),
                        'province'      => $request->input('province'),
                        'city'          => $request->input('city'),
                        'postal_code'   => $request->input('postal_code'),
                        'register_date' => Carbon::now(),
                        'uid_register_by'=> $request->user()->uid,
                    ]);
                    $data = $create;
                }
            }
           
            return response()->json([
                'status'        => true,
                'message'       => 'Save Data Success',
                'data'          => $data
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'message'       => $e->getMessage(),
                'data'          => null
            ], $e->getCode());
        }
    }

    public function addCustomerAccount($request){
        $validator = Validator::make($request->all(), [
            'uid_customer'  => 'required',
            'card_number'   => 'required|numeric',
            'account_number'=> 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'        => false,
                'message'       => $validator->errors(),
                'data'          => []
            ], 422);
        }

        try {
            $data = "";
            if($request->input("uid")){
                if($request->input('password')){
                    $update = MAccountCustomer::where('uid', $request->input("uid"))
                        ->update([
                            'uid_customer'          => $request->input('uid_customer'),
                            'account_number'        => $request->input('account_number'),
                            'password'              => Hash::make($request->input('password')),
                            'card_number'           => $request->input('card_number'),
                            'balance'               => $request->input('balance'),
                            'enabled'               => $request->input('enabled'),
                        ]);
                }else{
                    $update = MAccountCustomer::where('uid', $request->input("uid"))
                        ->update([
                            'uid_customer'          => $request->input('uid_customer'),
                            'account_number'        => $request->input('account_number'),
                            // 'password'              => Hash::make($request->input('password')),
                            'card_number'           => $request->input('card_number'),
                            'balance'               => $request->input('balance'),
                            'enabled'               => $request->input('enabled'),
                        ]);
                }
                
                $data = MAccountCustomer::where([
                            'enabled'   => true,
                            'uid'       => $request->input("uid")
                        ])->first();
            }else{

                $checking_account_number = MAccountCustomer::where([
                    'enabled'           => true,
                    'account_number'    => $request->input('account_number')
                ])->exists();

                if($checking_account_number == true){
                    return response()->json([
                        'status'        => false,
                        'message'       => 'Account Number '. $request->input('account_number'). ' Already Exists!',
                        'data'          => []
                    ], 409);
                }
                
                $create = MAccountCustomer::create([
                    'uid_customer'          => $request->input('uid_customer'),
                    'account_number'        => $request->input('account_number'),
                    'password'              => Hash::make($request->input('password')),
                    'card_number'           => $request->input('card_number'),
                    'balance'               => $request->input('balance'),
                ]);

                $data = $create;
            }
            
            return response()->json([
                'status'        => true,
                'message'       => 'Save Data Success',
                'data'          => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'message'       => $e->getMessage(),
                'data'          => null
            ], $e->getCode());
        }
    }
}