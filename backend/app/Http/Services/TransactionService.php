<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Support\Collection;

use App\Models\MAccountCustomer;
use App\Models\TAccountTransactionTransfer;
use App\Models\TMutation;
use App\Models\MCustomer;


class TransactionService {
    protected $user_auth;

    public function __construct()
    {
        $this->user_auth = Auth::guard('customer')->user();
    }

    public function getBalanceAccount($request){
        try {
            return response()->json([
                'status'        => true,
                'message'       => 'Data Found',
                'data'          => $this->user_auth->balance
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'message'       => $e->getMessage(),
                'data'          => null
            ], $e->getCode());
        }
    }

    public function transferAccount($request){
        try {
            DB::beginTransaction();

            //checking saldo
            if($this->user_auth->balance == 0){
                return response()->json([
                    'status'        => false,
                    'message'       => 'Your Balance Not Enough',
                    'data'          => []
                ], 400);
            }

            //checking yang mau d TF lebih gede dari saldonya ga?
            if(floatval($request->input("nominal")) > $this->user_auth->balance){
                return response()->json([
                    'status'        => false,
                    'message'       => 'Your Balance Not Enough',
                    'data'          => []
                ], 400);
            }

            $destination = MAccountCustomer::where('account_number', $request->input('account_destination'))
                            ->with('customer')
                            ->first();
            
            if($destination){
                $trans_number = $this->trans_num($request);

                $userAuthAccount = MCustomer::where([
                    'enabled'       => true,
                    'uid'           => $this->user_auth->uid_customer,
                ])->first();

                $str_desc_debit = "TRSF DEBIT ".Carbon::now()->format('d/m')." TO ". $destination->customer->name;
                $str_desc_kredit = "TRSF KREDIT ".Carbon::now()->format('d/m')." FROM ". $userAuthAccount->name;

                $create_trans = TAccountTransactionTransfer::create([
                    'trans_code'                => $trans_number,
                    'uid_account_sender'        => $this->user_auth->uid,
                    'trans_type'                => 'DT',
                    'nominal'                   => $request->input('nominal'),
                    'uid_account_destination'   => $destination->uid,
                    'trans_date'                => Carbon::now(),
                    'description'               => $str_desc_debit,
                ]);

                $ambil_mutasi_by_sender = TMutation::where('uid_account', $this->user_auth->uid)
                                            ->orderBy('created_at', 'DESC')
                                            ->first();

                $ambil_mutasi_by_receiver = TMutation::where('uid_account', $destination->uid)
                                            ->orderBy('created_at', 'DESC')
                                            ->first();
                
                $balance = "";
                if($ambil_mutasi_by_sender == ""){
                    $balance = floatval($this->user_auth->balance) - floatval($request->input('nominal'));
                    
                    $create_mutation_sender = TMutation::create([
                        'uid_account'       => $this->user_auth->uid,
                        'mutation_date'     => Carbon::now(),
                        'nominal'           => $request->input('nominal'),
                        'trans_type'        => 'DT',
                        'trans_code'        => $trans_number,
                        'balance'           => $balance,
                        'description'       => $str_desc_debit,
                    ]);

                    
                }else{
                    $balance = floatval($ambil_mutasi_by_sender->balance) - floatval($request->input('nominal'));
                    
                    $create_mutation_sender = TMutation::create([
                        'uid_account'       => $this->user_auth->uid,
                        'mutation_date'     => Carbon::now(),
                        'nominal'           => $request->input('nominal'),
                        'trans_type'        => 'DT',
                        'trans_code'        => $trans_number,
                        'balance'           => $balance,
                        'description'       => $str_desc_debit,
                    ]);
                }

                //update balance di account sender
                $update_balance_account_sender = MAccountCustomer::where('uid', $this->user_auth->uid)->update([
                    'balance'   => $balance
                ]);

                if($ambil_mutasi_by_receiver == ""){
                    $balance = floatval($destination->balance) + floatval($request->input('nominal'));

                    $create_mutation_receiver = TMutation::create([
                        'uid_account'       => $destination->uid,
                        'mutation_date'     => Carbon::now(),
                        'nominal'           => $request->input('nominal'),
                        'trans_type'        => 'CR',
                        'trans_code'        => $trans_number,
                        'balance'           => $balance,
                        'description'       => $str_desc_kredit,
                    ]);
                }else{
                    $balance = floatval($ambil_mutasi_by_receiver->balance) + floatval($request->input('nominal'));
                    
                    $create_mutation_receiver = TMutation::create([
                        'uid_account'       => $destination->uid,
                        'mutation_date'     => Carbon::now(),
                        'nominal'           => $request->input('nominal'),
                        'trans_type'        => 'CR',
                        'trans_code'        => $trans_number,
                        'balance'           => $balance,
                        'description'       => $str_desc_kredit,
                    ]);
                }

                //update balance di account receiver
                $update_balance_account_receiver = MAccountCustomer::where('uid', $destination->uid)->update([
                    'balance'   => $balance
                ]);
            }else{
                DB::rollback();
                return response()->json([
                    'status'        => false,
                    'message'       => "Destination Account Not Found!",
                    'data'          => null
                ], 404);
            }
            
            $result = DB::commit();

            if($result === false){
                DB::rollback();
                return response()->json([
                    'status'        => false,
                    'message'       => "Internal Server Error!",
                    'data'          => null
                ], 500);
            }else{
                return response()->json([
                    'status'        => true,
                    'message'       => 'Transfer Success',
                    'data'          => [],
                ], 200);
            }

        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'message'       => $e->getMessage(),
                'data'          => null
            ], $e->getCode());
        }
    }

    public function getTransferHistoryOut($request, $uid){
        $start_date = $request->query("start_date");
        $end_date = $request->query("end_date");
        
        try {
            $data = "";
            if($start_date && $end_date){
                $data = TAccountTransactionTransfer::where([
                    'enabled'               => true,
                    'uid_account_sender'    => $uid
                ])
                ->whereBetween('trans_date', [$start_date, $end_date])
                ->with([
                    'user_sender.customer',
                    'user_receiver.customer'
                ])
                ->orderBy('trans_date', 'DESC')
                ->get();
            }else{
                $data = TAccountTransactionTransfer::where([
                    'enabled'               => true,
                    'uid_account_sender'    => $uid
                ])
                ->with([
                    'user_sender.customer',
                    'user_receiver.customer'
                ])
                ->orderBy('trans_date', 'DESC')
                ->get();    
            }
           
            $data = $data->map(function ($key) {
                return [
                    "id"                        => $key->id,
                    "trans_code"                => $key->trans_code,
                    "uid_account_sender"        => $key->uid_account_sender,
                    "sender_by"                 => $key->user_sender->customer->name,
                    "sender_account_number"     => $key->user_sender->account_number,
                    "trans_type"                => $key->trans_type,
                    "nominal"                   => $key->nominal,
                    "uid_account_destination"   => $key->uid_account_destination,
                    "receive_by"                => $key->user_receiver->customer->name,
                    "receiver_account_number"   => $key->user_receiver->account_number,
                    "trans_date"                => $key->trans_date,
                    "description"               => $key->description,
                    "uid"                       => $key->uid
                ];
            });

            $data = (new Collection($data))->paginate(10);

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

    public function getMutation($request, $uid){
        $start_date = $request->query("start_date");
        $end_date = $request->query("end_date");
        $data = "";

        try {
            if($start_date && $end_date){
                $data = TMutation::where([
                    'enabled'       => true,
                    'uid_account'   => $uid
                ])
                ->whereBetween('mutation_date', [$start_date, $end_date])
                ->with('account_user.customer')
                ->orderBy('mutation_date', 'DESC')
                ->get();
            }else{
                $data = TMutation::where([
                    'enabled'       => true,
                    'uid_account'   => $uid
                ])
                ->with('account_user.customer')
                ->orderBy('mutation_date', 'DESC')
                ->get();
            }

            $data = $data->map(function ($key) {
                return [
                    "id"                        => $key->id,
                    "trans_code"                => $key->trans_code,
                    "uid_account"               => $key->uid_account_sender,
                    "account_name"              => $key->account_user->customer->name,
                    "mutation_date"             => $key->mutation_date,
                    "trans_type"                => $key->trans_type,
                    "nominal_debet"             => $key->trans_type == "DT" ? $key->nominal : "-",
                    "nominal_credit"            => $key->trans_type == "CR" ? $key->nominal : "-",
                    "balance"                   => $key->balance,
                    "description"               => $key->description,
                    "uid"                       => $key->uid
                ];
            });
           
            $data = (new Collection($data))->paginate(10);

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

    public function getCheckingAccount($request, $account_number, $nominal){
        try {
            $account_login = Auth::guard('customer')->user();

            if($account_login->account_number == $account_number){
                return response()->json([
                    'status'        => false,
                    'message'       => 'Cannot Sending To Self Account!!!',
                    'data'          => []
                ], 200);
            }

            if(floatval($nominal) > floatval($account_login->balance)){
                return response()->json([
                    'status'        => false,
                    'message'       => 'Not Enough Balance!!!',
                    'data'          => []
                ], 200);
            }

            $data = MAccountCustomer::where([
                'enabled'       => true,
                'account_number'=> $account_number,
            ])
            ->whereHas('customer')
            ->with('customer')
            ->first();

            $resp = new \stdClass();
            $resp->account_number = $data->account_number;
            $resp->name = $data->customer->name;

            return response()->json([
                'status'        => true,
                'message'       => 'Data Found',
                'data'          => $resp
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'message'       => $e->getMessage(),
                'data'          => null
            ], $e->getCode());
        }
    }

    public function changePasswordAccount($request){
        $validator = Validator::make($request->all(), [
            'old_password'      => 'required|min:6',
            'new_password'      => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'        => false,
                'message'       => $validator->errors(),
                'data'          => []
            ], 422);
        }

        try {
            $account_login = Auth::guard('customer')->user();

            $account = MAccountCustomer::where([
                            'account_number'    => $account_login->account_number,
                            'enabled'           => true
                        ])->first();

            

           

            if (Hash::check($request->input('old_password'), $account->password)) {
                
                if(Hash::check($request->input('new_password'), $account->password)){
                    return response()->json([
                        'status'        => false,
                        'message'       => 'Old Password As Same With New Password!!',
                        'data'          => ""
                    ], 200);
                }

                $data = MAccountCustomer::where([
                    'enabled'           => true,
                    'account_number'    => $account_login->account_number
                ])->update([
                    'password'          => Hash::make($request->input('new_password'))
                ]);
            }else{
                return response()->json([
                    'status'        => false,
                    'message'       => 'Old Password Wrong!!',
                    'data'          => ""
                ], 200);
            }

            return response()->json([
                'status'        => true,
                'message'       => 'Change Pin Success, Please Re-login!!',
                'data'          => ""
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'message'       => $e->getMessage(),
                'data'          => null
            ], $e->getCode());
        }
    }

    private function trans_num($request){
        $num;

        $now = Carbon::now('Asia/Jakarta');
        $nows =  $now->format('Ymd');
        $date = $now->format('Y-m-d');
        $no =  TAccountTransactionTransfer::selectRaw('RIGHT("trans_code", 3) as "tgl"')
                ->whereDate('created_at', $date)
                ->latest()
                ->max('trans_code');
            
        $thn = substr($now->format('Y'), 2,2);
        $tglBln = $now->format('dm');

        $tglAkhir = TAccountTransactionTransfer::where([
                        'enabled'       => true,
                    ])
                    ->whereNotNull('created_at')
                    ->latest()
                    ->first();

        if($no){
            if($date != $tglAkhir->created_at->format('Y-m-d')){
                $num = $tglBln.$thn.'0001';
            }else{
                $tgl = substr($no,0,6); //1909260001
                $autoNo = substr($no,6,10); 
                $buf = substr($autoNo,0,4)+1;

                if($buf < 10){
                    $num=$tglBln.$thn.'000'.$buf;
                }else if($buf >= 10 && $buf < 100) {
                    $num=$tglBln.$thn.'00'.$buf;
                }else if($buf >= 100 && $buf <= 1000){
                    $num=$tglBln.$thn.'0'.$buf;
                }else{
                    $num = '';
                }
            }
        }else{
            $num = $tglBln.$thn.'0001';
        }

        return $num;
    }
}