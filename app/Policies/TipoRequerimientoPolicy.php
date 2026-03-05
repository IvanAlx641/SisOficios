<?php

namespace App\Policies;

use App\Models\TipoRequerimiento;
use App\Models\User;

class TipoRequerimientoPolicy
{
    private function tienePermiso(User $user): bool
    {
        // QUITAMOS AL CAPTURISTA DE AQUÍ
        return in_array($user->rol, [
            'Administrador TI',
            'Titular de área',
            'Responsable'
        ]);
    }

    public function viewAny(User $user): bool { return $this->tienePermiso($user); }
    public function view(User $user, TipoRequerimiento $tiporequerimiento): bool { return $this->tienePermiso($user); }
    public function create(User $user): bool { return $this->tienePermiso($user); }
    public function update(User $user, TipoRequerimiento $tiporequerimiento): bool { return $this->tienePermiso($user); }
    public function delete(User $user, TipoRequerimiento $tiporequerimiento): bool { return $this->tienePermiso($user); }
    public function restore(User $user, TipoRequerimiento $tiporequerimiento): bool { return $this->tienePermiso($user); }
    public function forceDelete(User $user, TipoRequerimiento $tiporequerimiento): bool { return $this->tienePermiso($user); }
}