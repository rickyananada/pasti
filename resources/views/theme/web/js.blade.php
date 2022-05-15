<script src="{{asset('semicolon/js/jquery.js')}}"></script>
<script src="{{asset('semicolon/js/plugins.min.js')}}"></script>

<!-- Footer Scripts ============================================= -->
<script src="{{asset('semicolon/js/components/star-rating.js')}}"></script>
<script src="{{asset('semicolon/js/components/bs-filestyle.js')}}"></script>

<script src="{{asset('semicolon/js/functions.js')}}"></script>
<script type="text/javascript">
let APP_URL = "{{config('app.url')}}";


$("#input-16").rating("refresh", {disabled: true, showClear: false});
</script>
<script src="{{asset('js/toastr.js')}}"></script>
<script src="{{asset('js/confirm.js')}}"></script>
<script src="{{asset('js/plugin.js')}}"></script>
<script src="{{asset('js/method.js')}}"></script>