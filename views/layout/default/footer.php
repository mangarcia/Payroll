
        <script src="/public/js/vendor/jquery-3.3.1.min.js" style="opacity: 1;"></script>
        <script src="/public/js/vendor/bootstrap.bundle.min.js" style="opacity: 1;"></script>
        <script src="/public/js/vendor/bootstrap-notify.min.js" style="opacity: 1;"></script>
        <script src="/public/js/vendor/moment.min.js" style="opacity: 1;"></script>
        <script src="/public/js/vendor/Chart.bundle.min.js"></script>
        <script src="/public/js/vendor/datatables.min.js"></script>
        <script src="/public/js/vendor/chartjs-plugin-datalabels.js"></script>
        <script src="/public/js/vendor/jquery.smartWizard.min.js" style="opacity: 1;"></script>
        <script src="/public/js/vendor/jquery.validate/jquery.validate.min.js" style="opacity: 1;"></script>
        <script src="/public/js/vendor/jquery.validate/additional-methods.min.js" style="opacity: 1;"></script>
        <script src="/public/js/vendor/datatables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
        <script src="/public/js/vendor/select2.full.js"></script>
        <script src="/public/js/vendor/bootstrap-datepicker.js"></script>
        <script src="/public/js/vendor/fullcalendar.min.js" style="opacity: 1;"></script>
        <script src="/public/js/vendor/bootstrap-tagsinput.min.js" style="opacity: 1;"></script>
        <script src="/public/js/vendor/perfect-scrollbar.min.js" style="opacity: 1;"></script>
        <script src="/public/js/vendor/cropper.min.js" style="opacity: 1;"></script>
        <script src="/public/js/dore.script.js" style="opacity: 1;"></script>
        <script src="/public/js/scripts.js" style="opacity: 1;"></script>
        
        <script src="/public/js/api/Consts.js"></script>
        <script src="/public/js/api/AlertPop.js"></script>
        <script src="/public/js/api/Utils.js"></script>
        <script src="/public/js/api/FormValidator.js"></script>
        <script src="/public/js/api/WebClient.js"></script>
        <script src="<?php echo VIEWS_JS . "header.js"; ?>"></script>

        <script src="/public/js/firma/BigInt.js"></script>
        <script src="/public/js/firma/demoButtons_encryption.js"></script>
        <script src="/public/js/firma/q.js"></script>
        <script src="/public/js/firma/wgssStuSdk.js"></script>


        <?php foreach($this->_layoutParams["js"] as $js) { echo "<script src='{$js}'></script>"; } ?>
        
    </body>

</html>