<?php
header('Content-Type: text/plain');
echo "Deployment Triggered...\n";

function run($cmd) {
    echo "Running: $cmd\n";
    $output = [];
    $return_var = 0;
    exec($cmd . ' 2>&1', $output, $return_var);
    echo implode("\n", $output) . "\n";
    echo "Return code: $return_var\n\n";
    return $return_var;
}

chdir('..'); // Go to project root from public/
run('git add .');
run('git commit -m "Deployment via browser trigger"');
run('git push live main');
run('ssh -p 2222 dcbf265@209.182.203.135 "cd ~/partner.shopswallet.com && php artisan optimize:clear"');

echo "Finished.";
