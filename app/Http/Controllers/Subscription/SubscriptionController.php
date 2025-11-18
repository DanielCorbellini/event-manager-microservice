<?php

namespace App\Http\Controllers\Subscription;

use App\Exceptions\DuplicateSubscriptionException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CheckinService;
use App\Services\SubscriptionService;

class SubscriptionController extends Controller
{
    private $subscriptionService;
    private $checkinService;

    public function __construct(
        SubscriptionService $subscriptionService,
        CheckinService $checkinService
    ) {
        $this->subscriptionService = $subscriptionService;
        $this->checkinService = $checkinService;
    }

    /**
     * Display a listing of the resource.
     * Fazer assim por enquanto, conectar com o jwt depois
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $userId = $data['id_usuario'];
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
        $canceledSubscription = $this->subscriptionService->cancelSubscription($id);

        if (empty($canceledSubscription)) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível cancelar a inscrição. Ela pode não existir ou já estar cancelada.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Inscrição cancelada com sucesso.',
            'data' => $canceledSubscription
        ], 200);
    }

    public function checkin(int $subscriptionId)
    {
        try {
            $checkin = $this->checkinService->create($subscriptionId);

            return response()->json([
                'success' => true,
                'message' => 'Check-in realizado com sucesso!',
                'data' => $checkin,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Erro ao realizar check-in. {$e->getMessage()}",
            ], 400);
        }
    }
}
