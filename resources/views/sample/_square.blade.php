@php $uniqid = uniqid() @endphp

<div class="bg-white border rounded shadow mb-3 mt-3 text-center animated">
    <div class="p-3">
        <div class="relative h-24 w-24 rounded mx-auto bg-teal-600 shadow-lg -mt-8 mb-3">
            <img src="{{ $sample->thumbnail_link }}" class="rounded absolute w-full object-cover top-0 bottom-0 left-0 right-0">
        </div>
        <div class="truncate">
            <a href="{{ route('samples.show', $sample) }}" @isset($iframe) target="_blank" @endisset>
                {{ $sample->name }}
            </a>
        </div>
    </div>

    {!! $sample->render([
        'controls' => true,
        'height' => '30',
        'uniqid' => $uniqid,
    ]) !!}
</div>