<?php

namespace App\Http\Controllers\Subscription;

use App\Exceptions\DuplicateSubscriptionException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;

class SubscriptionController extends Controller
{
    private $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Display a listing of the resource.
     * Fazer assim por enquanto, conectar com o jwt depois
     */
    public function index(int $userId)
    {
        $subscriptions = $this->subscriptionService->listAllByUser($userId);

        if ($subscriptions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhuma inscrição foi encontrada para o usuário informado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'inscricoes' => $subscriptions
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                "id_usuario" => "required|integer|exists:usuarios,id_usuario",
                "id_evento" => "required|integer|exists:eventos,id_evento",
            ]);

            $subscription = $this->subscriptionService->create($validated);

            if ($subscription->id_usuario == $validated['id_usuario'] && $subscription->id_evento) {
                return response()->json([
                    'success' => false,
                    'message' => 'O usuário já está inscrito neste evento.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Inscrição criada com sucesso!',
                'data' => $subscription,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (DuplicateSubscriptionException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => "Erro interno ao criar inscrição. {$e->getMessage()}",
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $canceled = $this->subscriptionService->cancelSubscription($id);

        if ($canceled) {
            return response()->json([
                'success' => true,
                'message' => 'Inscrição cancelada com sucesso.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Não foi possível cancelar a inscrição. Ela pode não existir ou já estar cancelada.'
        ], 400);
    }
}
