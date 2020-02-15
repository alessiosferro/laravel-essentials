<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http;
use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;

class ShowRoomsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  Http\Request  $request
     * @return Factory | View
     */
    public function __invoke(Request $request)
    {
        $rooms = DB::table('rooms')->get();

        if($request->query('id') !== null) {
            $rooms = $rooms->where('room_type_id', '=', $request->query('id'));
        }

        return view('rooms.index', ["rooms" => $rooms]);
    }
}
