<!DOCTYPE html>
<?php
    $lang = \App\CentralLogics\Helpers::system_default_language();
    $site_direction = \App\CentralLogics\Helpers::system_default_direction();
?>
<html lang="<?php echo e($lang); ?>" class="<?php echo e($site_direction === 'rtl'?'active':''); ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(translate('Delivery Man Earning Report Invoice')); ?></title>
     <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;1,400&display=swap');

        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            font-size: 13px;
            line-height: 21px;
            color: #303030;
            background-color: #f5f5f5f5;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        h1,h2,h3,h4,h5,h6 {
            color: #303030;
            margin: 0;
        }
        span{
           color: #303030B2;
           font-size: 9px;
           line-height: 12px;
           display: inline-block;
        }
        * {
            box-sizing: border-box
        }
        img {
            max-width: 100%;
        }
        .mb-1 {
            margin-bottom: 5px;
        }
        .mb-2 {
            margin-bottom: 10px;
        }
        .mb-3 {
            margin-bottom: 15px;
        }
        .mb-4 {
            margin-bottom: 20px;
        }
        .mb-5 {
            margin-bottom: 25px;
        }
        .border-dashed-top {
            border-top: 1px solid #E6E7EC;
        }
        .border-dashed-bottom {
            border-top: 1px solid #E6E7EC;
        }
        .footer-bg{
            background-color: #FAFAFA;
        }
        .d-block {
            display: block;
        }
        div {
            display: block;
        }
        .text-center {
            text-align: center;
        }
        .text-base {
            color: #006161;
            font-weight: 700
        }
        .secondary-clr{
            color: rgba(48, 48, 48, 0.7);;
        }
        .text-clr{
            color: #303030;
        }
        .font-medium {
            font-family: 500;
        }
        .font-bold {
            font-family: 700;
        }
        a {
            text-decoration: none;
        }
        .fs-20{
            font-size: 20px;
        }
        .fs-10{
            font-size: 10px;
        }
        .p-10 {
            padding: 10px;
        }
        .py-6{
            padding: 6px 0;
        }
        .mt-0{
            margin-top: 0;
        }
        .mb-0{
            margin-bottom: 0;
        }
        .w-100 {
            width: 100%;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }

        /*Logo Header*/
        .invoice-body{
            max-width: 595px;
            width: 100%;
            margin: 20px auto;
            background-color: #fff;
        }
        .invoice-space{
            padding: 20px 20px 0;
        }
        .logo-header{
            margin-bottom: 15px;
        }
        .logo-header h3{
            font-size: 20px;
        }
        .logo{
            max-width: 40px;
            padding-bottom: 12px;
        }
        /*Header Info*/
        .header-information{
            border-bottom: 1px dashed #E6E7EC;
            border-top: 1px dashed #E6E7EC;
            padding: 15px 0;
        }
        .header-information .header-info-item{
            margin-bottom: 8px;
        }
        .header-info-right{
            margin-left: 50px;
        }
        .header-information .name{
            width: 90px;
            color: #212B36;
            font-size: 10px;
            font-weight: 500;
        }
        .header-information .datas{
            font-size: 10px;
            color: #6D6F73;
            line-height: 14px;
        }
        /*Main Table*/
        .main-table{
            margin-top: 20px;
            border-spacing: 0;
            margin-bottom: 40px;
        }
        .main-table thead tr th{
            background-color: #FAFAFA !important;
            padding: 8px 6px;
        }
        .main-table tbody tr td{
            padding: 6px;
        }
        .main-table td table {
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            width: auto !important;
        }

        .main-table td table td {
            padding: 0 !important;
            margin: 0 !important;
            height: auto !important;
            line-height: 12px !important;
            font-size: 10px !important;
            border: none !important;
        }
        .main-table td table span {
            margin: 0 !important;
            padding: 0 !important;
            line-height: 12px !important;
            display: inline-block !important;
        }


        /*Footer*/
        .invoice-footer{
            background-color: #FAFAFA;
            padding: 12px 32px;
            margin-top: 36px;
        }
        .invoice-footer span{
            color: #212B36;
            font-size: 10px;
        }
        .thanks-service{
            border-bottom: 1px dashed #E6E7EC;
            border-top: 1px dashed #E6E7EC;
        }

        .p-0{
            padding: 0 !important;
        }

        .w-50px {
            width: 50px;
        }

        .d-inline-block {
            display: inline-block;
        }

        .text-nowrap {
            white-space: nowrap;
        }


    </style>
</head>
<body>

<div class="invoice-body">
    <div class="invoice-space">
        <table class=" table w-100">
            <tr>
                <td>
                    <h3 class="fs-20 mb-0"><?php echo e(translate('Earning Statement')); ?></h3>
                </td>
                <td class="text-right">
                    <img height="30px" src="<?php echo e(\App\CentralLogics\Helpers::get_full_url('business', $logo?->value?? '', $logo?->storage[0]?->value ?? 'public','favicon')); ?>" alt="<?php echo e(translate('logo')); ?>" class="logo">
                </td>
            </tr>
        </table>
        <table class="table w-100 header-information">
            <tr>
                <td>
                    <table class="fs-10">
                        <tr>
                            <td>
                                <span class="name"><strong><?php echo e(translate('Name')); ?></strong></span>
                            </td>
                            <td>:</td>
                            <td>
                                <span class="datas"><?php echo e($dm->full_name); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="name"><strong><?php echo e(translate('Phone Number')); ?></strong></span>
                            </td>
                            <td>:</td>
                            <td>
                                <span class="datas"><?php echo e($dm->phone); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="name"><strong><?php echo e(translate('Deliveryman Type')); ?></strong></span>
                            </td>
                            <td>:</td>
                            <td>
                                <span class="datas"><?php if($dm->earning): ?> <?php echo e(translate('Freelance')); ?> <?php else: ?> <?php echo e(translate('Salary Based')); ?> <?php endif; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="name"><strong><?php echo e(translate('Download Date')); ?></strong></span>
                            </td>
                            <td>:</td>
                            <td>
                                <span class="datas"><?php echo e(\Carbon\Carbon::parse(now())->format('d-M-Y')); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="name"><strong><?php echo e(translate('Statement Period')); ?></strong></span>
                            </td>
                            <td>:</td>
                            <td>
                                <span class="datas">
                                <?php if($startDate && $endDate): ?>
                                    <?php echo e($startDate); ?> <?php echo e(translate('to')); ?> <?php echo e($endDate); ?>

                                <?php elseif($startDate): ?>
                                    <?php echo e($startDate); ?>

                                <?php elseif($endDate): ?>
                                    <?php echo e($endDate); ?>

                                <?php else: ?>
                                    <?php echo e(translate('All Time')); ?>

                                <?php endif; ?>
                            </span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <div class="text-left header-info-right header-info-inner">
                        <h3 class="header-info-item"><?php echo e(translate('Summery')); ?></h3>
                        <table class="fs-10">
                            <tr>
                                <td>
                                    <span class="name"><strong><?php echo e(translate('Total Earning')); ?></strong></span>
                                </td>
                                <td>:</td>
                                <td>
                                    <span class="datas"><?php echo e(\App\CentralLogics\Helpers::format_currency($earnings->sum('original_delivery_charge') + $earnings->sum('dm_tips'))); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="name"><strong><?php echo e(translate('Delivery Fee')); ?></strong></span>
                                </td>
                                <td>:</td>
                                <td>
                                    <span class="datas"><?php echo e(\App\CentralLogics\Helpers::format_currency($earnings->sum('original_delivery_charge'))); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="name"><strong><?php echo e(translate('Delivery Tips')); ?></strong></span>
                                </td>
                                <td>:</td>
                                <td>
                                    <span class="datas"><?php echo e(\App\CentralLogics\Helpers::format_currency($earnings->sum('dm_tips'))); ?></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
        <table dir="<?php echo e($site_direction); ?>" class="table w-100 main-table fs-10 text-clr">
            <thead class="text-nowrap">
            <tr>
                <th class="fs-10 text-left"><?php echo e(translate('Date')); ?></th>
                <th class="fs-10 text-left"><?php echo e(translate('Paid By')); ?></th>
                <th class="fs-10 text-left"><?php echo e(translate('Order & Transection')); ?></th>
                <th class="fs-10 text-right"><?php echo e(translate('Delivery Fee')); ?></th>
                <th class="fs-10 text-right"><?php echo e(translate('Delivery Tips')); ?></th>
                <th class="fs-10 text-right"><?php echo e(translate('Total')); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $earnings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $earning): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="text-left">
                    <span><?php echo e(\Carbon\Carbon::parse($earning->delivered)->format('d-M-Y')); ?></span>
                </td>
                <td class="text-left">
                    <span><?php echo e(translate($earning?->order?->payment_method)); ?></span>
                </td>
                <td class="text-left">
                    <table class="">
                        <tr>
                            <td style="color: rgba(48, 48, 48, 0.7);"><?php echo e(translate('Order ID')); ?></td>
                            <td class="text-clr">: <?php echo e($earning->order_id); ?></td>
                        </tr>
                        <tr>
                            
                        </tr>
                    </table>
                </td>
                <td class="text-right">
                    <span><?php echo e(\App\CentralLogics\Helpers::format_currency($earning->original_delivery_charge)); ?></span>
                </td>
                <td class="text-right">
                    <span><?php echo e(\App\CentralLogics\Helpers::format_currency($earning->dm_tips)); ?></span>
                </td>

                <td class="text-right">
                    <span><?php echo e(\App\CentralLogics\Helpers::format_currency( $earning->original_delivery_charge + $earning->dm_tips)); ?></span>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <p class="thanks-service text-center fs-10 mt-0 mb-0 py-6 text-clr"><?php echo e(translate('Thanks for using our service.')); ?></p>
    </div>
    <table class="table w-100 invoice-footer">
        <tr>
            <td class="text-left">
                <span><?php echo e(url('/')); ?></span>
            </td>
            <td class="text-center">
                <span><?php echo e($businessData['phone']); ?></span>
            </td>
            <td class="text-right">
                <span><?php echo e($businessData['email_address']); ?></span>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
<?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\invoice-pdf\deliveryman-report-invoice.blade.php ENDPATH**/ ?>