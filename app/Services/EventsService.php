<?php

use App\Models\Event;

class EventsService
{
    public function create(array $data)
    {
        return Event::create($data);
    }

    public function update(int $id, array $data)
    {
        $event = Event::findOrFail($id);
        $event->update($data);
        return $event;
    }

    public function listAll()
    {
        Event::all();
    }

    public function getById(int $id)
    {
        return Event::findOrFail($id);
    }

    public function delete(int $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
    }
}
