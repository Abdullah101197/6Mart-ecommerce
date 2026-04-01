
<?php
    $submitButtonText = $submitButtonText ?? 'Save Information';
    $resetButtonText = $resetButtonText ?? 'Reset';
?>
<div class="mt-0 footer-sticky">
    <div class="container-fluid">
        <div class="btn--container justify-content-end py-3">
            <button type="reset" class="btn btn--reset min-w-120px"><?php echo e($resetButtonText); ?></button>
            <button type="submit" id="submit"
                class="btn btn--primary call-demo min-w-120px"><i class="tio-save">x</i>
                <?php echo e($submitButtonText); ?></button>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\partials\_floating-submit-button.blade.php ENDPATH**/ ?>