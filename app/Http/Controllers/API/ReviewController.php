<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewForList as ReviewForListResource;
use App\Http\Resources\ReviewResume as ReviewResumeResource;
use App\Http\Resources\ItemSelector as ItemSelectorResource;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $reviews = Review::all();
        $offset = $request->offset;
        $limit = $request->limit;
        $sort = json_decode($request->sort, true);
        $count_items = $request->count_items;
        $query = Review::when($count_items, function ($query) use ($count_items) {
            $query->count();
        });
        $count_items = Review::count();
        $reviews = Review::offset($offset)
            ->limit($limit)
            ->get();
        $count_items = $query->count();
        if ($sort['isAsc']) {
            $reviews = Review::orderBy("{$sort['key']}", 'asc')->offset($offset)->limit($limit)->get();
        } else {
            $reviews = Review::orderBy("{$sort['key']}", 'desc')->offset($offset)->limit($limit)->get();
        }

        return response()->json([
            'total_items' => $count_items,
            'reviews' => ReviewForListResource::collection($reviews),
        ]);


        return ReviewForListResource::collection($reviews);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        DB::transaction(function () use ($request) {
            // $position = Employee::max('position');
            // $position++;

            $review = new Review;
            $review->name = $request->review['name'];
            $review->comment = $request->review['comment'];

            // $review->position = $position;
            // $user = Auth::user;
            // $user->name =  $request->review['user_name'];
            // $user->email = $request->employee['email'] ?: '';
            // $user->password = bcrypt($request->employee['password']);
            // $user->is_admin = $request->employee['is_admin'];
            // $user->is_leader = $request->employee['is_leader'];
            // $user->save();

            $review->user_id = Auth::id();

            $review->save();
        });

        return response()->json([
            'result' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        DB::transaction(function () use ($review) {

            if (isset($review->user_id)) {
                $review->delete();
            }
            $review->delete();
        });

        return response()->json([
            'result' => true,
        ]);
    }

    public function resume(ProductCost $productcost)
    {
        return new ReviewResumeResource($productcost);
    }
}
