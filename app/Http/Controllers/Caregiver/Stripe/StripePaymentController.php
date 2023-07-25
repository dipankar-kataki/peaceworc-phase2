<?php

namespace App\Http\Controllers\Caregiver\Stripe;

use App\Http\Controllers\Controller;
use App\Models\StripeConnectedAccount;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stripe\Account;

class StripePaymentController extends Controller
{
    use ApiResponse;
    public function createConnectedAccount(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{

                $connected_account = StripeConnectedAccount::where('user_id', Auth::user()->id)->first();

                if($connected_account != null){
                    return $this->error('You Already Have A Payout Account', null, null, 400);
                }else{

                    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
                    $account = $stripe->accounts->create([
                        'type' => 'express',
                        'country' => 'US',
                        'email' => Auth::user()->email,
                        // 'email' => $request->email,
                        'capabilities' => [
                            'card_payments' => ['requested' => true],
                            'transfers' => ['requested' => true],
                        ],
                    ]);
        
                    if($account->id != null){
                        $links = $stripe->accountLinks->create([
                            'account' => $account->id,
                            'refresh_url' => route('stripe.return.url'),
                            'return_url' => route('stripe.refresh.url'),
                            'type' => 'account_onboarding',
                        ]);

                        StripeConnectedAccount::create([
                            'user_id' => Auth::user()->id,
                            'stripe_account_id' => $account->id
                        ]);
        
                        return $this->success('Great! Connected Account Created And Account Link Generated Successfully.', $links->url, null, 201);
                    }else{
                        return $this->error('Oops! Unable To Link Account.',null,null,400);
                    }
                }
            }catch(\Stripe\Exception\ApiErrorException $e){ 
               return $this->error('Oops! Something Went Wrong.', null,null, 500); 
            }
        }
        
    }

    public function returnUrl(Request $request){
        return view('stripe-connected-account.stripe-return-url');
    }

    public function refreshUrl(Request $request){
        return $this->success('Great! Refresh Url Accessed', null, null, 200);
    }

    public function getAccounts(){
        try{
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
           $list_of_all_connected_accounts =  $stripe->accounts->all();

           return $this->success('Great! All Connected Accounts Fetched Successfully', $list_of_all_connected_accounts, null, 200);
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong', null, null, 500);
        }
    }

    public function deleteAccount(Request $request){
        $validator = Validator::make($request->all(), [
            'account_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
                $is_account_deleted = $stripe->accounts->delete(
                    $request->account_id,
                    []
                );
                return $this->success('Great! Account Deleted Successfully', $is_account_deleted, null, 200);
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
        
    }

    public function checkConnectedAccountStatus(Request $request){
        try{
            $connected_account = StripeConnectedAccount::where('user_id', Auth::user()->id)->first();
            if($connected_account == null){
                return $this->error('Oops! No Payout Accounts Found', null, null, 400);
            }else{
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            
                // $account = $stripe->accounts->retrieve($connected_account->stripe_account_id);
                $account = $stripe->accounts->retrieve('acct_1NXMkmPtsTfZkmsk');
    
               return $this->success('Great! All Connected Accounts Fetched Successfully', $account, null, 200);
            }

            
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong'.$e->getMessage(), null, null, 500);
        }
    }
}
