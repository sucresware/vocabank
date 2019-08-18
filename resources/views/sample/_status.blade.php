@switch($sample->status)
    @case(\App\Models\Sample::STATUS_DRAFT)
        Brouillon
        @break
    @case(\App\Models\Sample::STATUS_PROCESSING)
        En traitement
        @break
    @case(\App\Models\Sample::STATUS_PUBLIC)
        Public
        @break
    @case(\App\Models\Sample::STATUS_UNLISTED)
        Non répertorié
        @break
    @case(\App\Models\Sample::STATUS_REMOVED)
        Supprimé
        @break
@endswitch