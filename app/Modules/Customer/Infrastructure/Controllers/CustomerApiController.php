<?php

declare(strict_types=1);

namespace App\Modules\Customer\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\Customer\Domain\Repositories\CustomerRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search']);
        $customers = $this->customerRepository->all($filters);

        return response()->json([
            'data' => array_map(fn(Customer $c) => $c->toArray(), $customers),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'document_number' => 'nullable|string|unique:customers,document_number',
        ]);

        $customer = Customer::create(
            userId: $request->user()->id,
            name: $request->input('name'),
            email: $request->input('email', ''),
            phone: $request->input('phone', ''),
            address: $request->input('address', ''),
            city: $request->input('city', ''),
            documentNumber: $request->input('document_number', ''),
        );

        $saved = $this->customerRepository->save($customer);

        return response()->json([
            'message' => 'Cliente creado exitosamente',
            'data' => $saved->toArray(),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $customer = $this->customerRepository->findById($id);

        if (!$customer) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        return response()->json(['data' => $customer->toArray()]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'document_number' => 'nullable|string|unique:customers,document_number,' . $id,
        ]);

        $existing = $this->customerRepository->findById($id);
        if (!$existing) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $customer = Customer::fromArray(array_merge($existing->toArray(), $request->all()));
        $updated = $this->customerRepository->update($id, $customer);

        return response()->json([
            'message' => 'Cliente actualizado',
            'data' => $updated->toArray(),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $existing = $this->customerRepository->findById($id);
        if (!$existing) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $this->customerRepository->delete($id);

        return response()->json(['message' => 'Cliente eliminado']);
    }
}
