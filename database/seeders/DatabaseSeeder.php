<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Modules\Product\Infrastructure\Models\CategoryModel;
use App\Modules\Product\Infrastructure\Models\ProductModel;
use App\Modules\Customer\Infrastructure\Models\CustomerModel;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        $admin = User::create([
            'name' => 'Admin NovaOrders',
            'email' => 'admin@novaorders.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $vendedor = User::create([
            'name' => 'Carlos Vendedor',
            'email' => 'vendedor@novaorders.com',
            'password' => Hash::make('password'),
            'role' => 'vendedor',
        ]);

        $cliente = User::create([
            'name' => 'María Cliente',
            'email' => 'cliente@novaorders.com',
            'password' => Hash::make('password'),
            'role' => 'cliente',
        ]);

        // Categories
        $electronics = CategoryModel::create([
            'name' => 'Electrónicos',
            'description' => 'Productos electrónicos y tecnología',
            'slug' => 'electronicos',
        ]);

        $clothing = CategoryModel::create([
            'name' => 'Ropa y Accesorios',
            'description' => 'Vestimenta y complementos',
            'slug' => 'ropa-accesorios',
        ]);

        $food = CategoryModel::create([
            'name' => 'Alimentos',
            'description' => 'Productos alimenticios',
            'slug' => 'alimentos',
        ]);

        $home = CategoryModel::create([
            'name' => 'Hogar',
            'description' => 'Artículos para el hogar',
            'slug' => 'hogar',
        ]);

        // Products
        ProductModel::create([
            'name' => 'Laptop HP 15"',
            'description' => 'Laptop HP con procesador Intel i5, 8GB RAM, 256GB SSD',
            'price' => 2499.99,
            'stock' => 15,
            'category_id' => $electronics->id,
            'sku' => 'ELEC-001',
        ]);

        ProductModel::create([
            'name' => 'Mouse Inalámbrico Logitech',
            'description' => 'Mouse ergonómico inalámbrico Bluetooth',
            'price' => 89.90,
            'stock' => 50,
            'category_id' => $electronics->id,
            'sku' => 'ELEC-002',
        ]);

        ProductModel::create([
            'name' => 'Audífonos Sony WH-1000XM5',
            'description' => 'Audífonos con cancelación de ruido',
            'price' => 1299.00,
            'stock' => 8,
            'category_id' => $electronics->id,
            'sku' => 'ELEC-003',
        ]);

        ProductModel::create([
            'name' => 'Polo Nike Dri-FIT',
            'description' => 'Polo deportivo de secado rápido',
            'price' => 129.90,
            'stock' => 100,
            'category_id' => $clothing->id,
            'sku' => 'ROPA-001',
        ]);

        ProductModel::create([
            'name' => 'Zapatillas Adidas Ultraboost',
            'description' => 'Zapatillas deportivas con tecnología Boost',
            'price' => 599.00,
            'stock' => 25,
            'category_id' => $clothing->id,
            'sku' => 'ROPA-002',
        ]);

        ProductModel::create([
            'name' => 'Café Orgánico 500g',
            'description' => 'Café peruano de altura orgánico certificado',
            'price' => 35.00,
            'stock' => 200,
            'category_id' => $food->id,
            'sku' => 'ALIM-001',
        ]);

        ProductModel::create([
            'name' => 'Aceite de Oliva Extra Virgen',
            'description' => 'Aceite de oliva premium 500ml',
            'price' => 28.90,
            'stock' => 75,
            'category_id' => $food->id,
            'sku' => 'ALIM-002',
        ]);

        ProductModel::create([
            'name' => 'Juego de Sábanas King',
            'description' => 'Set de sábanas 100% algodón egipcio',
            'price' => 189.00,
            'stock' => 30,
            'category_id' => $home->id,
            'sku' => 'HOGAR-001',
        ]);

        // Customers
        CustomerModel::create([
            'user_id' => $cliente->id,
            'name' => 'María García López',
            'email' => 'maria@gmail.com',
            'phone' => '987654321',
            'address' => 'Av. Javier Prado 1234',
            'city' => 'Lima',
            'document_number' => '12345678',
        ]);

        CustomerModel::create([
            'user_id' => null,
            'name' => 'Juan Pérez Rojas',
            'email' => 'juanperez@gmail.com',
            'phone' => '912345678',
            'address' => 'Jr. de la Unión 567',
            'city' => 'Lima',
            'document_number' => '87654321',
        ]);

        CustomerModel::create([
            'user_id' => null,
            'name' => 'Ana Torres Quispe',
            'email' => 'anatorres@gmail.com',
            'phone' => '956789012',
            'address' => 'Calle Los Rosales 89',
            'city' => 'Arequipa',
            'document_number' => '11223344',
        ]);
    }
}
