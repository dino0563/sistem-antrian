<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loket extends Model
{
    use HasFactory;

    protected $table = "lokets";

    protected $fillable = [
        'nama', 'id',
    ];

    public function antrian()
    {
        return $this->hasMany(Antrian::class);
    }
}
