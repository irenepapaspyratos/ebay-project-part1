# The Ebay Project </br>Part 1 - Creating a Database

</br>

## Overview

_The Ebay Project_ can help people, who are selling manually listed items on eBay, to sell those items also on other portals - not having to change their eBay listing routine.

This first part of the project creates a database of the seller's items available on eBay and ensures to keep that database automatically up-to-date. The main events will create a log entry to document the process. The database can then be used to list the items on other selling portals - but this will be covered in other parts of the project.

</br>

## About

### Language

_The Ebay Project_ was written in PHP, because this is one requirement of the server it will be running on. No Framework is used and libraries are integrated as little as possible, in order to show the user the full ongoing process.

### Ebay API

Only the _Trading API_ is used in order to reduce the effort of eventually extending the project, as this API provides ALL specifics of an item and can also be used to delete, update or add items.

### Ebay API Access

Different use-cases for having/wishing to create a database of eBay listings may exist, bot this project only covers the authentication of an eBay seller, who is also the owner of the necessary eBay developer account.

### Database

eBay provides lots of categories with different mandatory/optional item specifics as well as several other differences depending on the country of publishing. Because of it's performance and unique features, MariaDB has been chosen as local database for the items' details along with a simple online hosted space to act as datapool for images and original responses. Like this, the code can be kept generalized, reusable and as simple as possible while minimizing the local storage.

</br>

## Getting Started

### Prerequisites

-   PHP 8.0 or higher
-   Composer
-   MariaDB 10 or higher
-   eBay Seller & Developer Account with corresponding Authentication Token

### Installation

1. Clone/Copy/Download this repository
1. Navigate to the project directory and update/install the composer.json with `composer install`
1. Copy the `.env.example` file to a new file called `.env` and fill in your eBay API credentials