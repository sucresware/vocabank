@switch($sample->status)
    @case(\App\Models\Sample::STATUS_DRAFT)
        <i class="fa fa-spin fa-spinner"></i> En attente de traitement
        @break
    @case(\App\Models\Sample::STATUS_PROCESSING)
        <i class="fa fa-spin fa-spinner"></i> Traitement en cours
        @break
    @case(\App\Models\Sample::STATUS_PUBLIC)
        <i class="fa fa-check"></i> Public
        @break
@endswitch