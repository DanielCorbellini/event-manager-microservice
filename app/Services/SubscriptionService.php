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

        $subscription = Subscription::create($data);

        return $subscription->load(['user', 'event', 'checkin']);
    }

    public function listAllByUser(int $idUser): Collection
    {
        return Subscription::with(['user', 'event', 'checkin'])->where('id_usuario', $idUser)->get();
    }

    public function getById(int $id)
    {
        return Subscription::findOrFail($id);
    }

    public function cancelSubscription(int $id): ?Subscription
    {
        $subscription = Subscription::find($id);

        if (!$subscription) {
            return null;
        }

        if ($subscription->status === false) {
            return null;
        }

        $subscription->status = false;
        $subscription->data_cancelamento = now();
        $subscription->save();

        $subscription->load(['user', 'event', 'checkin']);

        return $subscription;
    }

    public function listAllSubscriptionsByEventId(int $eventId): Collection
    {
        return Subscription::with(['user', 'event', 'checkin'])->where('id_evento', $eventId)->where('status', true)->get();
    }
}
