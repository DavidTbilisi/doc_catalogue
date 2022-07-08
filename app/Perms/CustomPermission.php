<?php
namespace App\Perms;


use App\Facades\Perms;
use App\Models\IoGroupsPermissions;
use App\Models\Permission;
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
            $inNames = in_array($perm, $permissin_names);
            $inIds = in_array($perm, $permissin_ids);
            $return[] =  ($inNames or $inIds);
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
        return $permissions[strtolower($permName)];
    }

    public function getPermName(int $permId)
    {
        /*
         * Returns String name of permission
         * */
        $permissions = $this->list();
        return $permissions[$permId];
    }

    public function isGroup($name)
    {
        return Session::get("user")["group"]["name"] == strtolower($name);
    }

    public function all()
    {
        return Permission::orderBy("power", "ASC")->get();
    }



    public function fondPerms(int $number)
    {
        $permissions = $this->all();
        $permittedBin = strrev(decbin($number));  // Converting decimal to binary + reverse str
        $permitted = [];

        for ($i = 0; $i < strlen($permittedBin); $i++) {
            $binary = $permittedBin[$i];
            $permission = $permissions[$i];

            if ($binary == 1) {
                $permitted[$permission->id] = $permission->const_name;
            }
        }

        return [
            "all"=>$permissions,
            "permitted"=>$permitted
        ];
    }

    public function hasPermsIo($io_id)
    {
        // group has perm to interact with io
        $current_user_group_id = Session("user")['group_id'];

        $io_group_permissions = IoGroupsPermissions::
        where("groups_id", $current_user_group_id)
            ->where('io_id',$io_id)
            ->first(); // ვიღებ ფერმიშენის რიცხვს

        $permitted = Perms::fondPerms($io_group_permissions->permission)["permitted"];

        return $permitted;
    }



}
