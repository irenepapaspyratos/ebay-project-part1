<?php

namespace App\Entity;

class Item implements Entity {

    private $id;
    private $itemId;
    private $title;
    private $currentPrice;
    private $listingStatus;
    private $quantity;
    private $quantitySold;
    private $conditionId;
    private $categoryId;
    private $storeCategoryId;
    private $storeCategory2Id;
    private $viewItemUrl;
    private $pictures;
    private $site;
    private $country;
    private $currency;
    private $shipToLocations;
    private $shippingOptions;
    private $itemCompatibility;
    private $itemSpecifics;
    private $htmlDescription;
    private $netPrice;
    private $filetime;

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
        array $pictures,
        int $site,
        string $country,
        string $currency,
        array $shipToLocations,
        array $shippingOptions,
        array $itemCompatibility,
        array $itemSpecifics,
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
        $this->pictures = $pictures;
        $this->site = $site;
        $this->country = $country;
        $this->currency = $currency;
        $this->shipToLocations = $shipToLocations;
        $this->shippingOptions = $shippingOptions;
        $this->itemCompatibility = $itemCompatibility;
        $this->itemSpecifics = $itemSpecifics;
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

    public function getPictures(): array {
        return $this->pictures;
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

    public function getShipToLocations(): array {
        return $this->shipToLocations;
    }

    public function getShippingOptions(): array {
        return $this->shippingOptions;
    }

    public function getItemCompatibility(): array {
        return $this->itemCompatibility;
    }

    public function getItemSpecifics(): array {
        return $this->itemSpecifics;
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

    public function setPictures(array $pictures): void {
        $this->pictures = $pictures;
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

    public function setShipToLocations(array $shipToLocations): void {
        $this->shipToLocations = $shipToLocations;
    }

    public function setShippingOptions(array $shippingOptions): void {
        $this->shippingOptions = $shippingOptions;
    }

    public function setItemCompatibility(array $itemCompatibility): void {
        $this->itemCompatibility = $itemCompatibility;
    }

    public function setItemSpecifics(array $itemSpecifics): void {
        $this->itemSpecifics = $itemSpecifics;
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
