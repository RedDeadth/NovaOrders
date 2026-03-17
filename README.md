# 🚀 NovaOrders - Sistema de Gestión de Pedidos y Ventas

<p align="center">
  <strong>API RESTful + Dashboard React</strong><br>
  Built with Laravel 12 + React (Inertia.js) + TypeScript
</p>

---

## 📋 Descripción

NovaOrders es un sistema de gestión de pedidos y ventas diseñado para retail, e-commerce y pequeños negocios. Implementado con **arquitectura hexagonal (Ports & Adapters)** y **DDD ligero**.

## 🏗️ Arquitectura

```
app/
├── Modules/
│   ├── Auth/           → Autenticación y roles (JWT/Sanctum)
│   ├── Product/        → CRUD de productos y categorías
│   ├── Order/          → Gestión de pedidos con máquina de estados
│   ├── Customer/       → Gestión de clientes
│   └── Report/         → Reportes de ventas y dashboard
├── Shared/
│   ├── Domain/         → Value Objects y Exceptions compartidos
│   └── Infrastructure/ → Providers y Middleware
```

Cada módulo sigue la estructura:
- **Domain/** → Entidades, Value Objects, Repository Interfaces (Ports)
- **Application/** → Use Cases, DTOs
- **Infrastructure/** → Eloquent Models, Repositories (Adapters), Controllers, Requests

## ⚡ Tech Stack

| Tecnología | Versión |
|---|---|
| **Laravel** | 12 |
| **PHP** | 8.2+ |
| **React** | 19+ (Inertia.js) |
| **TypeScript** | 5+ |
| **Vite** | 7+ |
| **Tailwind CSS** | 4+ |
| **SQLite/MySQL** | - |
| **Sanctum** | Auth API tokens |

## 🚀 Instalación

```bash
# Clonar el repositorio
git clone git@github.com:RedDeadth/NovaOrders.git
cd NovaOrders

# Instalar dependencias PHP
composer install

# Instalar dependencias JS
npm install --legacy-peer-deps

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Ejecutar migraciones y seeders
php artisan migrate --seed

# Iniciar servidor de desarrollo
php artisan serve &
npm run dev
```

## 🔐 Credenciales de prueba

| Rol | Email | Password |
|---|---|---|
| Admin | `admin@novaorders.com` | `password` |
| Vendedor | `vendedor@novaorders.com` | `password` |
| Cliente | `cliente@novaorders.com` | `password` |

## 📡 API Endpoints

### Auth
| Método | Ruta | Descripción |
|---|---|---|
| `POST` | `/api/auth/register` | Registro de usuario |
| `POST` | `/api/auth/login` | Login (retorna token) |
| `POST` | `/api/auth/logout` | Logout 🔒 |
| `GET` | `/api/auth/me` | Usuario actual 🔒 |

### Products
| Método | Ruta | Descripción |
|---|---|---|
| `GET` | `/api/products` | Listar productos 🔒 |
| `POST` | `/api/products` | Crear producto 🔒👔 |
| `GET` | `/api/products/{id}` | Ver producto 🔒 |
| `PUT` | `/api/products/{id}` | Actualizar 🔒👔 |
| `DELETE` | `/api/products/{id}` | Eliminar 🔒👔 |

### Orders
| Método | Ruta | Descripción |
|---|---|---|
| `GET` | `/api/orders` | Listar pedidos 🔒 |
| `POST` | `/api/orders` | Crear pedido 🔒 |
| `GET` | `/api/orders/{id}` | Ver pedido 🔒 |
| `PATCH` | `/api/orders/{id}/status` | Cambiar estado 🔒👔 |

### Reports
| Método | Ruta | Descripción |
|---|---|---|
| `GET` | `/api/reports/sales` | Reporte de ventas 🔒🛡️ |
| `GET` | `/api/reports/dashboard` | Dashboard stats 🔒🛡️ |

> 🔒 = Requiere autenticación | 👔 = Admin/Vendedor | 🛡️ = Solo Admin

## 📊 Flujo de Estados de Pedidos

```
pendiente → pagado → enviado → entregado
    ↓          ↓
 cancelado  cancelado
```

## 🧪 Tests

```bash
php artisan test
```

## 📝 Licencia

MIT License © 2026
