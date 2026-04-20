@include('partials.item._table_rows', [
    'items' => $items,
    'context' => 'vendor',
    'showStoreColumn' => false,
    'showRecommendedToggle' => true,
])
    <script src="{{asset('assets/admin')}}/js/view-pages/common.js"></script>
