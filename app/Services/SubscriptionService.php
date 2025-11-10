<?php

namespace App\Services;

use App\Exceptions\DuplicateSubscriptionException;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;

class SubscriptionService
{
    public function create(array $data): Subscription
    {
        $existingSubscription = Subscription::where('id_usuario', $data['id_usuario'])
            ->where('id_evento', $data['id_evento'])
            ->where('status', true)
            ->first();

        if ($existingSubscription) {
            throw new DuplicateSubscriptionException("O usuário já está inscrito neste evento.");
        }

        return Subscription::create($data);
    }

    public function listAllByUser(int $idUser): Collection
    {
        return Subscription::where('id_usuario', $idUser)->get();
    }

    public function getById(int $id)
    {
        return Subscription::findOrFail($id);
    }

    public function cancelSubscription(int $id): bool
    {
        $subscription = Subscription::find($id);

        if (!$subscription) {
            return false;
        }

        if ($subscription->status === false) {
            return false;
        }

        $subscription->status = false;
        $subscription->data_cancelamento = now();

        return $subscription->save();
    }
}
