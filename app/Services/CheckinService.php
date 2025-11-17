<?php

namespace App\Services;

use App\Models\Checkin;

class CheckinService
{
    public function create(int $subscriptionid)
    {
        $alreadyExist = Checkin::where('id_inscricao', $subscriptionid)->exists();

        if ($alreadyExist) {
            throw new \Exception("Check-in já existe para esta inscrição.");
        }

        return Checkin::create([
            'id_inscricao' => $subscriptionid,
        ]);
    }
}
