<style>
    :root {
        --font-serif: "Noto Serif", serif;
        --ui-background: #fef9f3;
        --ui-surface: #fef9f3;
        --ui-surface-container: #f2ede7;
        --ui-surface-container-low: #f8f3ed;
        --ui-surface-container-high: #ece7e2;
        --ui-surface-container-lowest: #ffffff;
        --ui-surface-variant: #e6e2dc;
        --ui-on-surface: #1d1b18;
        --ui-on-surface-variant: #51443c;
        --ui-primary: #50290b;
        --ui-primary-container: #6b3f1f;
        --ui-secondary: #835500;
        --ui-secondary-container: #feae2c;
        --ui-on-secondary-container: #6b4500;
        --ui-outline: #84746b;
        --ui-outline-variant: #d6c3b8;
        --ui-error: #ba1a1a;
        --ui-success: #15803d;
        --ui-warning: #b45309;
        --ui-info: #1d4ed8;
    }

    .font-serif {
        font-family: var(--font-serif);
    }

    .ui-page-header {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    @media (min-width: 640px) {
        .ui-page-header {
            flex-direction: row;
            align-items: center;
        }
    }

    .ui-card {
        background: var(--ui-surface-container-lowest);
        border: 1px solid rgba(214, 195, 184, 0.3);
        border-radius: 0.75rem;
        box-shadow: 0 1px 2px rgba(80, 41, 11, 0.05);
    }

    .ui-panel {
        background: var(--ui-surface);
        border: 1px solid rgba(214, 195, 184, 0.3);
        border-radius: 0.75rem;
        box-shadow: 0 1px 2px rgba(80, 41, 11, 0.05);
    }

    .ui-input {
        width: 100%;
        border-radius: 0.75rem;
        border: 1px solid rgba(214, 195, 184, 0.5);
        background: var(--ui-surface);
        color: var(--ui-on-surface);
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .ui-input:focus {
        border-color: var(--ui-secondary);
        box-shadow: 0 0 0 1px var(--ui-secondary);
        outline: none;
    }

    .ui-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 700;
        transition: filter 0.2s ease, background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
    }

    .ui-btn-primary {
        background: var(--ui-secondary-container);
        color: var(--ui-on-secondary-container);
    }

    .ui-btn-primary:hover {
        filter: brightness(1.08);
    }

    .ui-btn-secondary {
        border: 1px solid var(--ui-outline-variant);
        color: var(--ui-on-surface-variant);
        background: transparent;
    }

    .ui-btn-secondary:hover {
        background: var(--ui-surface-container-low);
    }

    .ui-btn-danger {
        background: var(--ui-error);
        color: #ffffff;
    }

    .ui-btn-icon {
        width: 2.25rem;
        height: 2.25rem;
        padding: 0;
        border-radius: 0.5rem;
    }

    .ui-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        border-radius: 9999px;
        padding: 0.25rem 0.625rem;
        font-size: 0.75rem;
        font-weight: 700;
        line-height: 1;
    }

    .ui-badge-success {
        background: #dcfce7;
        color: var(--ui-success);
    }

    .ui-badge-warning {
        background: #fef3c7;
        color: var(--ui-warning);
    }

    .ui-badge-danger {
        background: #fee2e2;
        color: var(--ui-error);
    }

    .ui-badge-info {
        background: #dbeafe;
        color: var(--ui-info);
    }

    .ui-badge-neutral {
        background: var(--ui-surface-variant);
        color: var(--ui-on-surface-variant);
    }

    .ui-icon-xs { font-size: 14px; }
    .ui-icon-sm { font-size: 16px; }
    .ui-icon-md { font-size: 18px; }
    .ui-icon-lg { font-size: 20px; }
    .ui-icon-nav { font-size: 22px; }
    .icon-filled { font-variation-settings: "FILL" 1; }

    .ui-modal-card {
        width: 100%;
        max-width: 24rem;
        border-radius: 1rem;
        border: 1px solid var(--ui-outline-variant);
        background: var(--ui-background);
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 25px 50px -12px rgba(80, 41, 11, 0.25);
    }

    @media (max-width: 767px) {
        .responsive-card-table thead {
            display: none;
        }

        .responsive-card-table,
        .responsive-card-table tbody,
        .responsive-card-table tr,
        .responsive-card-table td {
            display: block;
            width: 100%;
        }

        .responsive-card-table tbody {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding: 0.75rem;
        }

        .responsive-card-table tr {
            border: 1px solid rgba(132, 116, 107, 0.28);
            border-radius: 0.75rem;
            overflow: hidden;
            background: var(--ui-surface);
        }

        .responsive-card-table td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.75rem 1rem;
            text-align: right;
        }

        .responsive-card-table td::before {
            content: attr(data-label);
            color: var(--ui-on-surface-variant);
            flex: 0 0 auto;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-align: left;
            text-transform: uppercase;
        }

        .responsive-card-table .main-cell,
        .responsive-card-table .primary-cell,
        .responsive-card-table .student-profile-cell,
        .responsive-card-table .submission-main-cell {
            align-items: flex-start;
            flex-direction: column;
            text-align: left;
        }

        .responsive-card-table .action-cell,
        .responsive-card-table .submission-action-cell,
        .responsive-card-table .mobile-action-cell {
            justify-content: flex-start;
        }

        .responsive-card-table .feedback-cell {
            display: block;
            text-align: left;
        }

        .responsive-card-table .feedback-cell::before {
            display: none;
        }
    }
</style>
