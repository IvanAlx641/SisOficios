<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    // ¿Quién puede ver la tabla de usuarios?
    public function viewAny(User $user): bool
    {
        // Admin TI ya pasó. Permitimos al Titular.
        return $user->rol === 'Titular de área';
    }

    // ¿Quién puede ver el detalle/editar un usuario específico?
    public function view(User $user, User $model): bool
    {
        // El Titular solo puede ver/editar a los de SU propia área
        return $user->rol === 'Titular de área' && $user->unidad_administrativa_id === $model->unidad_administrativa_id;
    }

    // ¿Quién puede crear nuevos usuarios?
    public function create(User $user): bool
    {
        return $user->rol === 'Titular de área';
    }

    // ¿Quién puede guardar los cambios de un usuario?
    public function update(User $user, User $model): bool
    {
        // Igual que view: El Titular solo puede actualizar a los de su área
        return $user->rol === 'Titular de área' && $user->unidad_administrativa_id === $model->unidad_administrativa_id;
    }

    // ¿Quién puede eliminar un usuario?
    public function delete(User $user, User $model): bool
    {
        // El Titular solo puede borrar a los de su área
        return $user->rol === 'Titular de área' && $user->unidad_administrativa_id === $model->unidad_administrativa_id;
    }
}