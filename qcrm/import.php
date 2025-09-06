<?php

global $db;
include('includes/head.php');

?>
    <link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
<?php

include('includes/css.php');

?>
    <body>

    <!-- Begin page -->
    <div id="wrapper">


        <?php
        include('includes/topbar.php');
        include('includes/sidebar.php');

        /**
         * Escape User Input Values POST & GET
         */
        GF::escapeReq();
        ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Import</h4>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active">
                                        Welcome <?php echo htmlspecialchars($_SESSION["username"]); ?> to <?php echo Broker['title'];?>
                                    </li>
                                </ol>
                                <div class="state-information d-none d-sm-block">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <!-- Import Upload - Widget -->
                        <?php factory::widget('import_upload', 'Upload File <small>excel</small>', 12,true) ?>

                        <!-- Import Logs - Widget -->
                        <?php factory::widget('import_logs', 'Import <small>logs</small>', 12,true) ?>

                    </div>
                </div>
                <?php include('includes/footer.php'); ?>
            </div>
        </div>
        <?php include('includes/script.php'); ?>
        <script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script>
            $(document).ready( function () {

                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                });

            });
        </script>

        <?php include('includes/script-bottom.php'); ?>
    </body>
    </html>