<?php

namespace App\Http\Controllers;

use App\Booking;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $bookings = DB::table('bookings')->get();

        return view('bookings.index')
            ->with('bookings', $bookings);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        $users = DB::table('users')->get()->pluck('name', 'id')->prepend('none');
        $rooms = DB::table('rooms')->get()->pluck('number', 'id');

        return view('bookings.create')
            ->with('users', $users)
            ->with('rooms', $rooms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $id = DB::table('bookings')->insertGetId([
            'room_id' => $request->input('room_id'),
            'start' => $request->input('start'),
            'end' => $request->input('end'),
            'is_reservation' => $request->input('is_reservation', false),
            'is_paid' => $request->input('is_reservation', false),
            'notes' => $request->input('notes')
        ]);

        DB::table('bookings_users')->insert([
            'booking_id' => $id,
            'user_id' => $request->input('user_id')
        ]);

        return redirect()->action('BookingController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Booking $booking
     * @return Factory|View
     */
    public function show(Booking $booking)
    {
        return view('bookings.show', ["booking" => $booking]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Booking $booking
     * @return Factory|View
     */
    public function edit(Booking $booking)
    {
        $users = DB::table('users')->get()->pluck('name', 'id')->prepend('none');
        $rooms = DB::table('rooms')->get()->pluck('number', 'id');

        $bookingsUser = DB::table('bookings_users')->where('booking_id', $booking->id)->first();

        return view('bookings.edit')
            ->with('users', $users)
            ->with('rooms', $rooms)
            ->with('booking', $booking)
            ->with('bookingsUser', $bookingsUser);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Booking $booking)
    {
        DB::table('bookings')
            ->where('id', '=', $booking->id)
            ->update([
                'room_id' => $request->input('room_id'),
                'start' => $request->input('start'),
                'end' => $request->input('end'),
                'is_reservation' => $request->input('is_reservation', false),
                'is_paid' => $request->input('is_reservation', false),
                'notes' => $request->input('notes')
            ]);

        DB::table('bookings_users')
            ->where('booking_id', '=', $booking->id)
            ->update(['user_id' => $request->input('user_id')]);

        return redirect()->action('BookingController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Booking $booking)
    {
        DB::table('bookings_users')->where('booking_id', '=', $booking->id)->delete();
        DB::table('bookings')->where('id', '=', $booking->id)->delete();

        return redirect()->action('BookingController@index');
    }
}
