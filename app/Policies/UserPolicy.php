<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     * (Ver el listado de usuarios)
     */
    public function viewAny(User $user): bool
    {
        return $user->rol === 'Administrador TI';
    }

    /**
     * Determine whether the user can view the model.
     * (Ver detalle de un usuario específico)
     */
    public function view(User $user, User $model): bool
    {
        return $user->rol === 'Administrador TI';
    }

    /**
     * Determine whether the user can create models.
     * (Entrar al formulario de crear)
     */
    public function create(User $user): bool
    {
        return $user->rol === 'Administrador TI';
    }

    /**
     * Determine whether the user can update the model.
     * (Editar usuarios y Enviar Credenciales)
     */
    public function update(User $user, User $model): bool
    {
        return $user->rol === 'Administrador TI';
    }

    /**
     * Determine whether the user can delete the model.
     * (Eliminar usuarios)
     */
    public function delete(User $user, User $model): bool
    {
        return $user->rol === 'Administrador TI';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->rol === 'Administrador TI';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->rol === 'Administrador TI';
    }
}