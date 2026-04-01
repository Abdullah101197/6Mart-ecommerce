<html>
   <head>
      <title>Show Payment Page</title>
   </head>
   <body>
      <center>
         <h1>Please do not refresh this page...</h1>
      </center>
      <form method="post" action="<?php echo e(Config::get('paytm_config.PAYTM_STATUS_QUERY_NEW_URL')); ?>?mid=<?php echo e(Config::get('paytm_config.PAYTM_MERCHANT_MID')); ?>&orderId=<?php echo e($ORDER_ID); ?>" name="paytm">
         <table border="1">
            <tbody>
               <input type="hidden" name="mid" value="<?php echo e(Config::get('paytm_config.PAYTM_MERCHANT_MID')); ?>">
               <input type="hidden" name="orderId" value="<?php echo e($ORDER_ID); ?>">
               <input type="hidden" name="txnToken" value="<?php echo e($txnToken); ?>">
            </tbody>
         </table>
         <script type="text/javascript"> document.paytm.submit(); </script>
      </form>
   </body>
</html>
<?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\payment-views\paytm.blade.php ENDPATH**/ ?>