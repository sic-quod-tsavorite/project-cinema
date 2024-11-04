</div>
<footer>this will be footer</footer>
<script src="<?php echo $base_url; ?>/node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
<script src="<?php echo $base_url; ?>/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $base_url; ?>/includes/scripts/script.js"></script>
</body>

</html>

<?php
if (isset($connection)) {
    $connection = null;
}
?>