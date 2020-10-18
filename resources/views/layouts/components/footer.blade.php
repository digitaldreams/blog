<script type="text/javascript" src="{{asset('js/manifest.js')}}"></script>
<script type="text/javascript" src="{{asset('js/vendor.js')}}"></script>
<script type="text/javascript" src="{{asset('js/app.js')}}"></script>
<script type="text/javascript" src="{{asset('js/blog-layout-scripts.js')}}"></script>
<script type="text/javascript">

    window.addEventListener('load', async e => {
        if ('serviceWorker' in navigator) {
            try {
                navigator.serviceWorker.register('/serviceWorker.js');
            } catch (e) {
                console.log(e.message);
            }
        }
    });
</script>
@yield('scripts')
@yield('script')
</body>

</html>
