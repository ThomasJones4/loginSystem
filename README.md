# loginSystem
**Version 1.0**

Simple PHP login system that uses PHP, MySQL (PDO), JavaScript and UIKit. 


### Prerequisites

  - `PHP` *_required_*
	- Minimum version: `7.0`
	- `pdo_mysql` extension required
	- Recommended to enable `shell_exec`

  - `MySQL` *_required_*
	- Version `5.6+` recommended
	
  - `Composer` *_required_*
	- Version `1.1+` recommended
  
## Built With

* [UIKit](https://getuikit.com/) - Front-end framework used for web interfaces.

##### Composer Components used

- `PHP-Mailer`
	- Version `~6.0`

Installation
------------

#### Clone the Repository
	$ git clone https://github.com/ThomasJones4/loginSystem.git
	
#### Install Composer dependencies
	$ composer install
	
#### Install to existing PHP files
 - add php files to root directory
 - add `include_once('includes/header.php');` to the top line of all pages to be secured
 - add `include_once('includes/footer.php');` to the last line of pages to be secured
	
#### Configure config.php
 - add database details
 - define loginSuccessPage

#### Configure database for users
 - An existing database can be used or use sql/database.sql for database and table

## Authors

* **Thomas Jones** - [ThomasJones4](https://github.com/ThomasJones4)

