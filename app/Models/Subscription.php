<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'inscricoes';
    protected $primaryKey = 'id_inscricao';
    public $timestamps = false;

    protected $fillable = [
        'id_inscricao',
        'id_usuario',
        'id_evento',
        'data_inscricao',
        'data_cancelamento',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscription) {
            $subscription->status = true;
            $subscription->data_inscricao = now();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function event()
    {
        return $this->belongsTo(User::class, 'id_evento', 'id_evento');
    }
}
