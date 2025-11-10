<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'eventos';
    protected $primaryKey = 'id_evento';
    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'data_inicio',
        'data_fim',
        'local'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'id_evento', 'id_evento');
    }
}
