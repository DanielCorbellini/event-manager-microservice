<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    protected $table = 'checkins';
    public $timestamps = false;
    protected $primaryKey = 'id_checkin';

    protected $fillable = [
        'id_inscricao',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->data_checkin = now();
        });
    }
}
