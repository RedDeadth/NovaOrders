<?php

declare(strict_types=1);

namespace App\Modules\Customer\Infrastructure\Repositories;

use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\Customer\Domain\Repositories\CustomerRepositoryInterface;
use App\Modules\Customer\Infrastructure\Models\CustomerModel;

class EloquentCustomerRepository implements CustomerRepositoryInterface
{
    public function findById(int $id): ?Customer
    {
        $model = CustomerModel::find($id);
        return $model ? $this->toDomain($model) : null;
    }

    public function all(array $filters = []): array
    {
        $query = CustomerModel::query();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%")
                  ->orWhere('document_number', 'like', "%{$filters['search']}%");
            });
        }

        return $query->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($model) => $this->toDomain($model))
            ->toArray();
    }

    public function save(Customer $customer): Customer
    {
        $model = CustomerModel::create([
            'user_id' => $customer->userId(),
            'name' => $customer->name(),
            'email' => $customer->email(),
            'phone' => $customer->phone(),
            'address' => $customer->address(),
            'city' => $customer->city(),
            'document_number' => $customer->documentNumber(),
        ]);

        return $this->toDomain($model);
    }

    public function update(int $id, Customer $customer): Customer
    {
        $model = CustomerModel::findOrFail($id);
        $model->update([
            'name' => $customer->name(),
            'email' => $customer->email(),
            'phone' => $customer->phone(),
            'address' => $customer->address(),
            'city' => $customer->city(),
            'document_number' => $customer->documentNumber(),
        ]);

        return $this->toDomain($model->fresh());
    }

    public function delete(int $id): void
    {
        CustomerModel::findOrFail($id)->delete();
    }

    public function findByDocument(string $documentNumber): ?Customer
    {
        $model = CustomerModel::where('document_number', $documentNumber)->first();
        return $model ? $this->toDomain($model) : null;
    }

    private function toDomain(CustomerModel $model): Customer
    {
        return Customer::fromArray([
            'id' => $model->id,
            'user_id' => $model->user_id,
            'name' => $model->name,
            'email' => $model->email,
            'phone' => $model->phone,
            'address' => $model->address,
            'city' => $model->city,
            'document_number' => $model->document_number,
            'created_at' => $model->created_at?->toISOString(),
        ]);
    }
}
