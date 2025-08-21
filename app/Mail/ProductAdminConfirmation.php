<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use App\Models\User;

class ProductAdminConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $merchant;

    public function __construct(Product $product, User $merchant)
    {
        $this->product = $product;
        $this->merchant = $merchant;
    }

    public function build()
    {
        return $this->from('noreply@bissmoi.com', 'Bissmoi')
            ->subject('Confirmation/Rejet produit - BISSMOI')
            ->view('emails.product_admin_confirmation');
    }
}
