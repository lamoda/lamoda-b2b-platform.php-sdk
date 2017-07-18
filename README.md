PHP SDK for interacting with Lamoda B2B Platform
================================================

## Software
1. PHP >7.0
2. Guzzle Http >6.0.2
3. Monolog
4. Lamoda B2B Platform DTOs
5. Doctrine

## Installation

* Add to composer.json:
```
    "repositories": {
        "leos.partner.php-sdk": {
            "type": "vcs",
            "url": "git@github.com:pavelsavelyev/lamoda-b2b-platform.php-sdk.git"
        }
    }
```
* Add requirements to your project: 
``` composer require kupishoes/leos.partner.php-sdk ``` 

## API
| Protocol |         Location        | Methods |
|----------|-------------------------|----------
|   JSON   | /auth/token             |   GET   |
|   JSON   | /api/v1/orders          |   POST  |
|   JSON   | /api/v1/shipments/out   |   POST  |

## Examples

### Auth config
```
[
    'PC' => [
        'client_id'     => '1_500',
        'client_secret' => '123456789'
    ]
]
```