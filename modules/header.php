<header>
<?PHP
    if ($_COOKIE['user'] == null) {
    ?>
    <div class="container-fluid">
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a class="btn btn-sm btn-light text-white rounded-5" style="background-color: #ffc0cb;" href="/auth.php" role="button"> · 访客方式 · </a>
            </div>
        </div>
    </div>
    <?php
    }
?>
</header>