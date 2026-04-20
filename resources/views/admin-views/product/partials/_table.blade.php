@include('partials.item._table_rows', [
    'items' => $items,
    'context' => 'admin',
    'showStoreColumn' => true,
    'showRecommendedToggle' => false,
])
<script src="{{asset('assets/admin')}}/js/view-pages/common.js"></script>
