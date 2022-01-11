<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->email = "user1@gmail.com";
        $user->password = "$2y$10\$HS4Bw19Gb2kpc6ujYK2oSe2G0DV8rCM2ols1ChwpVecDwC1FInFF.";
        $user->role = "dosen";
        $user->created_at = \date("Y-m-d H:i:s");
        $user->updated_at = \date("Y-m-d H:i:s");
        $user->save();
    }
}