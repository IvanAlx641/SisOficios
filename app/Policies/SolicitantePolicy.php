<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Solicitante;
use Illuminate\Auth\Access\HandlesAuthorization;

class SolicitantePolicy
{
    use HandlesAuthorization;

    // Función auxiliar para no repetir código
    private function tienePermiso(User $user): bool
    {
        return in_array($user->rol, ['Administrador TI', 'Capturista']);
    }

    public function viewAny(User $user): bool { return $this->tienePermiso($user); }
    public function view(User $user, Solicitante $solicitante): bool { return $this->tienePermiso($user); }
    public function create(User $user): bool { return $this->tienePermiso($user); }
    public function update(User $user, Solicitante $solicitante): bool { return $this->tienePermiso($user); }
    public function delete(User $user, Solicitante $solicitante): bool { return $this->tienePermiso($user); }
    public function restore(User $user, Solicitante $solicitante): bool { return $this->tienePermiso($user); }
    public function forceDelete(User $user, Solicitante $solicitante): bool { return $this->tienePermiso($user); }
}