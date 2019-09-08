<script>
    function scrollingPageToTop() {
        var dn = $("div#top").offset().top;
        $('html, body').animate({scrollTop: dn}, 1000);
    }
</script>