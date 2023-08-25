<?php

namespace App\Enum\Ebay;

/**
 * The `EbayDetailLevel` class defines an enumeration.
 * 
 * Represents different levels of details that can be requested when making API calls to eBay.
 */
enum EbayDetailLevel: string {

    case ALL = 'ReturnAll';
    case ITEM_ATTRIBUTES = 'ItemReturnAttributes';
    case ITEM_CATEGORIES = 'ItemReturnCategories';
    case ITEM_DESCRIPTION = 'ItemReturnDescription';
    case HEADERS = 'ReturnHeaders';
    case MESSAGES = 'ReturnMessages';
    case SUMMARY = 'ReturnSummary';
}
