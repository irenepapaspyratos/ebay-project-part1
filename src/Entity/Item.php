<?php

namespace App\Entity;

/**
 * The `Item` class provides methods to deal with items and their details.
 * 
 * The contained methods are getting/setting its properties
 * and convert them to an array.
 */
class Item implements Entity {

    private ?int $id;
    private string $itemId;
    private string $title;
    private float $currentPrice;
    private string $listingStatus;
    private int $quantity;
    private int $quantitySold;
    private int $conditionId;
    private int $categoryId;
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
     * @param int|null $id Unique identifier of the item. Possibly null if no id is provided.
     * @param string $itemId Portal's unique identifier of an item.
     * @param string $title Item's title.
     * @param float $currentPrice Item's price.
     * @param string $listingStatus Item's listing status (like "Active", "Ended", etc.). 
     * @param int $quantity Number of items available at start time of i's listing.
     * @param int $quantitySold Number of items that have been sold already.
     * @param int $conditionId Item's condition (like 1000 representing "New", etc.).
     * @param int $categoryId Portal's unique identifier of a category.
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
     * 
     * @return void
     */
    public function __construct(
        ?int $id,
        string $itemId,
        string $title,
        float $currentPrice,
        string $listingStatus,
        int $quantity,
        int $quantitySold,
        int $conditionId,
        int $categoryId,
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
        string $filetime
    ) {
        $this->id = $id;
        $this->itemId = $itemId;
        $this->title = $title;
        $this->currentPrice = $currentPrice;
        $this->listingStatus = $listingStatus;
        $this->quantity = $quantity;
        $this->quantitySold = $quantitySold;
        $this->conditionId = $conditionId;
        $this->categoryId = $categoryId;
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

    public function getConditionId(): int {
        return $this->conditionId;
    }

    public function getCategoryId(): int {
        return $this->categoryId;
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
    public function setId(?int $id): void {
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

    public function setConditionId(int $conditionId): void {
        $this->conditionId = $conditionId;
    }

    public function setCategoryId(int $categoryId): void {
        $this->categoryId = $categoryId;
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


    /**
     * The 'toArray' method converts the object of the class to an array.
     * 
     * @return array Array representation of the object.
     */
    public function toArray(): array {

        return [
            'id' => $this->getId(),
            'item_id' => $this->getItemId(),
            'title' => $this->getTitle(),
            'current_price' => $this->getCurrentPrice(),
            'listing_status' => $this->getListingStatus(),
            'quantity' => $this->getQuantity(),
            'quantity_sold' => $this->getQuantitySold(),
            'condition_id' => $this->getConditionId(),
            'category_id' => $this->getCategoryId(),
            'store_category_id' => $this->getStoreCategoryId(),
            'store_category_2_id' => $this->getStoreCategory2Id(),
            'view_item_url' => $this->getViewItemUrl(),
            'pictures' => $this->getPictures(),
            'site' => $this->getSite(),
            'country' => $this->getCountry(),
            'currency' => $this->getCurrency(),
            'ship_to_locations' => $this->getShipToLocations(),
            'shipping_options' => $this->getShippingOptions(),
            'item_compatibility' => $this->getItemCompatibility(),
            'item_specifics' => $this->getItemSpecifics(),
            'html_description' => $this->getHtmlDescription(),
            'net_price' => $this->getNetPrice(),
            'filetime' => $this->getFiletime()
        ];
    }
}
