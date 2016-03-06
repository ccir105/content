<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/6/16
 * Time: 10:22 AM
 */

namespace Modules\Project\Entities;


use App\User;

interface BelongsContract
{
    public function belongs(User $user);
}