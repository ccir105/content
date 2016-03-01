<?php namespace Modules\UserManagement\Repositories;
use App\User;
use Modules\UserManagement\Entities\Role;

class UserRepository{

    public function create($data)
    {
        $user = new User;
        $user->fill($data);
        $user->save();
        if( $roles = $this->isRoleUpdate($data) ){

        } else{
            $roles = Role::where('name','client')->first();
        }

        $this->manageRoles($user, $roles);

        return $user;
    }

    public function isRoleUpdate($data){

        return isset($data['roles']) ? $data['roles'] : false;
    }

    public function manageRoles($user, $rolesId)
    {
        $user->roles()->detach();

        $user->roles()->attach($rolesId);
    }

    public function update($model, $data)
    {
        if( $roles = $this->isRoleUpdate($data) )
        {
            $this->manageRoles($model, $roles);
        }

        $model->fill( $data );

        $model->save();

        return $model;
    }
}