<?php

function getAdminPermissions(): array
{
    return array_merge(getUserGoldPermissions(), [
        'access_admin',
        'user_create',
        'user_read',
        'user_update',
        'user_delete',
    ]);
}

function getUserGoldPermissions(): array
{
    return array_merge(getUserSilverPermissions(), [

        // Market Permissions
        'market_create',
        'market_read',
        'market_update',
        'market_delete',

        // Market Item Category Permissions
        'market_item_category_create',
        'market_item_category_read',
        'market_item_category_update',
        'market_item_category_delete',

        // Market Item Permissions
        'market_item_create',
        'market_item_read',
        'market_item_update',
        'market_item_delete',

        // Market Stock Permissions
        'market_stock_create',
        'market_stock_read',
        'market_stock_update',
        'market_stock_delete',
        'market_stock_entry_create',
        'market_stock_entry_read',
        'market_stock_entry_update',
        'market_stock_entry_delete',
        'market_stock_withdraw_create',
        'market_stock_withdraw_read',
        'market_stock_withdraw_update',
        'market_stock_withdraw_delete',

    ]);
}

function getUserSilverPermissions(): array
{
    return array_merge(getUserPermissions(), [

        // Investment Permissions
        'investment_create',
        'investment_read',
        'investment_update',
        'investment_delete',
        'investment_entry_create',
        'investment_entry_read',
        'investment_entry_update',
        'investment_entry_delete',
        'investment_withdraw_create',
        'investment_withdraw_read',
        'investment_withdraw_update',
        'investment_withdraw_delete',

        // Trip Permissions
        'trip_create',
        'trip_read',
        'trip_update',
        'trip_delete',
        'trip_entry_create',
        'trip_entry_read',
        'trip_entry_update',
        'trip_entry_delete',
        'trip_withdraw_create',
        'trip_withdraw_read',
        'trip_withdraw_update',
        'trip_withdraw_delete',

        // Couple Spending Category Permissions
        'couple_spending_category_create',
        'couple_spending_category_read',
        'couple_spending_category_update',
        'couple_spending_category_delete',

        // Couple Spending Permissions
        'couple_spending_create',
        'couple_spending_read',
        'couple_spending_update',
        'couple_spending_delete',
    ]);
}

function getUserPermissions(): array
{
    return [

        // Bank Account Permissions
        'bank_account_create',
        'bank_account_read',
        'bank_account_update',
        'bank_account_delete',
        'bank_account_entry_create',
        'bank_account_entry_read',
        'bank_account_entry_update',
        'bank_account_entry_delete',
        'bank_account_withdraw_create',
        'bank_account_withdraw_read',
        'bank_account_withdraw_update',
        'bank_account_withdraw_delete',

        // Credit Card Permissions
        'credit_card_create',
        'credit_card_read',
        'credit_card_update',
        'credit_card_delete',
        'credit_card_spending_create',
        'credit_card_spending_read',
        'credit_card_spending_update',
        'credit_card_spending_delete',

        // Goal Permissions
        'goal_create',
        'goal_read',
        'goal_update',
        'goal_delete',
        'goal_entry_create',
        'goal_entry_read',
        'goal_entry_update',
        'goal_entry_delete',
        'goal_withdraw_create',
        'goal_withdraw_read',
        'goal_withdraw_update',
        'goal_withdraw_delete',
    ];
}
