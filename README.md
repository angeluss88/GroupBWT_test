# Running the code

Assuming PHP code is in `app.php`, you could run it by this command, output might be different due to dynamic data:

```
> php app.php input.txt
1
0.46180844185832
1.6574127786525
2.4014038976632
43.714413735069

```

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

# Notes about this code

1. Idea is to calculate commissions for already made transactions;
2. Transactions are provided each in it's own line in the input file, in JSON;
3. BIN number represents first digits of credit card number. They can be used to resolve country where the card was issued;
4. We apply different commission rates for EU-issued and non-EU-issued cards;
5. We calculate all commissions in EUR currency.

# Requirements for your code

1. It **must** have unit tests. If you haven't written any previously, please take the time to learn it before making the task, you'll thank us later.
   1. Unit tests must test the actual results and still pass even when the response from remote services change (this is quite normal, exchange rates change every day). This is best accomplished by using mocking.
1. As an improvement, add ceiling of commissions by cents. For example, `0.46180...` should become `0.47`.
1. It should give the same result as original code in case there are no failures, except for the additional ceiling.
1. Code should be extendible – we should not need to change existing, already tested functionality to accomplish the following:
   1. Switch our currency rates provider (different URL, different response format and structure, possibly some authentication);
   2. Switch our BIN provider (different URL, different response format and structure, possibly some authentication);
   3. Just to note – no need to implement anything additional. Just structure your code so that we could implement that later on without braking our tests;
1. It should look as you'd write it yourself in production – consistent, readable, structured. Anything we'll find in the code, we'll treat as if you'd write it yourself. Basically it's better to just look at the existing code and re-write it from scratch. For example, if `'yes'`/`'no'`, ugly parsing code or `die` statements are left in the solution, we'd treat it as an instant red flag.
1. Use composer to install testing framework and any needed dependencies you'd like to use, also for enabling autoloading.

# Task Submission

Before submitting your task, please review the requirements once again – **all of them must be accomplished**.

You can upload the source (i.e. to GitHub) publicly.
