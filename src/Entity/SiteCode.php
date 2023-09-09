<?php

namespace App\Entity;

use App\Interface\Entity;
use App\Trait\ToArrayTrait;

/**
 * The `SiteCode` class provides methods to deal with codes specifying country related sites.
 * 
 * The contained methods are getting/setting its properties
 * and convert them to an array using the 'ToArrayTrait'.
 */
class SiteCode implements Entity {

    private int|null $id;
    private int $siteId;
    private string $siteName;
    private string $globalId;
    private array $keyArray;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     * 
     * @param array<int,string> $keyArray Representing valid column names of the corresponding database table.
     * @param int $siteId Unique identifier of a country related site the listing is published on.
     * @param string $siteName Represents the verbal site name.
     * @param string $globalId Represents the verbal code. 
     * @param int|null $id Primary Key, possibly empty as coming from the database (Default = null).
     * 
     * @return void
     */
    public function __construct(array $keyArray, int $siteId, string $siteName, string $globalId, int|null $id = null) {

        $this->id = $id;
        $this->siteId = $siteId;
        $this->siteName = $siteName;
        $this->globalId = $globalId;
        $this->keyArray = $keyArray;
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

    public function getSiteGlobalId(): string {
        return $this->globalId;
    }


    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setSiteId(int $siteId): void {
        $this->siteId = $siteId;
    }

    public function setSiteName(string $siteName): void {
        $this->siteName = $siteName;
    }

    public function setSiteGlobalId(string $globalId): void {
        $this->globalId = $globalId;
    }


    // Import and use the 'toArray' method of the `ToArrayTrait` trait.
    use ToArrayTrait;
}
