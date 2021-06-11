<?php

use Illuminate\Database\Seeder;
use App\Review;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new Review;
        $model->user_id = 2;
        $model->name = '鶏そば　小箱';
        $model->comment = 'とても美味しかった';
        $model->save();
    }
}
