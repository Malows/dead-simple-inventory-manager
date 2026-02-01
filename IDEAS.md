# Propuestas de Mejora Técnica para Dead Simple Inventory Manager

Este documento detalla tres propuestas técnicas para mejorar la robustez, el rendimiento y la mantenibilidad del sistema, incluyendo especificaciones de implementación.

## 1. Historial de Movimientos (Kardex / Audit Log)

**Prioridad:** Alta
**Objetivo:** Integridad de datos y auditoría completa.

### Implementación Técnica

#### A. Esquema de Base de Datos
Nueva migración para la tabla `inventory_movements`:

```php
Schema::create('inventory_movements', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignIdFor(Product::class)->constrained();
    $table->foreignIdFor(User::class)->constrained(); // Usuario que realizó la acción
    $table->string('type'); // Enum: 'purchase', 'sale', 'adjustment', 'return'
    $table->integer('quantity'); // Puede ser negativo o positivo
    $table->integer('previous_stock'); // Snapshot
    $table->integer('new_stock'); // Snapshot
    $table->text('notes')->nullable();
    $table->timestamps();
    
    // Índices para búsquedas rápidas
    $table->index(['product_id', 'created_at']);
});
```

#### B. Lógica de Negocio (Service Pattern)
Encapsular la lógica en `App\Services\InventoryService` para garantizar consistencia mediante transacciones ACID.

```php
public function adjustStock(Product $product, int $quantity, string $type, ?string $notes = null)
{
    return DB::transaction(function () use ($product, $quantity, $type, $notes) {
        // 1. Registrar el movimiento histórico (Log inmutable)
        InventoryMovement::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'type' => $type,
            'quantity' => $quantity,
            'previous_stock' => $product->stock,
            'new_stock' => $product->stock + $quantity,
            'notes' => $notes,
        ]);

        // 2. Actualizar el estado actual (Cache/State)
        // Esto dispara el Observer si se implementa la mejora #3
        $product->increment('stock', $quantity); 
        
        return $product->fresh();
    });
}
```

---

## 2. Rendimiento: Scope SQL para "Bajo Stock"

**Prioridad:** Media
**Objetivo:** Optimización de consultas y reducción de consumo de memoria.

### Implementación Técnica

Reemplazar el filtrado en memoria (PHP) por filtrado en base de datos (SQL) utilizando un **Local Scope** en `App\Models\Product`.

```php
use Illuminate\Database\Eloquent\Builder;

// En App\Models\Product.php
public function scopeLowStock(Builder $query): void
{
    // Utiliza 'whereColumn' para comparar dos columnas de la misma fila
    $query->whereColumn('stock', '<=', 'min_stock_warning');
}
```

### Impacto en SQL
Esta implementación transforma la consulta.
**Antes (Ineficiente):** `SELECT * FROM products` -> PHP filtra miles de objetos.
**Después (Eficiente):**
```sql
SELECT * FROM products 
WHERE stock <= min_stock_warning
```
Esto permite paginación real: `Product::lowStock()->paginate(20);`

---

## 3. Refactorización: Desacoplar Lógica con Observers

**Prioridad:** Baja
**Objetivo:** Limpieza del Modelo y separación de responsabilidades (SoC).

### Implementación Técnica

Eliminar la lógica de "efectos secundarios" de los Mutators (`set: fn...`) en el Modelo y moverla a un Observer.

**1. Crear `App\Observers\ProductObserver`:**

```php
namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    public function updating(Product $product): void
    {
        // Detectar cambios en atributos específicos antes de guardar
        if ($product->isDirty('price')) {
            $product->last_price_update = now();
        }

        if ($product->isDirty('stock')) {
            $product->last_stock_update = now();
        }
    }
}
```

**2. Registrar el Observer en `App\Providers\EventServiceProvider` (o `AppServiceProvider` en Laravel 11+):**

```php
use App\Models\Product;
use App\Observers\ProductObserver;

public function boot(): void
{
    Product::observe(ProductObserver::class);
}
```

Esto deja el modelo `Product` limpio, actuando puramente como una definición de entidad y relaciones.
