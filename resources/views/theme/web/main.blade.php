<!DOCTYPE html>
<html dir="ltr" lang="en-US">
@include('theme.web.head')
<body class="stretched">
	<!-- Document Wrapper ============================================= -->
	<div id="wrapper" class="clearfix">
		<!-- Header ============================================= -->
		@include('theme.web.header')
        <!-- #header end -->
        @if (request()->is('home'))
		@include('theme.web.slider')
        @endif
		<!-- Content ============================================= -->
		{{$slot}}
        <!-- #content end -->
		<!-- Footer ============================================= -->
		@include('theme.web.footer')
        <!-- #footer end -->
	</div>
	<div class="modal fade" id="reviewFormModal" tabindex="-1" role="dialog" aria-labelledby="reviewFormModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" id="contentReviewModal">
				
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
    <!-- #wrapper end -->
	<!-- Go To Top ============================================= -->
	<div id="gotoTop" class="icon-angle-up"></div>
	<!-- JavaScripts ============================================= -->
	@include('theme.web.js')
	@yield('custom_js')
</body>
</html>