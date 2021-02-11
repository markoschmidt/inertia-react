@extends('layouts.app')

@section('content')

<style>
    .image {
        max-width: 500px;
        height: auto;
    }

    .label {
        font-weight: bold;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }

    .sublabel {
        font-weight: bold;
    }

    .value {
        font-weight: normal;
    }
</style>

<form action="{{ route('vision.index') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" />
    <button type="submit">Send</button>
</form>

<div class="image">
    <img src="{{ $filepath }}" />
</div>

<h3 class="label">Google Vision</h3>
@if ($labels ?? false)
<h4 class="label">Categories</h4>
@foreach($labels as $key => $label)
<div class="sublabel">Confidence: <span class="value">{{$key}}</span> --- Category: <span class="value">{{$label}}</span></div>
@endforeach
@endif

@if ($texts ?? false)
<h4 class="label">Texts</h4>
@foreach ($texts as $text)
<div class="sublabel">Confidence: <span class="value">{{$text['confidence']}}</span> --- Text: <span
        class="value">{{$text['text']}}</span></div>
@endforeach
@endif

@if ($props ?? false)
<h4 class="label">Colors</h4>
@foreach ($props as $key => $value)
<div class="sublabel">PixelFraction: <span class="value">{{$key}}</span> --- Color value: <span
        class="value">{{$value}}</span> </div>
@endforeach
@endif



@endsection
