<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingProfile extends Model
{
    protected $fillable = ['user_id','company_name','billing_email','phone','tax_number','address','city','state','postal_code','country'];
    public function user() { return $this->belongsTo(User::class); }
}
