<?php

namespace App\Entity;

/**
 * The `SiteCode` class provides methods to deal with codes specifying country related sites.
 * 
 * The contained methods are getting/setting its properties
 * and convert them to an array.
 */
class SiteCode implements Entity {

    private ?int $id;
    private int $siteId;
    private string $siteName;
    private string $globalId;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     * 
     * @param ?int $id Primary Key, possibly empty as coming from the database.
     * @param int $siteId Unique identifier of a country related site the listing is published on.
     * @param string $siteName Represents the verbal site name.
     * @param string $globalId Represents the verbal code. 
     * 
     * @return void
     */
    public function __construct(?int $id, int $siteId, string $siteName, string $globalId) {

        $this->id = $id;
        $this->siteId = $siteId;
        $this->siteName = $siteName;
        $this->globalId = $globalId;
    }


    // Getters
    public function getId(): int|null {
        return $this->id;
    }

    public function getSiteId(): int {
        return $this->siteId;
    }

    public function getSiteName(): string {
        return $this->siteName;
    }

    public function getGlobalId(): string {
        return $this->globalId;
    }


    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setSiteId(int $siteId): void {
        $this->siteId = $siteId;
    }

    public function setSiteName(string $siteName): void {
        $this->siteName = $siteName;
    }

    public function setGlobalId(string $globalId): void {
        $this->globalId = $globalId;
    }


    /**
     * The 'toArray' method converts the object of the class to an array.
     * 
     * @return array Array representation of the object.
     */
    public function toArray(): array {

        return [
            'id' => $this->getId(),
            'site_id' => $this->getSiteId(),
            'site_name' => $this->getSiteName(),
            'global_id' => $this->getGlobalId()
        ];
    }
}
