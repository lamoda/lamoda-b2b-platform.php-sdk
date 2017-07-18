PHP SDK for interacting with Lamoda B2B Platform
================================================

## Software
1. PHP7
2. Guzzle
3. Monolog
4. Doctrine

## Installation

Add requirements to your project

``` composer require lamoda/lamoda-b2b-platform.php-sdk ``` 

## API
| Protocol |         Location        | Methods |
|----------|-------------------------|----------
|   JSON   | /auth/token             |   GET   |
|   JSON   | /api/v1/orders          |   POST  |
|   JSON   | /api/v1/shipments/out   |   POST  |

## Auth config
```
[
    'PC' => [
        'client_id'     => '1_500',
        'client_secret' => '123456789'
    ]
]
```