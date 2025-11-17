<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

class EventsService
{
    public function create(array $data): Event
    {
        return Event::create($data);
    }

    public function update(int $id, array $data): Event
    {
        $event = Event::findOrFail($id);
        $event->update($data);
        return $event->fresh();
    }

    public function listAll(): Collection
    {
        return Event::all();
    }

    public function getById(int $id)
    {
        return Event::findOrFail($id);
    }

    public function delete(int $id): bool
    {
        return Event::destroy($id) > 0;
    }

    public function listAllByUser(int $userId): Collection
    {
        return Event::where('id_usuario', $userId)->get();
    }
}
