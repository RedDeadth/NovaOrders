<?php

declare(strict_types=1);

namespace App\Modules\Order\Domain\Repositories;

use App\Modules\Order\Domain\Entities\Order;

interface OrderRepositoryInterface
{
    public function findById(int $id): ?Order;
    public function all(array $filters = []): array;
    public function save(Order $order): Order;
    public function updateStatus(int $id, string $status): Order;
    public function delete(int $id): void;
    public function findByCustomer(int $customerId): array;
    public function salesReport(string $startDate, string $endDate): array;
}
