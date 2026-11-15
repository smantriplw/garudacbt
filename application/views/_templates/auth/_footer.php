<script src="<?= base_url() ?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>/assets/adminlte/dist/js/adminlte.min.js"></script>
<script>
    (function () {
        var slides = document.querySelectorAll('.login-bg-slide');
        if (slides.length < 2) {
            return;
        }
        var current = Math.floor(Math.random() * slides.length);
        for (var i = 0; i < slides.length; i++) {
            slides[i].classList.toggle('active', i === current);
        }
        setInterval(function () {
            slides[current].classList.remove('active');
            current = (current + 1) % slides.length;
            slides[current].classList.add('active');
        }, 8000);
    })();
</script>
</body>
</html>
