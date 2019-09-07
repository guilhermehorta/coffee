<div class="flex-container-row justify-start panel-row panel-name">
	<span> Estado do Jogo</span>
</div>
@if (in_array($game->phase, ['inbound', 'production', 'outbound', 'cleanup']))
	@include('partials.manager.status.status')
	@include('partials.manager.status.orders')
@endif

@if (in_array($game->phase, ['postInbound', 'postProduction', 'postOutbound', 'postCleanup']))
	@include('partials.manager.status.oldStatus')
	@include('partials.manager.status.orders')
	@include('partials.manager.status.masterReports')
	@include('partials.manager.status.status')
@endif