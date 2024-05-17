<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable =['user_id','address','city','province','postal_code','client_phone','client_name','completed_at','total','status'];
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeShippedWithinDays($query, $days)
    {
        return $query->whereDate('created_at', '>=', now()->subDays($days));
    }
    public function scopeStatus($query,$status)
    {
        return $query->where('status',$status);
    }
}
