<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Request;

class UserPolicy
{

    /**
     * Determine whether any user can view the list of models.
     */
    public function viewAny(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view the specified model.
     */
    public function view(User $user, User $model): Response
    {
        // Allow if the user owns the model or is a moderator
        // Deny otherwise
        return $user->id === $model->id || $user->isMod()
            ? Response::allow()
            : Response::deny(__('You are not allowed to view this user.'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if(Request::has('type')) {
            return Response::deny(__('Creating users with a specific type is not allowed.'));
        }

        // Allow if the user is a moderator
        // Deny otherwise
        if ($user->isMod()) {
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can update the specified model.
     */
    public function update(User $user, User $model): Response
    {
        if(Request::has('type')) {
            return Response::deny(__('Updating the "type" attribute is not allowed.'));
        }

        if ($user->id === $model->id) {
            // Todo usuário pode atualizar seu próprio perfil
            return Response::allow();
        }

        if ($user->isMod()) {
            // Moderador pode atualizar qualquer usuário
            return Response::allow();
        }

        // Outros usuários não têm permissão para atualizar usuários
        return Response::deny(__('You are not allowed to update users.'));
    }


    /**
     * Determine whether the user can delete the specified model.
     */
    public function delete(User $user, User $model): Response
    {
        return Response::deny(__('You are not allowed to delete users.'));
    }

    /**
     * Executed before all other authorization checks.
     * Allows the administrator to bypass other checks and have full access.
     */
    public function before($user, $ability)
    {
        // Allow if the user is an administrator
        // Return null otherwise (fall back to other checks)
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }

}
