<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new User;$model->name = 'mg-iguchi';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-yamakawa';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-kuninaka';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-asato';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-sugisaki';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-kushida';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-matsumoto';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-tsuji';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-kawada';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-shinzato';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-miyabayashi';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-hanatani';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-oikawa';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-kuwahara';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-fukuta';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-miyata';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-ueno';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-onishi';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-kakehashi';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-saito';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-kimura';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-shibata';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-maeda';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-hosaka';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-kawabata';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-ushijima';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-komatsuk';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-hisada';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-motoyama';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-yokoyamak';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-yokoyaman';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'mg-mukai';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
    }
}
