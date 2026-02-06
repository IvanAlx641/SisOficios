<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Solicitante;
use Illuminate\Auth\Access\HandlesAuthorization;

class SolicitantePolicy
{
    use HandlesAuthorization;

    /**
     * Determinar si el usuario puede ver el listado de solicitantes.
     */
    public function viewAny(User $user): bool
    {
        // Siguiendo tu lógica: Solo el Administrador TI puede gestionar esto
        return $user->rol === 'Administrador TI';
    }

    /**
     * Determinar si el usuario puede ver un solicitante específico.
     */
    public function view(User $user, Solicitante $solicitante): bool
    {
        return $user->rol === 'Administrador TI';
    }

    /**
     * Determinar si el usuario puede crear solicitantes.
     */
    public function create(User $user): bool
    {
        return $user->rol === 'Administrador TI';
    }

    /**
     * Determinar si el usuario puede actualizar/editar un solicitante.
     */
    public function update(User $user, Solicitante $solicitante): bool
    {
        return $user->rol === 'Administrador TI';
    }

    /**
     * Determinar si el usuario puede eliminar un solicitante.
     */
    public function delete(User $user, Solicitante $solicitante): bool
    {
        return $user->rol === 'Administrador TI';
    }

    /**
     * Determinar si el usuario puede restaurar (si usas SoftDeletes).
     */
    public function restore(User $user, Solicitante $solicitante): bool
    {
        return $user->rol === 'Administrador TI';
    }

    /**
     * Determinar si el usuario puede eliminar permanentemente.
     */
    public function forceDelete(User $user, Solicitante $solicitante): bool
    {
        return $user->rol === 'Administrador TI';
    }
}