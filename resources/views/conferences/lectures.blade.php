@extends('layouts.layout')

@section('content')

@isset($info['role'])
    @if($info['role'] == 'admin')
        <div id='admin_contents'>
            @yield('admin_contents')
        <div>
    @endif
@endisset

<p>Conference starts at: {{ $conference->start_time }}</p>
<p>Conference ends at: {{ $conference->end_time }}</p>
<h2>Lectures</h2>
@csrf
@foreach ($lectures as $item)
    <p>Lecture: <a href="{{ '/lectures/lecture/'.$item->id}}">{{$item->title}}</a></p>
    @if (!$item->is_confirmed)
        <form action="{{ url("/lectures/confirm") }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $item->id }}" required/>
            <input type="hidden" name="conferenceId" value="{{ $item->conference_id }}" required/>
            <label>Room:</label>
            <select name="roomId" required>
                <option value="" disabled selected hidden>Select room</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
            <br>
            <label>Start time:</label>
            <input type="datetime-local" name="startTime" required>
            <br>
            <label>End time:</label>
            <input type="datetime-local" name="endTime" required>
            <br>
            <button type="submit">Confirm</button>
        </form>
    @else
        <p>Room: {{ $rooms->firstWhere('id', $item->room_id)->name}}</p>
        <p>Start time: {{ $item->start_time }} </p>
        <p>End time: {{ $item->end_time }} </p>
        <form action="{{ url("/lectures/unconfirm") }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $item->id }}" required />
            <input type="hidden" name="conferenceId" value="{{ $item->conference_id }}" required/>
            <button type="submit">Cancel</button>
        </form>
    @endif
    <hr>
@endforeach
@endsection
