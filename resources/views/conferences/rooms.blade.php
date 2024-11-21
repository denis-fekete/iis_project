@extends('layouts.layout')

@section('content')

@php
    $url = 'conferences/conference/rooms';
@endphp

<div class="card">
    @if ($rooms && count($rooms) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Rooms</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $room)
                    <tr>
                        <td>
                            <form method="POST" action="/conferences/conference/updateRoom">
                                @csrf
                                <input type="hidden" name="conference_id" value="{{ $id }}" />
                                <input type="hidden" name="room_id" value={{ $room['id'] }} />
                                <input type="text" name="name" value="{{ $room['name'] }}"/>
                                <button type="submit">Save</button>
                            </form>
                        </td>
                        <td>
                            @if ($room['canBeDeleted'])
                                <form method="POST" action="/conferences/conference/deleteRoom">
                                    @csrf
                                    <input type="hidden" name="conference_id" value="{{ $id }}" />
                                    <input type="hidden" name="room_id" value={{ $room['id'] }} />
                                    <button type="submit">Delete</button>
                                </form>
                            @else
                                <span>Room is being used</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No rooms found for this conference.</p>
    @endif
</div>
<br>
<div class="card">
    <h3>Add new room:</h3>
    <form method="POST" action="/conferences/conference/createRoom">
        @csrf
        <input type="hidden" name="conference_id" value="{{ $id }}" />
        <input type="text" name="name" placeholder="Room name" required />
        <button type="submit">Add Room</button>
    </form>
</div>

@endsection
