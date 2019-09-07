<div class="flex-container-row justify-between panel-row">
	<div class="flex-container-row justify-start panel-name">
		<span> Relatórios Comerciais</span>
	</div>
	<div class="flex-container-row justify-end panel-list">
		@for ($i = 1; $i <= $game->lastReportablePeriod; $i++)
			<span class="panel-btn" id="btn-detalhes-periodo-{{$i}}" data-name="periodo-{{$i}}" data-group="report-group">
				Periodo {{$i}}
			</span>
		@endfor
	</div>
</div>