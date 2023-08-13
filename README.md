```diff
-!! Under Construction !!-
```

# The Ebay Project </br>Part 1 - Creating a Database

</br>

|                                                                                               Backend Testing:                                                                                                |
| :-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------: |
| [![Coverage Status](https://coveralls.io/repos/github/irenepapaspyratos/ebay-project-part1/badge.svg?branch=main&kill_cache=1)](https://coveralls.io/github/irenepapaspyratos/ebay-project-part1?branch=main) |

</br>

## Overview

_The Ebay Project_ can help people, who are selling manually listed items on eBay, to sell those items also on other portals - not having to change their eBay listing routine.

This first part of the project creates a database of the seller's items available on eBay and ensures to keep that database automatically up-to-date. Timestamps are exclusively eBay's official time to ensure consistency and the main events create log entries to document the process. The database can then be used to list the items on other selling portals - but this will be covered in other parts of _The Ebay Project_.

</br>

## About

### Technical Documentation

The project's [Technical Documentation](https://irenepapaspyratos.github.io/ebay-project-part1/) is hosted on GitHub Pages and offers details about the codebase. It was generated using [phpDocumentor](https://www.phpdoc.org/) with the help of [Mintlify](https://marketplace.visualstudio.com/items?itemName=mintlify.document) to create the necessary DocBlock styled comments.

### Language

_The Ebay Project_ was written in PHP, because this is one requirement of the server it will be running on. No Framework is used and libraries are integrated as little as possible, in order to show the user the full ongoing process.

### Ebay API

Only the _Trading API_ is used in order to reduce the effort of eventually extending the project, as this API provides ALL specifics of an item and can be used to delete, update or add items as well as to receive a list of modified items for a specified time window.

### Ebay API Access

Different use-cases for having/wishing to create a database of eBay listings may exist, bot this project only covers the authentication of an eBay seller, who is also the owner of the necessary eBay developer account.

### Database

eBay provides lots of categories with different mandatory/optional item specifics as well as several other differences depending on the country of publishing. Because of it's performance and unique features, MariaDB has been chosen as local database for the items' details along with a simple online hosted space to act as datapool for images and original responses. Like this, the code can be kept generalized, reusable and as simple as possible while minimizing the local storage.

### Testing

The testing framework for Codeception is used to ensure the quality and functionality of the codebase. A 'test' command has been added to the composer.json file, allowing easy execution.

</br>

## Getting Started

### Prerequisites

-   PHP 8.0 or higher
-   Composer
-   MariaDB 10 or higher
-   eBay Seller & Developer Account with corresponding Authentication Token

### Installation

1. Clone/Copy/Download this repository
1. Navigate to the project directory and update/install the dependencies with '`composer install`' in your terminal
1. Copy the `.env.example` file to a new file called `.env` and fill in your eBay API credentials
1. Edit the variables in the `config.php` file according to your requirements
1. Optional:  
   Install a cronjob to automatically update the database
    - Open your crontab using your teminal with '`crontab -e`'
    - Add a new line, e.g. like '`*/15 * * * * /usr/bin/php /path/to/your/main.php`',  
       where `/path/to/your/main.php` is the full path to the project's main.php on your system  
       and `/usr/bin/php` is the path, where your PHP is installed

### Run The Programm

-   #### Option A

    Run it manually:

    1. Navigate to the `scripts` folder in the project directory
    1. Open a terminal
    1. Run the program with '`php main.php`'

-   #### Option B

    Let it run scheduled using a cronjob to execute `main.php`,

    -   See description as optional step under [Installation](#installation)

</br>

## Further Reading

-   [PHP](https://www.php.net/) - The programming language used
-   [Composer](https://getcomposer.org/) - PHP Dependency Management Tool
-   [phpDocumentor](https://www.phpdoc.org/) - PHP Document Creation Tool
-   [DocBlock](https://docs.phpdoc.org/guide/guides/docblocks.html) - Special comment style to document code
-   [Mintlify](https://marketplace.visualstudio.com/items?itemName=mintlify.document) - VS Code Extension to assist in creating styled comments.
-   [MariaDB](https://mariadb.org/documentation/) - The database used
-   [eBay Features Guide](https://developer.ebay.com/DevZone/guides/features-guide/default.html#features-guide-landing.html?TocPath=_____1) - General guide for traditional APIs (using XML or SOAP instead of REST) like the Trading API
-   [Wikipedia: Cron](https://en.wikipedia.org/wiki/Cron) - Explanation and history of cronjobs
