<?php
namespace Indianic\CurrencyManagement\Policies;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CurrencyManagementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any currency management.
     *
     * @param Admin $user
     * @return bool
     */
    public function viewAny(Admin $user): bool
    {
        return $user->hasPermissionTo('view currency-management');
    }

    /**
     * Determine whether the user can view the currency-management.
     *
     * @param Admin $user
     * @return bool
     */
    public function view(Admin $user): bool
    {
        return ( $user->hasPermissionTo('view currency-management'));
    }

    /**
     * Determine whether the user can create currency-management.
     *
     * @param Admin $user
     * @return bool
     */
    public function create(Admin $user): bool
    {
        return ( $user->hasPermissionTo('create currency-management'));
    }

    /**
     * Determine whether the user can update the currency-management.
     *
     * @param Admin $user
     * @return bool
     */
    public function update(Admin $user): bool
    {
        return $user->hasPermissionTo('update currency-management');
    }

    /**
     * Determine whether the user can delete the currency-management.
     *
     * @return Response|bool
     */
    public function delete(): Response|bool
    {
        return $user->hasPermissionTo('delete currency-management');
    }
}
