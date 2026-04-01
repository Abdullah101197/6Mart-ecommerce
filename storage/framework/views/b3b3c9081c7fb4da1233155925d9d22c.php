<div class="bg--secondary rounded p-20 mb-20">
                        <div class="d-flex flex-column gap-lg-4 gap-3">
                            <div>
                                <span class="mb-2 d-block title-clr fw-normal"><?php echo e(translate('messages.duration')); ?></span>
                                <select name="date_range" id="date_range_type"
                                    class="custom-select custom-select-color border rounded w-100">
                                    <option value="all_time" <?php echo e(request()->input('date_range') == 'all_time' ? 'selected' : ''); ?>><?php echo e(translate('messages.all_time')); ?></option>
                                    <option value="this_week" <?php echo e(request()->input('date_range') == 'this_week' ? 'selected' : ''); ?>><?php echo e(translate('messages.this_week')); ?></option>
                                    <option value="this_month" <?php echo e(request()->input('date_range') == 'this_month' ? 'selected' : ''); ?>><?php echo e(translate('messages.this_month')); ?></option>
                                    <option value="this_year" <?php echo e(request()->input('date_range') == 'this_year' ? 'selected' : ''); ?>><?php echo e(translate('messages.this_year')); ?></option>
                                    <option value="custom" <?php echo e(request()->input('date_range') == 'custom' ? 'selected' : ''); ?>>
                                        <?php echo e(translate('messages.custom')); ?>

                                    </option>
                                </select>
                            </div>
                            <div id="date_range" class="<?php echo e(request()->input('date_range') == 'custom' ? '' : 'd-none'); ?>">
                                <label class="form-label"><?php echo e(translate('messages.start_date')); ?></label>
                                <div class="position-relative">
                                    <i class="tio-calendar-month icon-absolute-on-right"></i>
                                    <input type="text" name="dates" value="<?php echo e($date); ?>"
                                        class="form-control h-45 position-relative bg-white"
                                        placeholder="<?php echo e(translate('messages.select_date')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

<?php $__env->startPush('script_2'); ?>
    <script>
        $(document).ready(function () {
            var dateString = '<?php echo e($date); ?>';
            if (dateString) {
                var dates = dateString.split(' - ');
                if (dates.length === 2) {
                    var start = moment(dates[0], 'MM/DD/YYYY');
                    var end = moment(dates[1], 'MM/DD/YYYY');

                    var picker = $('input[name="dates"]').data('daterangepicker');
                    if (picker) {
                        picker.setStartDate(start);
                        picker.setEndDate(end);
                    }
                }
            }

            if ($('#date_range_type').val() !== 'custom') {
                $('input[name="dates"]').prop('disabled', true);
            }

            $('#date_range_type').on('change', function () {
                if (this.value === 'custom') {
                    $('input[name="dates"]').prop('disabled', false);
                } else {
                    $('input[name="dates"]').prop('disabled', true);
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\partials\_date-range.blade.php ENDPATH**/ ?>