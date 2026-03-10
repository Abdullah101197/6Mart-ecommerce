<?php
$html = file_get_contents(__DIR__ . '/task2.html');
preg_match('/<style>(.*?)<\/style>/s', $html, $matches);
$css = $matches[1];

// Prefix all CSS rules
// Very basic prefixing for demonstration/quick use. For a real project, using LESS/SASS or a CSS parser is better.
// But we can just wrap the container in a web component or iframe. No, iframes are bad.
// Let's just create a modified version of the index.blade.php that uses the task2.html layout directly.
// Actually, since this is a Blade view extending layouts.admin.app, it inherits Bootstrap.
// The new design doesn't use Bootstrap grid (.col-md-6 etc.), it uses native CSS grid (.grid, .form-row).
// The existing partials DO use Bootstrap classes (.row, .col-lg-6, .form-group, .form-control).
// This is a major clash.
// If I put the existing partials into the new `.tab-panel`, they will look like the old design, but inside a new tab structure.
// Is that what the user wants?
// "We need to update the existing “Add New Product” form to match this new design... The new design should be integrated"
// It's probably best to manually apply the task2.html CSS classes to the inner form, or restructure the tabs to use Bootstrap.
// But the user specifically wants the *design* from task2.html.

// Let's dump the CSS into a separate file public/assets/admin/css/new-product.css
$css = preg_replace('/body\s*{[^}]+}/', '', $css);
$css = preg_replace('/\*.*\s*{[^}]+}/', '', $css);
$css = preg_replace('/\.main\s*{[^}]+}/', '', $css);
$css = preg_replace('/\.topbar[^}]*{[^}]+}/', '', $css);
$css = preg_replace('/\.sidebar[^}]*{[^}]+}/', '', $css);

$css = ".new-product-form {\n" . preg_replace('/^([a-zA-Z.\-\#])/m', '.new-product-form $1', $css) . "\n}";

file_put_contents(__DIR__ . '/public/assets/admin/css/new-product.css', $css);
echo "CSS created";
