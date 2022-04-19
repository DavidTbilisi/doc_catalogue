<?php
namespace App\Perms;


use Illuminate\Support\Facades\Session;

class CustomPermission{
    protected $userPerms;


    public function __construct()
    {
        $this->userPerms = Session::get("perms");
    }


    public function list(): array
    {
        /*
         * Current user permissions as array
         * */

        $permissions = [];
        foreach($this->userPerms['perms'] as $id => $name):
            $permissions[$name] = $id;
        endforeach;
        return $permissions;
    }

    public function hasPerm($perm): bool
    {
        /*
         * Checking perms array
         * return boolean
         * */
        $p = $this->list();
        $permissin_names = array_values( $p );
        $permissin_ids = array_keys( $p );

        return in_array($perm, $permissin_names) or in_array($perm, $permissin_ids);
    }

    public function hasPerms(array $perms): bool
    {
        /*
         * Checking perms array
         * return boolean
         * */
        $p = $this->list();
        $permissin_names = array_values( $p );
        $permissin_ids = array_keys( $p );

        $return = [];

        foreach($perms as $perm):
            $return[] = in_array($perm, $permissin_names) or in_array($perm, $permissin_ids);
        endforeach;

        // თუ გვხვდება ერთი false მაინც, მაშინ ვაბრუნებთ false-ს
        return !in_array(false, $return);
    }

    public function getPermId(string $permName)
    {
        /*
         * returns int
         * */

        $permissions = [];
        foreach($this->userPerms['perms'] as $id => $name):
            $permissions[$name] = $id;
        endforeach;
        return $permissions[$permName];
    }

    public function getPermName(int $permId)
    {
        /*
         * Returns String name of permission
         * */
        $permissions = $this->list();
        return $permissions[$permId];


    }




}
