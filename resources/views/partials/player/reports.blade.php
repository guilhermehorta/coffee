<div id="report-group" class="section">
	@include('partials.player.reports.controlPanel')
	@for ($i = 1; $i <= $game->lastReportablePeriod; $i++)
		@include('partials.player.reports.report')
	@endfor
</div>