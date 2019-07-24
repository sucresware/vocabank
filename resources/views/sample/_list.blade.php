{{--  @php $uniqid = uniqid() @endphp

<div class="px-2 py-2 w-full flex items-center relative">
    <div class="absolute px-5 top-0 bottom-0 left-0 right-0" id="waveform-{{ $uniqid}}"><img src="/img/waveform.png" class="w-full h-full" style="opacity: 0.20"></div>
    <div class="absolute px-5 top-0 bottom-0 left-0 right-0"><div class="w-full h-full" style="background: linear-gradient(0deg, #FFFFFF 0%, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 100%);"></div></div>

    <div class="relative h-8 w-8">
        <img src="{{ $sample->thumbnail_link }}" class="rounded-full border-2 border-gray-400 absolute object-cover top-0 bottom-0 left-0 right-0">
        <div class="opacity-0 hover:opacity-100 rounded-full border-2 border-gray-400 absolute top-0 bottom-0 left-0 right-0 text-white flex items-center justify-center" style="background: rgba(0, 0, 0, 0.5)" onclick="$('#controls-{{ $uniqid }}').slideDown('fast'); $('#waveform-{{ $uniqid }}').fadeOut('fast');">
            <i class="text-xs fas fa-play"></i>
        </div>
    </div>
    <div class="z-20 ml-3 truncate font-bold">
        <a href="{{ route('samples.show', $sample) }}" @isset($iframe) target="_blank" @endisset>{{ $sample->name }}</a>
    </div>
    <div class="z-20 ml-auto">
        <i class="fas fa-undo"></i> {{ rand(10, 150) }}
    </div>

    <div class="absolute bottom-0 left-0 bg-teal-400" style="height: 3px;"></div>
</div>
<div id="controls-{{ $uniqid }}" style="height: 30px; display: none;">
    {!! $sample->render([
        'controls' => false,
        'height' => '30',
        'uniqid' => $uniqid,
    ]) !!}
</div>  --}}

<sample-component :sample="{{ $sample }}"></sample-component>