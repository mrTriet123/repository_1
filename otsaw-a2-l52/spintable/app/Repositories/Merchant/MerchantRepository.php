<?php

namespace App\Repositories\Merchant;


interface MerchantRepository
{
    public function getAll();
    public function getByID($id);
    public function saveStripeAccountID($merchant_id, $connected_stripe_account_id);
}