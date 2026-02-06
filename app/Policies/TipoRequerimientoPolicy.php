<?php

namespace App\Policies;

use App\Models\TipoRequerimiento;
use App\Models\User;

class TipoRequerimientoPolicy
{
    /**
     * Roles permitidos para gestionar este módulo.
     * Se define aquí para reutilizar la lógica en todos los métodos.
     */
    private function tienePermiso(User $user): bool
    {
        return in_array($user->rol, [
            'Administrador TI',
            'Titular de área',
            'Responsable',
            'Capturista'
        ]);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->tienePermiso($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TipoRequerimiento $tiporequerimiento): bool
    {
        return $this->tienePermiso($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->tienePermiso($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TipoRequerimiento $tiporequerimiento): bool
    {
        return $this->tienePermiso($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TipoRequerimiento $tiporequerimiento): bool
    {
        return $this->tienePermiso($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TipoRequerimiento $tiporequerimiento): bool
    {
        return $this->tienePermiso($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TipoRequerimiento $tiporequerimiento): bool
    {
        return $this->tienePermiso($user);
    }
}