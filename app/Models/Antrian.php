<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{

    use HasFactory;

    protected $table = "antrian";
    protected $fillable = [
        'loket_id', 'nomor_antrian', 'status', 'id',
    ];

    public function loket()
    {
        return $this->belongsTo(Loket::class);
    }
}
