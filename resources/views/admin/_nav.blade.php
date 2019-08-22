<div class="flex justify-center md:justify-start mb-6">
    <a href="{{ route('admin.index') }}" class="mx-1 btn btn-lg {{ active_class(if_route('admin.index'), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-lock"></i> Tableau de bord</a>
    <a href="{{ route('admin.static-pages.index') }}" class="mx-1 btn btn-lg {{ active_class(if_route_pattern('admin.static-pages*'), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-file"></i> Pages</a>
</div>