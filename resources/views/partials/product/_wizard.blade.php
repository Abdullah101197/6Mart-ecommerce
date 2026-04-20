@php
  /**
   * Shared Product Wizard include (admin/vendor).
   *
 * This is a structural cleanup layer so admin/vendor wrappers share one entry point.
 * The actual wizard implementation lives in
 * `admin-views.product._wizard_impl`.
   *
   * Inputs:
   * - $npWizardContext: 'admin'|'vendor' (default: auto)
   * - $product: Item|null (optional, edit mode)
   */
  $npWizardContext = $npWizardContext ?? null;
  $product = $product ?? null;
@endphp

@include('admin-views.product._wizard_impl', [
  'npWizardContext' => $npWizardContext,
  'product' => $product,
])

