@extends('layouts.layout')

@section('content')

<div class="title_block">
    @isset($info['role'])
        @if($info['role'] == 'admin')
            <br>
            <p class="title">(Editing as administrator)</p>
            @php
                $url = 'admin/conferences/conference/reservations';
            @endphp
        @endif
    @endisset

    <p class="title">Conference lectures</p>
</div>

<div class="card">
    <p class="title_2">Conference: {{ $conference->title }}</p>
    <p>Conference starts at: {{ $conference->start_time }}</p>
    <p>Conference ends at: {{ $conference->end_time }}</p>
</div>
<br>
@foreach ($lectures as $item)
    <div class="card">
    <p>Lecture: <a class="text_link" onclick="navigateTo('/lectures/lecture/{{$item->id}}')">{{$item->title}}</a></p>
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
            <input type="datetime-local" name="startTime"
                value="{{ old('start_time', $conference->start_time ? $conference->start_time->format('Y-m-d\TH:i') : '') }}"
                required>
            <br>
            <label>End time:</label>
            <input type="datetime-local" name="endTime"
                value="{{ old('end_time', $conference->end_time ? $conference->end_time->format('Y-m-d\TH:i') : '') }}"
                required>
            <br>
            <button type="submit">Confirm</button>
        </form>
    @else
        <p>Room: {{ $rooms->firstWhere('id', $item->room_id)->name ?? 'default' }}</p>
        <p>Start time: {{ $item->start_time }} </p>
        <p>End time: {{ $item->end_time }} </p>
        <form action="{{ url("/lectures/unconfirm") }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $item->id }}" required />
            <input type="hidden" name="conferenceId" value="{{ $item->conference_id }}" required/>
            <button type="submit">Cancel</button>
        </form>
    @endif
    </div>
    <br>
@endforeach
@endsection
