<?php

namespace App\Repositories\Stripe;

use App\Repositories\DbRepository;

class DbStripeRepository extends DbRepository implements StripeRepository
{
    // protected $r_category;
    // protected $default_restaurant_image;

    public function __construct()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createCustomer($stripe_card_token, $email)
    {
        try {
            $stripe_customer = \Stripe\Customer::create(array(
                              "email" => $email,
                              "source" => $stripe_card_token
                            ));
            return $stripe_customer;
        } catch (\Exception $e) {
            return array('error' => $e->getMessage());
        }
    }

    public function createCharge($stripe_customer_id, $total_amount, $connected_stripe_account_id)
    {
        try {
            // Share customer - create token first
            $token = \Stripe\Token::create(array(
                "customer" => $stripe_customer_id,
            ), array("stripe_account" => $connected_stripe_account_id));

            // Create a Charge:
            $charge = \Stripe\Charge::create(array(
              "amount" => (int) ($total_amount * 100), // in cents
              "currency" => "sgd",
              "source" => $token->id,
              "application_fee" => 100, // in cents
            ), array("stripe_account" => $connected_stripe_account_id));

            return $charge;
        } catch(\Stripe_CardError $e) {
            $error1 = $e->getMessage();
            return array('error' => 'Invalid Card.', 'dev_error' => $e->getMessage());
        } catch (\Stripe_InvalidRequestError $e) {
            // Invalid parameters were supplied to Stripe's API
            $error2 = $e->getMessage();
            return array('error' => 'Invalid parameters were supplied to Stripe.', 'dev_error' => $e->getMessage());
        } catch (\Stripe_AuthenticationError $e) {
            // Authentication with Stripe's API failed
            $error3 = $e->getMessage();
            return array('error' => 'Stripe Authentication failed.', 'dev_error' => $e->getMessage());
        } catch (\Stripe_ApiConnectionError $e) {
            // Network communication with Stripe failed
            $error4 = $e->getMessage();
            return array('error' => 'Network communication with Stripe failed', 'dev_error' => $e->getMessage());
        } catch (\Stripe_Error $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $error5 = $e->getMessage();
            return array('error' => 'Payment was unsucessful', 'dev_error' => $e->getMessage());
        } catch (\Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $error6 = $e->getMessage();
            return array('error' => 'Something went wrong.', 'dev_error' => $e->getMessage());
        }
    }

    public function getSavedCards($stripe_customer_id)
    {
        try {
            $cards = \Stripe\Customer::retrieve($stripe_customer_id)->sources->all(array('limit'=>3, 'object' => 'card'));

            $i = 0;
            $card_array = array();
            foreach ($cards->data as $card) {
                $card_array[$i]['card_id'] = $card->id;
                $card_array[$i]['brand'] = $card->brand;
                $card_array[$i]['last4'] = $card->last4;
                $card_array[$i]['exp_month'] = $card->exp_month;
                $card_array[$i]['exp_year'] = $card->exp_year;
                $i++;
            }

            return $card_array;
        } catch (\Exception $e) {
            return array();
        }

    }

    public function addCard($stripe_customer_id, $stripe_card_token)
    {
        try {
            $customer = \Stripe\Customer::retrieve($stripe_customer_id);
            $card = $customer->sources->create(array("source" => $stripe_card_token));
            return $card;
        } catch (\Exception $e) {
            return array('error' => $e->getMessage());
        }
    }

    public function updateCard($stripe_customer_id, $card_id)
    {
        try {
            $customer = \Stripe\Customer::retrieve($stripe_customer_id);
            $card = $customer->sources->retrieve($card_id);
            // $card->name = "Lily Williams"; // https://stripe.com/docs/api#update_card; can only update few things
            $card->save();
            return $card;
        } catch (\Exception $e) {
            return array('error' => $e->getMessage());
        }
    }

    public function deleteCard($stripe_customer_id, $card_id)
    {
        try {
            $customer = \Stripe\Customer::retrieve($stripe_customer_id);
            $card = $customer->sources->retrieve($card_id)->delete();
            return $card;
        } catch (\Exception $e) {
            return array('error' => $e->getMessage());
        }
    }
}