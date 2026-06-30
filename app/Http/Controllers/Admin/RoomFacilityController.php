<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Facility;
use App\Models\RoomFacility;
use Illuminate\Http\Request;

class RoomFacilityController extends Controller
{
    /**
     * Display room facilities management.
     */
    public function index(Request $request)
    {
        $rooms = Room::with(['facilities' => function ($query) {
            $query->withPivot('status');
        }])->get();

        $facilities = Facility::all();

        // Filter by room if specified
        if ($request->filled('room_id')) {
            $rooms = Room::with(['facilities' => function ($query) {
                $query->withPivot('status');
            }])->where('id', $request->room_id)->get();
        }

        return view('admin.room-facilities.index', compact('rooms', 'facilities'));
    }

    /**
     * Assign facility to room.
     */
    public function attach(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'facility_id' => 'required|exists:facilities,id',
            'status' => 'required|in:tersedia,rusak,maintenance',
        ]);

        $room = Room::findOrFail($request->room_id);
        $facility = Facility::findOrFail($request->facility_id);

        // Check if already attached
        $existing = RoomFacility::where('room_id', $request->room_id)
            ->where('facility_id', $request->facility_id)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', "Fasilitas \"{$facility->nama}\" sudah terpasang di ruang \"{$room->nama}\".");
        }

        // Attach with status
        $room->facilities()->attach($request->facility_id, [
            'status' => $request->status,
        ]);

        return redirect()->back()
            ->with('success', "Fasilitas \"{$facility->nama}\" berhasil ditambahkan ke ruang \"{$room->nama}\".");
    }

    /**
     * Update room facility status.
     */
    public function update(Request $request, int $roomId, int $facilityId)
    {
        $request->validate([
            'status' => 'required|in:tersedia,rusak,maintenance',
        ]);

        $roomFacility = RoomFacility::where('room_id', $roomId)
            ->where('facility_id', $facilityId)
            ->firstOrFail();

        $roomFacility->update(['status' => $request->status]);

        $room = Room::find($roomId);
        $facility = Facility::find($facilityId);

        return redirect()->back()
            ->with('success', "Status fasilitas \"{$facility->nama}\" di ruang \"{$room->nama}\" berhasil diperbarui.");
    }

    /**
     * Remove facility from room.
     */
    public function detach(int $roomId, int $facilityId)
    {
        $room = Room::findOrFail($roomId);
        $facility = Facility::findOrFail($facilityId);

        $room->facilities()->detach($facilityId);

        return redirect()->back()
            ->with('success', "Fasilitas \"{$facility->nama}\" berhasil dihapus dari ruang \"{$room->nama}\".");
    }

    /**
     * Bulk assign facilities to room.
     */
    public function bulkAttach(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'facilities' => 'required|array',
            'facilities.*' => 'exists:facilities,id',
            'status' => 'required|in:tersedia,rusak,maintenance',
        ]);

        $room = Room::findOrFail($request->room_id);
        $facilities = Facility::whereIn('id', $request->facilities)->get();

        $attached = [];
        foreach ($facilities as $facility) {
            // Check if already attached
            $existing = RoomFacility::where('room_id', $request->room_id)
                ->where('facility_id', $facility->id)
                ->first();

            if (!$existing) {
                $room->facilities()->attach($facility->id, [
                    'status' => $request->status,
                ]);
                $attached[] = $facility->nama;
            }
        }

        if (empty($attached)) {
            return redirect()->back()
                ->with('info', 'Tidak ada fasilitas baru yang ditambahkan.');
        }

        return redirect()->back()
            ->with('success', count($attached) . ' fasilitas berhasil ditambahkan ke ruang "' . $room->nama . '".');
    }

    /**
     * Get facilities for a room (AJAX).
     */
    public function getRoomFacilities(int $roomId)
    {
        $room = Room::with(['facilities' => function ($query) {
            $query->withPivot('status');
        }])->findOrFail($roomId);

        return response()->json([
            'room' => [
                'id' => $room->id,
                'nama' => $room->nama,
                'kode' => $room->kode,
            ],
            'facilities' => $room->facilities->map(function ($facility) {
                return [
                    'id' => $facility->id,
                    'nama' => $facility->nama,
                    'icon' => $facility->icon,
                    'status' => $facility->pivot->status,
                    'status_label' => $facility->pivot->getStatusLabelAttribute(),
                    'status_color' => $facility->pivot->getStatusColorAttribute(),
                ];
            }),
        ]);
    }

    /**
     * Get available facilities for a room (AJAX).
     */
    public function getAvailableFacilities(int $roomId)
    {
        $room = Room::findOrFail($roomId);

        // Get all facilities
        $allFacilities = Facility::all();

        // Get existing facility IDs for this room
        $existingIds = $room->facilities->pluck('id')->toArray();

        // Filter out existing
        $available = $allFacilities->filter(function ($facility) use ($existingIds) {
            return !in_array($facility->id, $existingIds);
        });

        return response()->json([
            'room_id' => $roomId,
            'available' => $available->values(),
        ]);
    }

        /**
     * Restore a soft-deleted facility from room.
     */
    public function restore(int $roomId, int $facilityId)
    {
        $roomFacility = RoomFacility::where('room_id', $roomId)
            ->where('facility_id', $facilityId)
            ->withTrashed()
            ->firstOrFail();

        $roomFacility->restore();

        $room = Room::find($roomId);
        $facility = Facility::find($facilityId);

        return redirect()->back()
            ->with('success', "Fasilitas \"{$facility->nama}\" berhasil dipulihkan di ruang \"{$room->nama}\".");
    }

        /**
     * Permanently delete a facility from room.
     */
    public function forceDetach(int $roomId, int $facilityId)
    {
        $roomFacility = RoomFacility::where('room_id', $roomId)
            ->where('facility_id', $facilityId)
            ->withTrashed()
            ->firstOrFail();

        $roomFacility->forceDelete();

        $room = Room::find($roomId);
        $facility = Facility::find($facilityId);

        return redirect()->back()
            ->with('success', "Fasilitas \"{$facility->nama}\" berhasil dihapus permanen dari ruang \"{$room->nama}\".");
    }
}
