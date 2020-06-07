<script type="text/javascript">
    document.addEventListener("keydown", function (e) {
        checkKey(e);
    });

    function checkKey(e) {
        e = e || window.event;
        if (e.keyCode == '37') {
            @if(!empty($records->previousPageUrl()))
                window.location.href = '{!! $records->previousPageUrl() !!}'
            @endif
        }
        else if (e.keyCode == '39') {
            @if(!empty($records->nextPageUrl()))
                window.location.href = '{!! $records->nextPageUrl()  !!}'
            @endif
        }
    }

    document.addEventListener('touchstart', handleTouchStart, false);
    document.addEventListener('touchmove', handleTouchMove, false);

    var xDown = null;
    var yDown = null;

    function getTouches(evt) {
        return evt.touches ||             // browser API
            evt.originalEvent.touches; // jQuery
    }

    function handleTouchStart(evt) {
        const firstTouch = getTouches(evt)[0];
        xDown = firstTouch.clientX;
        yDown = firstTouch.clientY;
    };

    function handleTouchMove(evt) {
        if (!xDown || !yDown) {
            return;
        }
        var xUp = evt.touches[0].clientX;
        var yUp = evt.touches[0].clientY;

        var xDiff = xDown - xUp;
        var yDiff = yDown - yUp;
        if (Math.abs(xDiff) > Math.abs(yDiff)) {/*most significant*/
            if (xDiff > 0) {
                window.location.href = '{!! $records->nextPageUrl() !!}'
            } else {
                window.location.href = '{!! $records->previousPageUrl() !!}'
            }
        }
        /* reset values */
        xDown = null;
        yDown = null;
    };
</script>