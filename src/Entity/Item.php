<?php

namespace App\Entity;

use App\Interface\Entity;
use App\Trait\ToArrayTrait;

/**
 * The `Item` class provides methods to deal with items and their details.
 * 
 * The contained methods are getting/setting its properties
 * and convert them to an array using the 'ToArrayTrait'.
 */
class Item implements Entity {

    private array $keyArray;
    private int|null $id;
    private string $itemId;
    private string $title;
    private float $currentPrice;
    private string $listingStatus;
    private int $quantity;
    private int $quantitySold;
    private int $condition;
    private int $category;
    private string $storeCategoryId;
    private string $storeCategory2Id;
    private string $viewItemUrl;
    private string $jsonArrayPictures;
    private int $site;
    private string $country;
    private string $currency;
    private string $jsonArrayShipToLocations;
    private string $jsonArrayShippingOptions;
    private string $jsonArrayItemCompatibility;
    private string $jsonArrayItemSpecifics;
    private string $htmlDescription;
    private float $netPrice;
    private string $filetime;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     * 
     * This is just a general collection, as there are many other details of an item and its listing existing.
     * 
     * @param string $itemId Portal's unique identifier of an item.
     * @param string $title Item's title.
     * @param float $currentPrice Item's price.
     * @param string $listingStatus Item's listing status (like "Active", "Ended", etc.). 
     * @param int $quantity Number of items available at start time of i's listing.
     * @param int $quantitySold Number of items that have been sold already.
     * @param int $condition Item's condition (like 1000 representing "New", etc.).
     * @param int $category Portal's unique identifier of a category.
     * @param string $storeCategoryId Seller's unique identifier of a category.
     * @param string $storeCategory2Id Seller's unique identifier of a second category.
     * @param string $viewItemUrl Item's listing page url.
     * @param string $jsonArrayPictures Stringified JSON array containing urls of the item's pictures.
     * @param int $site Portal's unique identifier of a country related site the listing is published on.
     * @param string $country Country where the item is located or being sold.
     * @param string $currency Currency being used in the item's listing.
     * @param string $jsonArrayShipToLocations Stringified JSON array containing the locations the item can be shipped to.
     * @param string $jsonArrayShippingOptions Stringified JSON array containing shipping options for an item. 
     * @param string $jsonArrayItemCompatibility Stringified JSON array containing the compatibility information for an item.
     * @param string $jsonArrayItemSpecifics Stringified JSON array containing item specific attributes.
     * @param string $htmlDescription Portal's html of the item's listing.
     * @param float $netPrice Item's current price without VAT.
     * @param string $filetime Time when the item's detail file was last modified (Format: "Y-m-d H:i:s").
     * @param int|null $id Primary Key, possibly empty as coming from the database (Default = null).
     * 
     * @return void
     */
    public function __construct(
        array $keyArray,
        string $itemId,
        string $title,
        float $currentPrice,
        string $listingStatus,
        int $quantity,
        int $quantitySold,
        int $condition,
        int $category,
        string $storeCategoryId,
        string $storeCategory2Id,
        string $viewItemUrl,
        string $jsonArrayPictures,
        int $site,
        string $country,
        string $currency,
        string $jsonArrayShipToLocations,
        string $jsonArrayShippingOptions,
        string $jsonArrayItemCompatibility,
        string $jsonArrayItemSpecifics,
        string $htmlDescription,
        float $netPrice,
        string $filetime,
        int|null $id = null,
    ) {
        $this->id = $id;
        $this->itemId = $itemId;
        $this->title = $title;
        $this->currentPrice = $currentPrice;
        $this->listingStatus = $listingStatus;
        $this->quantity = $quantity;
        $this->quantitySold = $quantitySold;
        $this->condition = $condition;
        $this->category = $category;
        $this->storeCategoryId = $storeCategoryId;
        $this->storeCategory2Id = $storeCategory2Id;
        $this->viewItemUrl = $viewItemUrl;
        $this->jsonArrayPictures = $jsonArrayPictures;
        $this->site = $site;
        $this->country = $country;
        $this->currency = $currency;
        $this->jsonArrayShipToLocations = $jsonArrayShipToLocations;
        $this->jsonArrayShippingOptions = $jsonArrayShippingOptions;
        $this->jsonArrayItemCompatibility = $jsonArrayItemCompatibility;
        $this->jsonArrayItemSpecifics = $jsonArrayItemSpecifics;
        $this->htmlDescription = $htmlDescription;
        $this->netPrice = $netPrice;
        $this->filetime = $filetime;
        $this->keyArray = $keyArray;
    }


    // Getters
    public function getId(): int|null {
        return $this->id;
    }

    public function getItemId(): string {
        return $this->itemId;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getCurrentPrice(): float {
        return $this->currentPrice;
    }

    public function getListingStatus(): string {
        return $this->listingStatus;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function getQuantitySold(): int {
        return $this->quantitySold;
    }

    public function getCondition(): int {
        return $this->condition;
    }

    public function getCategory(): int {
        return $this->category;
    }

    public function getStoreCategoryId(): string {
        return $this->storeCategoryId;
    }

    public function getStoreCategory2Id(): string {
        return $this->storeCategory2Id;
    }

    public function getViewItemUrl(): string {
        return $this->viewItemUrl;
    }

    public function getPictures(): string {
        return $this->jsonArrayPictures;
    }

    public function getSite(): int {
        return $this->site;
    }

    public function getCountry(): string {
        return $this->country;
    }

    public function getCurrency(): string {
        return $this->currency;
    }

    public function getShipToLocations(): string {
        return $this->jsonArrayShipToLocations;
    }

    public function getShippingOptions(): string {
        return $this->jsonArrayShippingOptions;
    }

    public function getItemCompatibility(): string {
        return $this->jsonArrayItemCompatibility;
    }

    public function getItemSpecifics(): string {
        return $this->jsonArrayItemSpecifics;
    }

    public function getHtmlDescription(): string {
        return $this->htmlDescription;
    }

    public function getNetPrice(): float {
        return $this->netPrice;
    }

    public function getFiletime(): string {
        return $this->filetime;
    }


    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setItemId(string $itemId): void {
        $this->itemId = $itemId;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function setCurrentPrice(float $currentPrice): void {
        $this->currentPrice = $currentPrice;
    }

    public function setListingStatus(string $listingStatus): void {
        $this->listingStatus = $listingStatus;
    }

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }

    public function setQuantitySold(int $quantitySold): void {
        $this->quantitySold = $quantitySold;
    }

    public function setCondition(int $condition): void {
        $this->condition = $condition;
    }

    public function setCategory(int $category): void {
        $this->category = $category;
    }

    public function setStoreCategoryId(string $storeCategoryId): void {
        $this->storeCategoryId = $storeCategoryId;
    }

    public function setStoreCategory2Id(string $storeCategory2Id): void {
        $this->storeCategory2Id = $storeCategory2Id;
    }

    public function setViewItemUrl(string $viewItemUrl): void {
        $this->viewItemUrl = $viewItemUrl;
    }

    public function setPictures(string $jsonArrayPictures): void {
        $this->jsonArrayPictures = $jsonArrayPictures;
    }

    public function setSite(int $site): void {
        $this->site = $site;
    }

    public function setCountry(string $country): void {
        $this->country = $country;
    }

    public function setCurrency(string $currency): void {
        $this->currency = $currency;
    }

    public function setShipToLocations(string $jsonArrayShipToLocations): void {
        $this->jsonArrayShipToLocations = $jsonArrayShipToLocations;
    }

    public function setShippingOptions(string $jsonArrayShippingOptions): void {
        $this->jsonArrayShippingOptions = $jsonArrayShippingOptions;
    }

    public function setItemCompatibility(string $jsonArrayItemCompatibility): void {
        $this->jsonArrayItemCompatibility = $jsonArrayItemCompatibility;
    }

    public function setItemSpecifics(string $jsonArrayItemSpecifics): void {
        $this->jsonArrayItemSpecifics = $jsonArrayItemSpecifics;
    }

    public function setHtmlDescription(string $htmlDescription): void {
        $this->htmlDescription = $htmlDescription;
    }

    public function setNetPrice(float $netPrice): void {
        $this->netPrice = $netPrice;
    }

    public function setFiletime(string $filetime): void {
        $this->filetime = $filetime;
    }


    // Import and use the 'toArray' method of the `ToArrayTrait` trait.
    use ToArrayTrait;
}
