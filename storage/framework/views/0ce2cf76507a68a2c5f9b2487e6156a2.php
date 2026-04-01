

<?php $__env->startSection('title', translate('Product_Gallery')); ?>

<?php $__env->startPush('css_or_js'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <style>
            :root {
                --navy: #0d1b2a;
                --slate: #415a77;
                --text: #1b263b;
                --muted: #778da9;
                --bg: #f8fafc;
                --white: #ffffff;
                --border: #e2e8f0;
                --shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                --gallery-primary: #00605a;
                --gallery-primary-dark: #004d48;
                --gallery-primary-light: rgba(0, 96, 90, 0.1);
                --danger: #e2001a;
                --danger-light: #fff0f2;
                --success: #22c55e;
                --warn: #f59e0b;
                --purple: #7c3aed;
                --radius: 12px;
            }

            .gallery-wrapper {
                font-family: 'Sora', sans-serif;
                color: var(--text);
            }

            /* Topbar & Page Header */
            .topbar {
                background: var(--white);
                border-bottom: 1px solid var(--border);
                padding: 14px 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin: -1.25rem -1.25rem 20px;
            }

            .breadcrumb {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 13px;
                color: var(--muted)
            }

            .breadcrumb span {
                color: var(--text);
                font-weight: 500
            }

            .topbar-actions {
                display: flex;
                align-items: center;
                gap: 10px
            }

            .btn-icon {
                width: 36px;
                height: 36px;
                border-radius: 8px;
                border: 1px solid var(--border);
                background: white;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 15px;
                transition: all 0.15s
            }

            .btn-icon:hover {
                background: var(--bg)
            }

            .page-header {
                margin-bottom: 20px;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 16px;
                border-bottom: none
            }

            .page-title {
                font-size: 21px;
                font-weight: 700;
                color: var(--navy)
            }

            .page-sub {
                color: var(--muted);
                font-size: 13px;
                margin-top: 3px
            }

            .btn-row {
                display: flex;
                gap: 8px;
                align-items: center;
                flex-wrap: wrap
            }

            .btn {
                display: inline-flex;
                align-items: center;
                gap: 7px;
                padding: 9px 18px;
                border-radius: 8px;
                font-family: 'Sora', sans-serif;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
                border: none;
                transition: all 0.18s;
                white-space: nowrap;
                text-decoration: none !important;
            }

            .btn-ghost {
                background: var(--white);
                border: 1.5px solid #cbd5e1;
                color: #1e293b;
            }

            .btn-ghost:hover {
                border-color: #94a3b8;
                background: #f8fafc;
            }

            .btn-primary {
                background: var(--gallery-primary);
                color: var(--white);
                padding: 10px 22px;
                border-radius: 10px;
                box-shadow: 0 4px 12px rgba(0, 96, 90, 0.2);
            }

            .btn-primary:hover {
                background: var(--gallery-primary-dark);
                transform: translateY(-1px);
                box-shadow: 0 6px 16px rgba(0, 96, 90, 0.3);
            }

            /* Stats Bar */
            .stats-bar {
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                gap: 12px;
                margin-bottom: 20px;
            }

            .stat-card {
                background: var(--white);
                border: 1px solid var(--border);
                border-radius: var(--radius);
                padding: 14px 18px;
                display: flex;
                align-items: center;
                gap: 12px;
                box-shadow: var(--shadow);
                transition: all 0.18s;
                cursor: pointer;
            }

            .stat-card:hover {
                border-color: var(--primary);
                box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
            }

            .stat-card.active-filter {
                border-color: var(--primary);
                background: var(--gallery-primary-light);
            }

            .stat-icon {
                font-size: 20px;
                width: 38px;
                height: 38px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .si-total {
                background: #e0f2fe;
            }

            .si-active {
                background: #dcfce7;
            }

            .si-draft {
                background: #fef3c7;
            }

            /* FILTER CHIPS */
            .active-filters {
                display: flex;
                gap: 6px;
                flex-wrap: wrap;
                margin-top: 10px;
                padding-top: 10px;
                border-top: 1px solid var(--border);
            }

            .filter-chip {
                display: flex;
                align-items: center;
                gap: 5px;
                padding: 3px 10px;
                border-radius: 20px;
                background: var(--gallery-primary-light);
                color: var(--gallery-primary);
                font-size: 11.5px;
                font-weight: 500;
                cursor: pointer;
                border: 1px solid var(--gallery-primary);
                transition: all 0.15s;
            }

            .filter-chip .rm {
                font-size: 13px;
                line-height: 1;
                font-weight: 700;
            }

            .filter-chip:hover {
                opacity: 0.8;
            }

            /* TOAST */
            .toast {
                position: fixed;
                bottom: 28px;
                right: 28px;
                background: var(--navy);
                color: white;
                border-radius: 10px;
                padding: 12px 18px;
                font-size: 13px;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 10px;
                box-shadow: 0 8px 24px rgba(13, 27, 42, 0.25);
                z-index: 9999;
                transform: translateY(20px);
                opacity: 0;
                transition: all 0.25s;
                pointer-events: none;
            }

            .toast.show {
                transform: translateY(0);
                opacity: 1;
            }

            .toast-icon {
                font-size: 16px;
            }

            /* OOS BADGE */
            .b-oos {
                background: #fee2e2;
                color: #991b1b;
            }

            /* VAT COLORS */
            .vat-zero {
                color: var(--muted);
                opacity: 0.8;
            }

            .vat-active {
                color: var(--success);
                font-weight: 700;
            }

            /* margin preview (inline edit modal) */
            .ie-preview {
                background: var(--bg);
                border-radius: 8px;
                padding: 12px;
                margin-top: 8px;
                display: flex;
                gap: 20px;
            }

            .ie-preview-lbl {
                font-size: 10px;
                font-weight: 700;
                color: var(--muted);
                letter-spacing: 0.5px;
                text-transform: uppercase;
            }

            .ie-preview-val {
                font-size: 17px;
                font-weight: 700;
                color: var(--navy);
                margin-top: 2px;
            }

            /* si-expiry icon */
            .si-expiry {
                background: #fde8ff;
            }

            .stat-val {
                font-size: 20px;
                font-weight: 700;
                color: var(--navy);
                line-height: 1;
            }

            .stat-lbl {
                font-size: 11px;
                color: var(--muted);
                margin-top: 2px;
            }

            /* Filter Bar */
            .filter-bar {
                background: var(--white);
                border: 1px solid var(--border);
                border-radius: var(--radius);
                padding: 14px 18px;
                margin-bottom: 16px;
                box-shadow: var(--shadow);
            }

            .filter-row {
                display: flex;
                gap: 10px;
                align-items: center;
                flex-wrap: wrap;
            }

            .filter-group {
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .filter-label {
                font-size: 11.5px;
                font-weight: 600;
                color: var(--muted);
                white-space: nowrap;
            }

            .search-wrap {
                flex: 1;
                min-width: 200px;
                position: relative;
            }

            .search-wrap input {
                width: 100%;
                padding: 8px 12px 8px 36px;
                border: 1.5px solid var(--border);
                border-radius: 8px;
                font-size: 13px;
                color: var(--text);
                outline: none;
                transition: border 0.18s;
            }

            .search-wrap input:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
            }

            .search-icon {
                position: absolute;
                left: 10px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 14px;
                pointer-events: none;
            }

            .filter-sel,
            .price-input {
                padding: 8px 12px;
                border: 1.5px solid var(--border);
                border-radius: 8px;
                font-size: 12.5px;
                color: var(--text);
                outline: none;
                transition: border 0.18s;
            }

            .price-input {
                width: 80px;
            }

            /* Bulk Bar */
            .bulk-bar {
                background: var(--navy);
                border-radius: var(--radius);
                padding: 12px 18px;
                margin-bottom: 14px;
                display: none;
                align-items: center;
                gap: 14px;
                animation: slideDown 0.2s ease;
            }

            .bulk-bar.visible {
                display: flex;
            }

            .bulk-count {
                color: white;
                font-size: 13px;
                font-weight: 600;
            }

            .bulk-btn {
                padding: 7px 14px;
                border-radius: 7px;
                font-family: 'Sora', sans-serif;
                font-size: 12px;
                font-weight: 600;
                cursor: pointer;
                border: none;
                transition: all 0.15s;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .bb-activate {
                background: #dcfce7;
                color: #15803d;
            }

            .bb-deactivate {
                background: #fef3c7;
                color: #92400e;
            }

            .bb-delete {
                background: #fee2e2;
                color: #991b1b;
            }

            .bb-discount {
                background: #e0f2fe;
                color: #0369a1;
            }

            .bb-discount:hover {
                background: #bae6fd;
            }

            .discount-preview {
                background: #f0f9ff;
                border: 1.5px dashed #bae6fd;
                border-radius: 10px;
                padding: 14px 20px;
                margin-top: 16px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                animation: fadeIn 0.15s ease;
            }

            .dp-val {
                font-size: 18px;
                font-weight: 700;
                color: var(--navy);
            }

            .dp-lbl {
                font-size: 10px;
                color: var(--muted);
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-top: 2px;
            }

            .bulk-spacer {
                flex: 1;
            }

            .bulk-clear {
                color: rgba(255, 255, 255, 0.55);
                font-size: 12px;
                cursor: pointer;
                background: none;
                border: none;
            }

            /* Table Design */
            .table-wrap {
                background: var(--white);
                border: 1px solid var(--border);
                border-radius: var(--radius);
                box-shadow: var(--shadow);
                overflow: hidden;
            }

            .table-header {
                padding: 14px 18px;
                border-bottom: 1px solid var(--border);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            thead tr {
                background: var(--bg);
                border-bottom: 2px solid var(--border)
            }

            th {
                padding: 10px 14px;
                text-align: left;
                font-size: 10.5px;
                font-weight: 700;
                color: var(--muted);
                letter-spacing: 0.5px;
                text-transform: uppercase;
                white-space: nowrap;
                cursor: pointer;
                user-select: none;
            }

            th:hover {
                color: var(--text);
            }

            th .sort-icon {
                margin-left: 4px;
                opacity: 0.4;
                font-size: 10px;
            }

            th.sorted .sort-icon {
                opacity: 1;
                color: var(--gallery-primary);
            }

            th.th-check {
                width: 40px;
                cursor: default;
            }

            td {
                padding: 11px 14px;
                border-bottom: 1px solid var(--border);
                font-size: 13px;
                color: var(--text);
                vertical-align: middle;
            }

            tr:last-child td {
                border-bottom: none
            }

            tr:hover td {
                background: #fafbfd
            }

            tr.selected td {
                background: var(--gallery-primary-light);
            }

            tr.selected td:first-child {
                border-left: 3px solid var(--gallery-primary);
            }

            /* Product Cell */
            .prod-cell {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .prod-img {
                width: 44px;
                height: 44px;
                border-radius: 8px;
                border: 1.5px solid var(--border);
                overflow: hidden;
            }

            .prod-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .prod-name {
                font-weight: 600;
                font-size: 13px;
                color: var(--navy);
                line-height: 1.3;
                max-width: 200px;
            }

            .prod-sku {
                font-size: 11px;
                color: var(--muted);
                font-family: monospace;
                margin-top: 2px
            }

            .prod-barcode {
                font-size: 10.5px;
                color: var(--muted);
                font-family: monospace;
            }

            /* Badges & Pills */
            .badge {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                font-size: 11px;
                font-weight: 600;
                padding: 3px 9px;
                border-radius: 20px;
                border: none;
            }

            .b-active {
                background: #dcfce7;
                color: #15803d;
            }

            .b-draft {
                background: #fef3c7;
                color: #92400e;
            }

            .b-dot {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: currentColor;
                flex-shrink: 0;
            }

            .cat-crumb {
                font-size: 11.5px;
                color: var(--muted)
            }

            .cat-crumb span {
                color: var(--text);
                font-weight: 500
            }

            .margin-pill {
                font-size: 11.5px;
                font-weight: 600;
                padding: 2px 8px;
                border-radius: 20px;
                cursor: pointer;
                display: inline-block;
            }

            .mp-good {
                background: #dcfce7;
                color: #15803d;
            }

            .mp-ok {
                background: #fef3c7;
                color: #92400e;
            }

            .mp-bad {
                background: #fee2e2;
                color: #991b1b;
            }

            /* Inline Edit */
            .inline-val {
                display: flex;
                align-items: center;
                gap: 5px;
                cursor: pointer;
                border-radius: 6px;
                padding: 3px 6px;
                transition: all 0.15s;
                border: 1.5px solid transparent;
            }

            .inline-val:hover {
                border-color: var(--gallery-primary);
                background: var(--gallery-primary-light);
            }

            .inline-edit-icon {
                font-size: 10px;
                color: var(--gallery-primary);
                opacity: 0;
            }

            .inline-val:hover .inline-edit-icon {
                opacity: 1;
            }

            /* Row Actions */
            .row-actions {
                display: flex;
                gap: 4px;
                opacity: 0;
                transition: opacity 0.15s;
            }

            tr:hover .row-actions {
                opacity: 1;
            }

            .ra-btn {
                padding: 5px 9px;
                border-radius: 6px;
                font-size: 11.5px;
                font-weight: 600;
                border: 1.5px solid var(--border);
                background: white;
                cursor: pointer;
                font-family: 'Sora', sans-serif;
                text-decoration: none !important;
                color: var(--slate);
                transition: all 0.15s;
                display: inline-flex;
                align-items: center;
                gap: 3px;
            }

            .ra-btn:hover {
                border-color: var(--gallery-primary);
                color: var(--gallery-primary);
                background: var(--gallery-primary-light);
            }

            .ra-btn.del:hover {
                border-color: #ef4444;
                color: #ef4444;
                background: #fef2f2
            }

            /* Expiry */
            .expiry-ok {
                color: var(--success);
            }

            .expiry-warn {
                color: var(--warn);
            }

            .expiry-danger {
                color: var(--gallery-primary);
                font-weight: 700;
            }

            .expiry-na {
                color: var(--muted)
            }

            /* Stock Bar */
            .stock-cell {
                min-width: 100px
            }

            .stock-num {
                font-size: 13px;
                font-weight: 600;
                color: var(--text)
            }

            .stock-bar-bg {
                height: 4px;
                background: var(--border);
                border-radius: 2px;
                margin-top: 4px;
                overflow: hidden;
            }

            .stock-bar-fill {
                height: 100%;
                border-radius: 2px;
                transition: width 0.4s;
            }

            .sf-high {
                background: var(--success);
            }

            .sf-med {
                background: var(--warn);
            }

            .sf-low {
                background: var(--gallery-primary);
            }

            /* MODAL SYSTEM (Custom classes to avoid Bootstrap conflicts) */
            .g-modal-overlay {
                position: fixed;
                inset: 0;
                background: rgba(13, 27, 42, 0.4);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                backdrop-filter: blur(4px);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.2s;
            }

            .g-modal-overlay.open {
                opacity: 1;
                pointer-events: all;
            }

            .g-modal {
                background: var(--white);
                border-radius: 16px;
                box-shadow: 0 24px 60px rgba(13, 27, 42, 0.22);
                width: 460px;
                max-width: calc(100vw - 40px);
                transform: scale(0.95) translateY(8px);
                transition: transform 0.2s;
                overflow: hidden;
                display: flex;
                flex-direction: column;
            }

            .g-modal-overlay.open .g-modal {
                transform: scale(1) translateY(0);
            }

            .g-modal-header {
                padding: 18px 22px;
                border-bottom: 1px solid var(--border);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .g-modal-title {
                font-size: 15px;
                font-weight: 700;
                color: var(--navy);
            }

            .g-modal-close {
                width: 30px;
                height: 30px;
                border-radius: 6px;
                border: 1.5px solid var(--border);
                background: transparent;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
                color: var(--muted);
                transition: all 0.15s;
            }

            .g-modal-close:hover {
                background: var(--bg);
            }

            .g-modal-body {
                padding: 22px;
            }

            .g-modal-footer {
                padding: 14px 22px;
                border-top: 1px solid var(--border);
                display: flex;
                gap: 10px;
                justify-content: flex-end;
            }

            /* Header Branding Buttons (Screenshot Parity) */
            .topbar-btn {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 8px 16px;
                background: white;
                border: 1.5px solid #eef2f6;
                border-radius: 10px;
                font-size: 14px;
                font-weight: 500;
                color: #475569;
                cursor: pointer;
                transition: all 0.2s;
            }

            .topbar-btn:hover {
                background: #f8fafc;
                border-color: #cbd5e1;
            }

            .topbar-btn .icon-bg {
                width: 22px;
                height: 22px;
                background: #3b82f6;
                border-radius: 4px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
            }

            .topbar-btn-icon {
                width: 40px;
                height: 40px;
                background: white;
                border: 1.5px solid #eef2f6;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s;
            }

            .topbar-btn-icon:hover {
                background: #f8fafc;
                border-color: #cbd5e1;
            }

            .form-group {
                margin-bottom: 16px
            }

            .form-group:last-child {
                margin-bottom: 0
            }

            label.ml {
                display: block;
                font-size: 12px;
                font-weight: 600;
                color: var(--slate);
                margin-bottom: 5px
            }

            input.fi,
            select.fi {
                width: 100%;
                padding: 9px 13px;
                border: 1.5px solid var(--border);
                border-radius: 8px;
                font-family: 'Sora', sans-serif;
                font-size: 13px;
                color: var(--text);
                background: var(--white);
                transition: border 0.18s, box-shadow 0.18s;
                outline: none;
                appearance: none
            }

            input.fi:focus,
            select.fi:focus {
                border-color: var(--gallery-primary);
                box-shadow: 0 0 0 3px var(--gallery-primary-light);
            }

            select.fi {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%237a8fa6' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 12px center;
                padding-right: 34px
            }

            /* FILTER UTILS */
            .filter-divider {
                height: 24px;
                width: 1px;
                background: var(--border)
            }

            .btn-ghost {
                background: transparent;
                border: 1.5px solid var(--border);
                color: var(--slate);
                padding: 7px 14px;
                border-radius: 8px;
            }

            .btn-ghost:hover {
                border-color: var(--slate);
                background: var(--bg)
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-8px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Select2 Integration */
            .select2-container--default .select2-selection--single {
                height: 38px;
                border: 1.5px solid var(--border);
                border-radius: 8px;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 35px;
                font-size: 13px;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 35px;
            }
        </style>

        <div class="gallery-wrapper">
            <header class="topbar" style="margin-bottom: 25px;">
                <div class="breadcrumb" style="font-size: 14px; color: #64748b;">Products › <span
                        style="color: #1e293b; font-weight: 600;">All Products</span></div>
                <div class="topbar-actions" style="display: flex; gap: 12px; align-items: center;">
                    <button class="topbar-btn" onclick="$('#importModal').addClass('open')">
                        <span class="icon-bg">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 19V5M5 12l7-7 7 7" />
                            </svg>
                        </span>
                        Import
                    </button>
                    <button class="topbar-btn" onclick="exportData()">
                        <span class="icon-bg">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14M5 12l7 7 7-7" />
                            </svg>
                        </span>
                        Export
                    </button>
                    <button class="topbar-btn-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" />
                        </svg>
                    </button>
                    <button class="topbar-btn-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3M12 17H12.01" />
                        </svg>
                    </button>
                </div>
            </header>

            <div class="page-header mt-3">
                <div>
                    <div class="page-title">Product Catalogue</div>
                    <div class="page-sub">Manage inventory, pricing, and visibility across all channels</div>
                </div>
                <div class="btn-row">
                    <button class="btn btn-ghost" onclick="$('#importModal').addClass('open')">📋 Bulk Template</button>
                    <a href="<?php echo e(route('admin.item.add-new', ['module_id' => Config::get('module.current_module_id')])); ?>"
                        class="btn btn-primary">+ Add New Product</a>
                </div>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="stats-bar">
            <div class="stat-card active-filter" onclick="filterByStatus('all', this)">
                <div class="stat-icon si-total">📦</div>
                <div>
                    <div class="stat-val" id="sv-total"><?php echo e($total_count); ?></div>
                    <div class="stat-lbl">Total Products</div>
                </div>
            </div>
            <div class="stat-card" onclick="filterByStatus('active', this)">
                <div class="stat-icon si-active">✅</div>
                <div>
                    <div class="stat-val" id="sv-active"><?php echo e($active_count); ?></div>
                    <div class="stat-lbl">Active</div>
                </div>
            </div>
            <div class="stat-card" onclick="filterByStatus('draft', this)">
                <div class="stat-icon si-draft">📝</div>
                <div>
                    <div class="stat-val" id="sv-draft"><?php echo e($draft_count); ?></div>
                    <div class="stat-lbl">Draft</div>
                </div>
            </div>
            <div class="stat-card" onclick="filterByStatus('oos', this)">
                <div class="stat-icon si-oos">⚠️</div>
                <div>
                    <div class="stat-val" id="sv-oos"><?php echo e($oos_count); ?></div>
                    <div class="stat-lbl">Out of Stock</div>
                </div>
            </div>
            <div class="stat-card" onclick="filterByStatus('expiry', this)">
                <div class="stat-icon si-expiry">⏳</div>
                <div>
                    <div class="stat-val" id="sv-expiry"><?php echo e($expiry_count); ?></div>
                    <div class="stat-lbl">Near Expiry</div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <form id="gallery-filter-form">
                <div class="filter-row">
                    <div class="search-wrap">
                        <span class="search-icon">🔍</span>
                        <input type="text" name="search" id="gallery-search" value="<?php echo e(request()?->search ?? ''); ?>"
                            placeholder="Search product name, SKU, or EAN..." oninput="debouncedSearch()">
                    </div>
                    <div class="filter-group">
                        <span class="filter-label"><?php echo e(translate('messages.Category')); ?></span>
                        <select name="category_id" id="category_id" class="filter-sel" style="min-width: 150px;"
                            onchange="applyFilters()">
                            <option value="all"><?php echo e(translate('messages.all_categories')); ?></option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cat->id); ?>" <?php echo e((isset($category) && $category->id == $cat->id) || request('category_id') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <span class="filter-label"><?php echo e(translate('messages.Brand')); ?></span>
                        <select name="brand_id" id="brand_id" class="filter-sel" style="min-width: 150px;"
                            onchange="applyFilters()">
                            <option value="all"><?php echo e(translate('messages.all_brands')); ?></option>
                            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($brand->id); ?>" <?php echo e(request('brand_id') == $brand->id ? 'selected' : ''); ?>>
                                    <?php echo e($brand->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <span class="filter-label"><?php echo e(translate('messages.Status')); ?></span>
                        <select name="status_filter" id="filter-status-select" class="filter-sel" style="min-width: 150px;"
                            onchange="applyFilters()">
                            <option value="all"><?php echo e(translate('messages.all_status')); ?></option>
                            <option value="active" <?php echo e(request('status_filter') == 'active' ? 'selected' : ''); ?>>
                                <?php echo e(translate('messages.Active')); ?>

                            </option>
                            <option value="draft" <?php echo e(request('status_filter') == 'draft' ? 'selected' : ''); ?>>
                                <?php echo e(translate('messages.Draft')); ?>

                            </option>
                            <option value="oos" <?php echo e(request('status_filter') == 'oos' ? 'selected' : ''); ?>>Out of Stock
                            </option>
                            <option value="expiry" <?php echo e(request('status_filter') == 'expiry' ? 'selected' : ''); ?>>Near Expiry
                            </option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <span class="filter-label"><?php echo e(translate('messages.VAT')); ?></span>
                        <select name="vat" class="filter-sel" style="min-width: 150px;" onchange="applyFilters()">
                            <option value="all"><?php echo e(translate('Any VAT')); ?></option>
                            <option value="0" <?php echo e(request('vat') == '0' ? 'selected' : ''); ?>>0% (Exempt)</option>
                            <option value="5" <?php echo e(request('vat') == '5' ? 'selected' : ''); ?>>5% Standard</option>
                        </select>
                    </div>
                    <div class="filter-divider"></div>
                    <div class="filter-group">
                        <span class="filter-label"><?php echo e(translate('Price')); ?></span>
                        <div class="price-range-wrap">
                            <input type="number" name="min_price" class="price-input" placeholder="Min"
                                oninput="debouncedSearch()">
                            <span style="color:var(--muted);font-size:12px">–</span>
                            <input type="number" name="max_price" class="price-input" placeholder="Max"
                                oninput="debouncedSearch()">
                            <span style="color:var(--muted);font-size:11px">QAR</span>
                        </div>
                    </div>
                    <input type="hidden" id="filter-status" value="all">
                    <input type="hidden" name="sort_by" id="filter-sort" value="name_asc">
                    <input type="hidden" name="limit" id="filter-limit" value="20">
                    <input type="hidden" name="product_gallery" value="1">
                    <button type="button" class="btn btn-ghost" onclick="resetFilters()"
                        style="font-size:12px;padding:7px 12px">✕ Clear</button>
                </div>
            </form>
            <!-- Active Filter Chips -->
            <div class="active-filters" id="activeFiltersRow" style="display:none;"></div>
        </div>

        <!-- Bulk Action Bar -->
        <div class="bulk-bar" id="bulkBar">
            <span class="bulk-count" id="bulkCount">0 selected</span>
            <div style="width:1px; height:20px; background:rgba(255,255,255,0.15)"></div>
            <button class="bulk-btn bb-activate" onclick="bulkRequest('active')">✅ Activate</button>
            <button class="bulk-btn bb-deactivate" onclick="bulkRequest('draft')">⏸ Deactivate</button>
            <button class="bulk-btn bb-delete" onclick="bulkRequest('delete')">🗑️ Delete</button>
            <button class="bulk-btn bb-discount" onclick="openBulkDiscount()">🏷️ Discount</button>
            <div class="bulk-spacer"></div>
            <button class="bulk-clear" onclick="clearSelection()">✕ Clear Selection</button>
        </div>

        <!-- Table -->
        <div class="table-wrap">
            <div class="table-header">
                <div style="font-size:13px; color:var(--muted)" class="table-count">
                    Showing <strong id="showingCount"><?php echo e($items->count()); ?></strong> of <strong
                        id="totalCount"><?php echo e($total_count); ?></strong> products
                </div>
                <div style="display:flex;align-items:center;gap:12px">
                    <select class="filter-sel" onchange="$('#filter-limit').val(this.value); applyFilters()"
                        style="font-size:12px;padding:6px 30px 6px 10px">
                        <option value="20" <?php echo e(request('limit') == 20 ? 'selected' : ''); ?>>20 / page</option>
                        <option value="50" <?php echo e(request('limit') == 50 ? 'selected' : ''); ?>>50 / page</option>
                        <option value="100" <?php echo e(request('limit') == 100 ? 'selected' : ''); ?>>100 / page</option>
                    </select>
                    <select class="filter-sel" onchange="$('#filter-sort').val(this.value); applyFilters()"
                        style="font-size:12px;padding:6px 30px 6px 10px">
                        <option value="name_asc" <?php echo e(request('sort_by') == 'name_asc' ? 'selected' : ''); ?>>Name: A-Z
                        </option>
                        <option value="name_desc" <?php echo e(request('sort_by') == 'name_desc' ? 'selected' : ''); ?>>Name: Z-A
                        </option>
                        <option value="price_asc" <?php echo e(request('sort_by') == 'price_asc' ? 'selected' : ''); ?>>Price: Low to
                            High</option>
                        <option value="price_desc" <?php echo e(request('sort_by') == 'price_desc' ? 'selected' : ''); ?>>Price: High
                            to Low</option>
                        <option value="stock_desc" <?php echo e(request('sort_by') == 'stock_desc' ? 'selected' : ''); ?>>Stock: High
                            to Low</option>
                        <option value="margin" <?php echo e(request('sort_by') == 'margin' ? 'selected' : ''); ?>>Margin: High to Low
                        </option>
                        <option value="expiry" <?php echo e(request('sort_by') == 'expiry' ? 'selected' : ''); ?>>Expiry: Soonest
                            first</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table id="product-table">
                    <thead>
                        <tr>
                            <th class="th-check"><input type="checkbox" class="row-check" id="selectAll"
                                    onchange="toggleSelectAll(this)"></th>
                            <th tabindex="0" onclick="$('#filter-sort').val('name_asc'); applyFilters()">Product <span
                                    class="sort-icon">↕</span></th>
                            <th tabindex="0" onclick="$('#filter-sort').val('sku'); applyFilters()">SKU / EAN <span
                                    class="sort-icon">↕</span></th>
                            <th tabindex="0" onclick="$('#filter-sort').val('cat'); applyFilters()">Category <span
                                    class="sort-icon">↕</span></th>
                            <th tabindex="0" onclick="$('#filter-sort').val('price_asc'); applyFilters()">Sell Price
                                <span class="sort-icon">↕</span>
                            </th>
                            <th
                                onclick="$('#filter-sort').val($('#filter-sort').val()=='margin_asc'?'margin_desc':'margin_asc');applyFilters()">
                                Margin <span class="sort-icon">↕</span> <span
                                    title="<?php echo e(translate('Margin is calculated as ((Price - Cost) / Price) * 100')); ?>"
                                    style="cursor:help; font-size:10px;">❓</span></th>
                            <th
                                onclick="$('#filter-sort').val($('#filter-sort').val()=='stock_asc'?'stock_desc':'stock_asc');applyFilters()">
                                Stock <span class="sort-icon">↕</span></th>
                            <th
                                onclick="$('#filter-sort').val($('#filter-sort').val()=='expiry_asc'?'expiry_desc':'expiry_asc');applyFilters()">
                                Min. Shelf Life <span class="sort-icon">↕</span> <span
                                    title="<?php echo e(translate('Days remaining until product expiry')); ?>"
                                    style="cursor:help; font-size:10px;">❓</span></th>
                            <th>VAT</th>
                            <th tabindex="0" onclick="$('#filter-sort').val('status'); applyFilters()">Status <span
                                    class="sort-icon">↕</span></th>
                            <th style="width:120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="set-rows">
                        <?php echo $__env->make('admin-views.product.partials._gallery', ['items' => $items], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer" id="pagination-links">
                <?php echo $items->links(); ?>

            </div>
        </div>
    </div>
    </div>

    <!-- Quick Edit Modal -->
    <div class="g-modal-overlay" id="quickEditModal">
        <div class="g-modal">
            <div class="g-modal-header">
                <div class="g-modal-title" id="quickEditTitle">✏️ Edit Field</div>
                <button class="g-modal-close" onclick="$('#quickEditModal').removeClass('open')">✕</button>
            </div>
            <form id="quickEditForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" id="edit-item-id">
                <input type="hidden" name="field" id="edit-field-name">
                <div class="g-modal-body" id="quickEditBody">
                    <!-- Dynamic content -->
                </div>
                <div class="g-modal-footer">
                    <button type="button" class="btn btn-ghost"
                        onclick="$('#quickEditModal').removeClass('open')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Discount Modal -->
    <div class="g-modal-overlay" id="discountModal">
        <div class="g-modal">
            <div class="g-modal-header">
                <div class="g-modal-title">🏷️ Apply Bulk Discount</div>
                <button class="g-modal-close" onclick="$('#discountModal').removeClass('open')">✕</button>
            </div>
            <div class="g-modal-body">
                <p style="font-size:13px;color:var(--muted);margin-bottom:16px">Applying discount to <strong
                        id="discountItemCount" style="color:var(--navy)">0 products</strong>.</p>
                <div class="form-group">
                    <label class="ml">Discount Type</label>
                    <select class="fi" id="discountType" onchange="updateDiscountPreview()">
                        <option value="percent">Percentage Off (%)</option>
                        <option value="amount">Fixed Amount Off (<?php echo e(\App\CentralLogics\Helpers::currency_symbol()); ?>)
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="ml">Discount Amount</label>
                    <input type="number" class="fi" id="discountAmount" placeholder="e.g. 10"
                        oninput="updateDiscountPreview()">
                </div>
                <div class="form-group">
                    <label class="ml">Discount Valid Until <small
                            style="font-weight:400;color:var(--muted)">(optional)</small></label>
                    <input type="date" class="fi" id="discountExpiry">
                </div>
                <div class="discount-preview" id="discountPreview" style="display:none">
                    <div>
                        <div class="dp-val" id="dpOld">—</div>
                        <div class="dp-lbl">Avg. Current</div>
                    </div>
                    <div style="font-size:22px;color:var(--muted)">→</div>
                    <div>
                        <div class="dp-val" id="dpNew" style="color:var(--success)">—</div>
                        <div class="dp-lbl">New Price</div>
                    </div>
                    <div>
                        <div class="dp-val" id="dpSave" style="color:var(--gallery-primary)">—</div>
                        <div class="dp-lbl">Saving</div>
                    </div>
                </div>
            </div>
            <div class="g-modal-footer">
                <button type="button" class="btn btn-ghost"
                    onclick="$('#discountModal').removeClass('open')">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="applyBulkDiscount()">Apply Discount</button>
            </div>
        </div>
    </div>

    <!-- IMPORT MODAL -->
    <div class="g-modal-overlay" id="importModal">
        <div class="g-modal">
            <div class="g-modal-header">
                <div class="g-modal-title">⬆️ Import Products</div>
                <button class="g-modal-close" onclick="$('#importModal').removeClass('open')">✕</button>
            </div>
            <form id="importForm" method="POST" action="<?php echo e(route('admin.item.bulk-import')); ?>"
                enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="button" value="import">
                <div class="g-modal-body">
                    <div
                        style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:12px 14px;font-size:12.5px;color:#0c4a6e;margin-bottom:16px;display:flex;gap:8px">
                        <span>ℹ️</span>
                        <div>Download the template first, fill in your product data, then upload. Supports CSV and Excel
                            (.xlsx). Max 5,000 rows per file.</div>
                    </div>
                    <div class="form-group">
                        <label class="ml" for="import-format">File Format</label>
                        <select class="fi" id="import-format" name="format">
                            <option value="csv">CSV (.csv)</option>
                            <option value="xlsx">Excel (.xlsx)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="ml" for="import-file">Upload File</label>
                        <div id="import-dropzone"
                            style="border:2px dashed var(--border);border-radius:10px;padding:28px;text-align:center;cursor:pointer;transition:all 0.2s;background:var(--bg)"
                            onclick="document.getElementById('import-file').click()"
                            onmouseover="this.style.borderColor='var(--gallery-primary)';this.style.background='var(--gallery-primary-light)'"
                            onmouseout="this.style.borderColor='var(--border)';this.style.background='var(--bg)'">
                            <div style="font-size:28px;margin-bottom:8px">📄</div>
                            <div id="import-filename" style="font-weight:600;font-size:13.5px;color:var(--navy)">Drop your
                                file here or click to browse</div>
                            <div style="font-size:11.5px;color:var(--muted);margin-top:4px">Supports .csv and .xlsx up to
                                10MB</div>
                        </div>
                        <input type="file" id="import-file" name="products_file" accept=".csv,.xlsx" style="display:none"
                            onchange="document.getElementById('import-filename').textContent = this.files[0]?.name || 'No file selected'">
                    </div>
                </div>
                <div class="g-modal-footer">
                    <a href="<?php echo e(route('admin.item.bulk-export-index', ['module_id' => Config::get('module.current_module_id')])); ?>"
                        class="btn btn-ghost">⬇️ Download Template</a>
                    <button type="button" class="btn btn-ghost"
                        onclick="$('#importModal').removeClass('open')">Cancel</button>
                    <button type="submit" class="btn btn-primary" onclick="return validateImportForm()">Start
                        Import</button>
                </div>
            </form>
        </div>
    </div>

    <!-- TOAST -->
    <div class="toast" id="toast"><span class="toast-icon" id="toastIcon">✅</span><span id="toastMsg">Action
            completed</span></div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
    <script>
        let searchTimer;
        let selectedIds = new Set();

        function debouncedSearch() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(applyFilters, 500);
        }

        function applyFilters(page = 1) {
            let formData = $('#gallery-filter-form').serialize();
            $.ajax({
                url: '<?php echo e(route("admin.item.search")); ?>?page=' + page,
                method: 'POST',
                data: formData,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                beforeSend: function () { $('#loading').show(); },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('#pagination-links').html(data.pagination);
                    updateStats(data.stats, data.total);
                    updateActiveFilterChips();
                },
                complete: function () { $('#loading').hide(); }
            });
        }

        function updateStats(stats, total) {
            if (stats) {
                $('#sv-active').text(stats.active);
                $('#sv-draft').text(stats.draft);
                $('#sv-oos').text(stats.oos);
                if (stats.expiry !== undefined) $('#sv-expiry').text(stats.expiry);
                $('#sv-total').text(total ?? stats.total ?? $('#sv-total').text());
            }
            $('#showingCount').text($('#set-rows tr').length);
        }

        function filterByStatus(status, el) {
            $('#filter-status-select').val(status === 'all' ? 'all' : status);
            $('#filter-status').val(status === 'all' ? 'all' : status);
            $('.stat-card').removeClass('active-filter');
            $(el).addClass('active-filter');
            applyFilters();
        }

        function resetFilters() {
            $('#gallery-filter-form')[0].reset();
            try { $('#category_id').val(null).trigger('change'); } catch (e) { }
            try { $('#brand_id').val('all').trigger('change'); } catch (e) { }
            $('#filter-status').val('all');
            $('#filter-sort').val('name_asc');
            $('#filter-limit').val('20');
            $('.stat-card').removeClass('active-filter');
            $('.stat-card').first().addClass('active-filter');
            let wrap = document.getElementById('activeFiltersRow');
            if (wrap) { wrap.style.display = 'none'; wrap.innerHTML = ''; }
            applyFilters();
        }

        // ── TOAST NOTIFICATION ─────────────────────────────────────────────
        let _toastTimer;
        function showToast(msg) {
            let t = document.getElementById('toast');
            let toastMsg = document.getElementById('toastMsg');
            if (!t || !toastMsg) return;
            toastMsg.textContent = msg;
            t.classList.add('show');
            clearTimeout(_toastTimer);
            _toastTimer = setTimeout(() => t.classList.remove('show'), 3500);
        }

        // ── ACTIVE FILTER CHIPS ─────────────────────────────────────────────
        function updateActiveFilterChips() {
            let chips = [];
            let q = $('#gallery-search').val();
            let cat = $('[name="category_id"]').find('option:selected').text();
            let brand = $('[name="brand_id"]').find('option:selected').text();
            let status = $('#filter-status').val();
            let minP = $('[name="min_price"]').val();
            let maxP = $('[name="max_price"]').val();

            if (q) chips.push('Search: "' + q + '"');
            if (cat && cat !== 'All Categories' && cat !== '' && cat !== '<?php echo e(translate("messages.all_categories")); ?>') chips.push('Category: ' + cat);
            if (brand && brand !== 'All Brands' && brand !== '' && brand !== '<?php echo e(translate("messages.all_brands")); ?>') chips.push('Brand: ' + brand);
            if (status && status !== 'all') chips.push('Status: ' + status.toUpperCase());
            if (minP || maxP) chips.push('Price: ' + (minP || '0') + ' – ' + (maxP || '∞'));

            let wrap = document.getElementById('activeFiltersRow');
            if (!wrap) return;
            if (!chips.length) { wrap.style.display = 'none'; return; }
            wrap.style.display = 'flex';
            wrap.innerHTML = chips.map(c =>
                `<span class="filter-chip">${c} <span class="rm" onclick="resetFilters()">✕</span></span>`
            ).join('');
        }

        function toggleSelectAll(cb) {
            $('.pc-check').prop('checked', cb.checked);
            $('.pc-check').each(function () {
                let id = $(this).val();
                if (cb.checked) {
                    selectedIds.add(String(id));
                    $('#row-' + id).addClass('selected');
                } else {
                    selectedIds.delete(String(id));
                    $('#row-' + id).removeClass('selected');
                }
            });
            if (selectedIds.size > 0) {
                $('#bulkBar').addClass('visible');
                $('#bulkCount').text(selectedIds.size + ' selected');
            } else {
                $('#bulkBar').removeClass('visible');
            }
        }

        function toggleRow(id, cb) {
            if (cb.checked) {
                selectedIds.add(String(id));
                $('#row-' + id).addClass('selected');
            } else {
                selectedIds.delete(String(id));
                $('#row-' + id).removeClass('selected');
            }
            if (selectedIds.size > 0) {
                $('#bulkBar').addClass('visible');
                $('#bulkCount').text(selectedIds.size + ' selected');
            } else {
                $('#bulkBar').removeClass('visible');
            }
            // sync the header selectAll checkbox
            let allChecked = $('.pc-check').length && $('.pc-check:not(:checked)').length === 0;
            $('#selectAll').prop('checked', allChecked);
        }

        function clearSelection() {
            $('.pc-check, #selectAll').prop('checked', false);
            selectedIds.clear();
            $('#bulkBar').removeClass('visible');
            $('.selected').removeClass('selected');
        }

        function bulkRequest(action) {
            if (selectedIds.size === 0) return;
            confirm_alert('bulk-action', 'Are you sure you want to perform this action?').then(result => { if (result.value) { $.post('<?php echo e(url("admin/item/bulk/action")); ?>/' + action, { _token: '<?php echo e(csrf_token()); ?>', id: Array.from(selectedIds) }, function (res) { toastr.success(res.message); clearSelection(); applyFilters(); }).fail(function (err) { toastr.error(err.responseJSON.message || 'Action failed'); }); } });
        }

        function editField(id, field, currentVal, label) {
            $('#edit-item-id').val(id);
            $('#edit-field-name').val(field);
            $('#quickEditTitle').text('✏️ Edit ' + label + ' — #' + id);

            let bodyHtml = '';

            if (field === 'price') {
                let row = $('#row-' + id);
                let costAttr = row.data('cost') || 0;
                bodyHtml = `
                                                    <div class="form-group">
                                                        <label class="ml" for="ie-cost">Cost Price (<?php echo e(\App\CentralLogics\Helpers::currency_symbol()); ?>)</label>
                                                        <input type="number" class="fi" id="ie-cost" name="cost_price" value="${costAttr}" step="0.01" oninput="calcMarginPreview()" placeholder="0.00">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="ml" for="ie-sell">Sell Price (<?php echo e(\App\CentralLogics\Helpers::currency_symbol()); ?>)</label>
                                                        <input type="number" class="fi" id="ie-sell" name="price" value="${currentVal}" step="0.01" oninput="calcMarginPreview()" placeholder="0.00">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="ml" for="ie-disc">Additional Discount (%)</label>
                                                        <input type="number" class="fi" id="ie-disc" value="0" min="0" max="100" step="0.1" oninput="calcMarginPreview()" placeholder="0">
                                                    </div>
                                                    <div class="ie-preview">
                                                        <div>
                                                            <div class="ie-preview-lbl">Final Price</div>
                                                            <div id="ie-final" class="ie-preview-val">—</div>
                                                        </div>
                                                        <div>
                                                            <div class="ie-preview-lbl">Margin</div>
                                                            <div id="ie-margin" class="ie-preview-val" style="color:var(--success)">—</div>
                                                        </div>
                                                    </div>`;
                $('#quickEditBody').html(bodyHtml);
                setTimeout(calcMarginPreview, 30);

            } else if (field === 'stock') {
                bodyHtml = `
                                                    <div class="form-group">
                                                        <label class="ml" for="ie-stock-val">Current Stock</label>
                                                        <input type="number" class="fi" id="ie-stock-val" name="stock" value="${currentVal}" min="0">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="ml" for="ie-threshold">Low Stock Alert Threshold</label>
                                                        <input type="number" class="fi" id="ie-threshold" value="20" min="0" placeholder="e.g. 20">
                                                    </div>
                                                    <div style="background:var(--bg);border-radius:8px;padding:10px 14px;font-size:12.5px;color:var(--muted);margin-top:4px">
                                                        💡 Stock will be updated immediately. A toast alert is shown when stock falls below the threshold.
                                                    </div>`;
                $('#quickEditBody').html(bodyHtml);

            } else {
                bodyHtml = `
                                                    <div class="form-group">
                                                        <label class="ml" for="ie-generic">${label}</label>
                                                        <input type="text" class="fi" id="ie-generic" name="value" value="${currentVal}">
                                                    </div>`;
                $('#quickEditBody').html(bodyHtml);
            }

            $('#quickEditModal').addClass('open');
        }

        function calcMarginPreview() {
            let cost = parseFloat($('#ie-cost').val()) || 0;
            let sell = parseFloat($('#ie-sell').val()) || 0;
            let disc = parseFloat($('#ie-disc').val()) || 0;
            let final = sell * (1 - disc / 100);
            let margin = sell > 0 ? Math.round(((final - cost) / final) * 100) : 0;
            let color = margin >= 35 ? 'var(--success)' : margin >= 20 ? 'var(--warn)' : 'var(--danger)';
            $('#ie-final').text('<?php echo e(\App\CentralLogics\Helpers::currency_symbol()); ?> ' + final.toFixed(2));
            $('#ie-margin').text(margin + '%').css('color', color);
        }

        $(function () {
            // ── Select2 for Store & Category ─────────────────────────────
            if ($('#store_id').length) {
                $('#store_id').select2({
                    ajax: {
                        url: '<?php echo e(url("/")); ?>/admin/store/get-stores',
                        data: function (params) {
                            return { q: params.term, module_id: '<?php echo e($moduleId); ?>', page: params.page };
                        },
                        processResults: function (data) { return { results: data }; }
                    }
                });
            }
            if ($('#category_id').length) {
                $('#category_id').select2({
                    placeholder: "Select Category",
                    allowClear: true
                });
            }
            if ($('#brand_id').length) {
                $('#brand_id').select2({
                    placeholder: "Select Brand",
                    allowClear: true
                });
            }

            // ── Quick Edit form submit ────────────────────────────────────
            $('#quickEditForm').on('submit', function (e) {
                e.preventDefault();
                let id = $('#edit-item-id').val();
                let field = $('#edit-field-name').val();
                let payload = { _token: '<?php echo e(csrf_token()); ?>', id: id };

                if (field === 'price') {
                    payload.price = $('#ie-sell').val();
                    payload.cost_price = $('#ie-cost').val();
                } else if (field === 'stock') {
                    payload.stock = $('#ie-stock-val').val();
                    let threshold = parseInt($('#ie-threshold').val()) || 0;
                    let stock = parseInt($('#ie-stock-val').val()) || 0;
                    if (stock < threshold && threshold > 0) {
                        showToast('⚠️ Warning: stock (' + stock + ') is below the alert threshold (' + threshold + ')');
                    }
                } else {
                    let genericVal = $('#ie-generic').val();
                    payload[field] = genericVal;
                }

                $.post('<?php echo e(route("admin.item.stock-update")); ?>', payload, function (res) {
                    showToast('✅ Updated successfully');
                    $('#quickEditModal').removeClass('open');
                    applyFilters();
                }).fail(function (err) {
                    let msg = (err.responseJSON && err.responseJSON.message) ? err.responseJSON.message : 'Update failed';
                    showToast('❌ ' + msg);
                });
            });

            // ── AJAX Pagination ──────────────────────────────────────────
            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                applyFilters(page);
            });
        }); // end $(function)
        function openBulkDiscount() {
            if (selectedIds.size === 0) return;
            $('#discountItemCount').text(selectedIds.size + ' products');
            $('#discountModal').addClass('open');
        }
        function applyBulkDiscount() {
            let type = $('#discountType').val();
            let amount = $('#discountAmount').val();
            let expiry = $('#discountExpiry').val();
            if (!amount) { showToast('⚠️ Please enter a discount amount'); return; }
            if (selectedIds.size === 0) { showToast('⚠️ No products selected'); return; }
            if (confirm('Apply ' + type + ' discount of ' + amount + ' to ' + selectedIds.size + ' product(s)?')) {
                $.post('<?php echo e(route("admin.item.bulk-action", ["action" => "discount"])); ?>', {
                    _token: '<?php echo e(csrf_token()); ?>',
                    id: Array.from(selectedIds),
                    discount_type: type,
                    discount_amount: amount,
                    discount_expiry: expiry || null
                }, function (res) {
                    showToast('🏷️ ' + res.message);
                    $('#discountModal').removeClass('open');
                    clearSelection();
                    applyFilters();
                }).fail(function (err) {
                    let msg = (err.responseJSON && err.responseJSON.message) ? err.responseJSON.message : 'Action failed';
                    showToast('❌ ' + msg);
                });
            }
        }
        function validateImportForm() {
            let file = document.getElementById('import-file');
            if (!file || !file.files || file.files.length === 0) {
                showToast('⚠️ Please select a file to import');
                return false;
            }
            return true;
        }
        function exportData() {
            let formData = $('#gallery-filter-form').serialize();
            let url = '<?php echo e(route("admin.item.export")); ?>?' + formData;
            window.location.href = url;
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\product\product_gallery.blade.php ENDPATH**/ ?>