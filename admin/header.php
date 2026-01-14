<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<div class="container-fluid">
    <div class="row" style="display: flex;">
        <div class="col-md-8"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/Logo-150.png'; ?>" style="width: 140px;margin-left: 45px"></div>
        <div class="col-md-4">
            <?php if(!$_SESSION['license_status']) { ?>
            <a href="./admin.php?page=hm_settings" style="text-decoration: none;">
                <p style="text-align: center;
                    margin-top: 30px;color: red"><i class="fa fa-lock" aria-hidden="true"></i> License not activated!</p>
            </a>
            <?php } ?>
        </div>
    </div>
</div>