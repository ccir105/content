<?php namespace Modules\Management\Repositories;
use App\User;

class UserRepository{

    public function all()
    {
        return User::all()->load('roles');
    }

    public function find( $id )
    {
        $user = User::find( $id )->with('roles');
        return $user;
    }

    public function create($data)
    {
        $user = new User;
        $user->fill($data);
        $user->save();
        if( $roles = $this->isRoleUpdate($data) ){

            $this->manageRoles($user, $roles);
        }

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

    public function delete($model)
    {
       return $model->delete();
    }
}