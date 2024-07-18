# Setup

1. clone the repo
2. run `composer install`

# Running the code

` > php app.php input.txt`

# Running the tests

`> vendor/bin/phpunit UnitTests.php`

# BinResults Response Example

```
{
   "number": {},
   "scheme": "visa",
   "type": "debit",
   "brand": "Visa Classic",
   "country": {
      "numeric": "208",
      "alpha2": "DK",
      "name": "Denmark",
      "emoji": "🇩🇰",
      "currency": "DKK",
      "latitude": 56,
      "longitude": 10
   },
   "bank": {
      "name": "Jyske Bank A/S"
   }
}

```

# Rates response Example

```
{
   "success": true,
   "timestamp": 1721311444,
   "base": "EUR",
   "date": "2024-07-18",
   "rates": {
      "AED": 4.006491,
      "AFN": 77.187366,
      ...
      "ZWL": 351.232876
   }
}
```
