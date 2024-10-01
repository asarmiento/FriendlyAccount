<div class="Report" style="font-size: 18px;">
	<!-- ReportPdf Header -->
	@include('courtCases.report.header')
	<!-- ReportPdf Content-->
	@if($type == 1)
		@include('courtCases.report.content-general')
	@elseif($type == 2)
		@include('courtCases.report.content-receipts')
	@else
		@include('courtCases.report.content-deposits')
	@endif
</div>