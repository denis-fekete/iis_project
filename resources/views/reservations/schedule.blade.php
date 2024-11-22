@extends('layouts.layout')

@section('content')

<div class="title_block">
    <p class="title">Lecures schedule</h1>
</div>

<div class="card">
    @if ($schedule && count($schedule) > 0)
        <form id="scheduleForm" method="POST" action="{{ url('/reservations/saveSchedule') }}">
            @csrf
            <input type="hidden" name="reservationId" value="{{ $reservationId }}" />
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Room</th>
                        <th>Scheduled</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedule as $lecture)
                        <tr>
                            <td><a class="text_link" href="{{ config('app.url') }}/lectures/lecture/{{ $lecture['id'] }}">{{ $lecture['title'] }}</a></td>
                            <td>{{ $lecture['startTime'] }}</td>
                            <td>{{ $lecture['endTime'] }}</td>
                            <td>{{ $lecture['room'] }}</td>
                            <td>
                                <input
                                    type="checkbox"
                                    name="scheduled[{{ $lecture['id'] }}]"
                                    value="1"
                                    {{ $lecture['scheduled'] ? 'checked' : '' }}
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="schedule-save">
                <button type="submit">Save</button>
            </div>
        </form>
    @else
        <p>No lectures found for the schedule.</p>
    @endif
</div>
@endsection
