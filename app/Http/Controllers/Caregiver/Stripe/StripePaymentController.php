<?php

namespace App\Http\Controllers\Caregiver\Stripe;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Account;

class StripePaymentController extends Controller
{
    use ApiResponse;
    public function createConnectedAccount(Request $request){
        try{
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            $account = $stripe->accounts->create([
                'type' => 'express',
                'country' => 'US',
                'email' => 'subhrajit@ekodus.com',
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

                return $this->success('Great! Connected Account Created And Account Link Successfully.', $links->url, null, 201);
            }else{
                return $this->error('Oops! Unable To Link Account.',null,null,400);
            }
            
        }catch(\Stripe\Exception\ApiErrorException $e){ 
           return $this->error('Oops! Something Went Wrong.', null,null, 500); 
        }
    }

    public function returnUrl(Request $request){
        return $this->success('Great! Return Url Accessed', null, null, 200);
    }

    public function refreshUrl(Request $request){
        return $this->success('Great! Refresh Url Accessed', null, null, 200);
    }
}
