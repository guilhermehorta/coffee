<div id="top-button-group" class="flex-container-row justify-center">
	<div class="btn-group" role="group" aria-label="...">
		<a href="/games" class="btn btn-default" role="button">{{ __('Games') }}</a>
		@if (Auth::user()->is_admin)
		<a href="/rules" class="btn btn-default" role="button">{{ __('Rules') }}</a>
		@endif
	</div>
</div>