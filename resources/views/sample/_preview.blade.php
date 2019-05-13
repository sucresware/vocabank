<div class="row align-items-center mb-3">
    <div class="col-auto">
        <div class="sample-thumbnail shadow">
            <a href="{{ route('samples.show', $sample) }}"><img src="{{ $sample->thumbnail_link }}" class="rounded"></a>
        </div>
    </div>
    <div class="col">
        <div class="row align-items-center">
            <div class="col-auto pr-0">
                <div class="btn-group text-center d-block">
                    <a href="javascript:void(0)" class="btn btn-primary rounded" data-wavecontrol data-target="{{ $uniqid = uniqid() }}" data-control="toggle"><i class="far fa-play-circle fa-fw"></i></a>
                </div>
            </div>
            <div class="col">
                <div class="text-small"><a href="{{ route('users.show', $sample->user) }}">{{ $sample->user->name }}</div>
                <strong><a href="{{ route('samples.show', $sample) }}">{{ $sample->name }}</a></strong>
            </div>
            <div class="col-auto">
                @foreach($sample->tags as $tag)
                    <a href="{{ route('samples.search') }}?q={{ $tag->name }}" class="badge badge-secondary">{{ $tag->name }}</a>
                @endforeach
            </div>
        </div>
        <div class="mt-2">
            {!! $sample->render([
                'controls' => false,
                'height' => '60',
                'uniqid' => $uniqid,
            ]) !!}
        </div>
    </div>
</div>