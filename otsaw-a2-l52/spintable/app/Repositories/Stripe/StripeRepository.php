<?php

namespace App\Repositories\Stripe;


interface StripeRepository
{
	public function createCustomer($stripe_card_token, $email);
	public function createCharge($stripe_customer_id, $total_amount, $connected_stripe_account_id);
	public function getSavedCards($stripe_customer_id);
	public function addCard($stripe_customer_id, $stripe_card_token);
	public function deleteCard($stripe_customer_id, $card_id);
	public function updateCard($stripe_customer_id, $card_id);
}