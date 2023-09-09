<?php

namespace App\Enum;

/**
 * The `EbayGranularityLevel` class defines an enumeration.
 * 
 * Represents different levels of details that can be requested when making API calls to eBay.
 */
enum EbayGranularityLevel: string {

    case COARSE = 'Coarse';
    case MEDIUM = 'Medium';
    case FINE = 'Fine';
    case CUSTOM = 'CustomCode';
}
