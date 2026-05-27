<style>
.page-header-card {
    background: #ffffff !important;
    border-radius: 12px !important;
    padding: 16px !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
    border: none !important;
    margin-bottom: 0 !important;
}

.page-header-card .page-header-title {
    font-size: 18px !important;
    font-weight: 700 !important;
    color: #333333 !important;
    margin: 0 0 8px 0 !important;
    line-height: 1.4 !important;
    text-align: right !important;
}

.page-header-card .page-header-subtitle {
    font-size: 14px !important;
    color: #666666 !important;
    margin: 0 !important;
    line-height: 1.4 !important;
    text-align: right !important;
}

.homes-mobile-search-wrap {
    width: 100%;
}

.homes-mobile-search-form {
    display: flex;
    align-items: stretch;
    gap: 8px;
    width: 100%;
}

.homes-mobile-search-input-wrap {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 6px;
    background: #fff;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 6px 10px;
    min-height: 40px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.homes-mobile-search-input-wrap:focus-within {
    border-color: #D39D1A;
    box-shadow: 0 2px 8px rgba(211, 157, 26, 0.2);
}

.homes-mobile-search-input {
    flex: 1;
    min-width: 0;
    border: none;
    background: transparent;
    padding: 2px 4px;
    font-size: 13px;
    color: #333;
    outline: none;
    font-family: inherit;
    line-height: 1.4;
}

.homes-mobile-search-input::placeholder {
    color: #999;
    font-size: 12px;
}

.homes-mobile-search-btn {
    flex-shrink: 0;
    width: 40px;
    min-width: 40px;
    height: 40px;
    align-self: center;
    border: none;
    border-radius: 10px;
    background: #D39D1A;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(211, 157, 26, 0.3);
    transition: background 0.2s ease, transform 0.15s ease;
}

.homes-mobile-search-btn .bi {
    font-size: 16px;
}

.homes-mobile-search-btn:active {
    transform: scale(0.97);
    background: #B8860B;
}
</style>
