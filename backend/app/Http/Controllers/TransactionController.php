<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\TransactionService;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function getBalanceAccount(Request $request){
        return $this->transactionService->getBalanceAccount($request);
    }

    public function transferAccount(Request $request){
        return $this->transactionService->transferAccount($request);
    }

    public function getTransferHistoryOut(Request $request, $uid){
        return $this->transactionService->getTransferHistoryOut($request, $uid);
    }

    public function getMutation(Request $request, $uid){
        return $this->transactionService->getMutation($request, $uid);
    }

    public function getCheckingAccount(Request $request, $account_number, $nominal){
        return $this->transactionService->getCheckingAccount($request, $account_number, $nominal);
    }

    public function changePasswordAccount(Request $request){
        return $this->transactionService->changePasswordAccount($request);
    }
    
}
