<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index(Request $request)
    {
        $tickets = Ticket::with('destination')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tickets
        ]);
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'visitor_name'   => 'required|string|max:255',
            'visitor_email'  => 'required|email|max:255',
            'visitor_phone'  => 'required|string|max:50',
            'visit_date'     => 'required|date',
            'quantity'       => 'required|integer|min:1',
            'payment_method' => 'nullable|string',
        ]);

        $destination = Destination::findOrFail($request->destination_id);
        $pricePerTicket = $destination->ticket_price ?? 0;
        $totalPrice = $pricePerTicket * $request->quantity;

        $ticketCode = 'ET-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        $ticket = Ticket::create([
            'ticket_code'      => $ticketCode,
            'user_id'          => auth('sanctum')->id(),
            'destination_id'   => $destination->id,
            'visitor_name'     => $request->visitor_name,
            'visitor_email'    => $request->visitor_email,
            'visitor_phone'    => $request->visitor_phone,
            'visit_date'       => $request->visit_date,
            'quantity'         => $request->quantity,
            'price_per_ticket' => $pricePerTicket,
            'total_price'      => $totalPrice,
            'payment_method'   => $request->payment_method ?? 'QRIS',
            'status'           => 'paid',
        ]);

        $ticket->load('destination');

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil dipesan!',
            'data'    => $ticket
        ], 201);
    }

    /**
     * Display the specified ticket.
     */
    public function show($id)
    {
        $ticket = Ticket::with('destination')
            ->where('id', $id)
            ->orWhere('ticket_code', $id)
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $ticket
        ]);
    }
}
