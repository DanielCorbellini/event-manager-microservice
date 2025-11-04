<?php

namespace App\Http\Controllers\Events;

use Illuminate\Http\Request;
use App\Services\EventsService;
use App\Http\Controllers\Controller;
use Exception;

class EventsController extends Controller
{
    protected $eventsService;

    public function __construct(EventsService $eventsService)
    {
        $this->eventsService = $eventsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = $this->eventsService->listAll();

        if ($events->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhum evento foi encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'eventos' => $events
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                "titulo" => "required|string|max:255",
                "data_inicio" => "required|date",
                "data_fim" => "required|date|after_or_equal:data_inicio",
                "local" => "required|string|max:255",
            ]);

            $event = $this->eventsService->create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Evento criado com sucesso!',
                'data' => $event,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao criar evento.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {

            $event = $this->eventsService->getById($id);

            return response()->json([
                'success' => true,
                'evento' => $event
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Evento não encontrado.'
            ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao criar evento.',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $validated = $request->validate([
                "titulo" => "sometimes|required|string|max:255",
                "data_inicio" => "sometimes|required|date",
                "data_fim" => "sometimes|required|date|after_or_equal:data_inicio",
                "local" => "sometimes|required|string|max:255",
            ]);

            $event = $this->eventsService->update($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Evento atualizado com sucesso!',
                'data' => $event,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Evento não encontrado.'
            ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao atualizar evento.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $eventDeleted = $this->eventsService->delete($id);

        if (!$eventDeleted) {
            return response()->json([
                'success' => false,
                'message' => 'Evento não encontrado.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Evento deletado com sucesso.'
        ], 200);
    }
}
