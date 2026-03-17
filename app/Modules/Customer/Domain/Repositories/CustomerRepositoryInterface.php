<?php

declare(strict_types=1);

namespace App\Modules\Customer\Domain\Repositories;

use App\Modules\Customer\Domain\Entities\Customer;

interface CustomerRepositoryInterface
{
    public function findById(int $id): ?Customer;
    public function all(array $filters = []): array;
    public function save(Customer $customer): Customer;
    public function update(int $id, Customer $customer): Customer;
    public function delete(int $id): void;
    public function findByDocument(string $documentNumber): ?Customer;
}
