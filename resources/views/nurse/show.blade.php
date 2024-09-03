@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Health Examination Details</h1>

        <p>Name: {{ $healthExamination->user->name }}</p>
        <p>Health Exam Picture: <img src="{{ asset('storage/' . $healthExamination->health_examination_picture) }}" alt="Health Exam Picture"></p>
        <p>X-ray Pictures: 
            @foreach(json_decode($healthExamination->xray_picture, true) as $xray)
                <img src="{{ asset('storage/' . $xray) }}" alt="X-ray Picture">
            @endforeach
        </p>
        <p>Lab Result Pictures: 
            @foreach(json_decode($healthExamination->lab_result_picture, true) as $lab)
                <img src="{{ asset('storage/' . $lab) }}" alt="Lab Result Picture">
            @endforeach
        </p>

        <a href="{{ route('health-examination.downloadPdf', $healthExamination->id) }}" class="btn btn-primary">
            Download PDF
        </a>
    </div>
@endsection
