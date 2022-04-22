# Obtaining a MaxMind GeoIP License Key
-----------------

If you would like to use "advanced analytics" in order to get more insights on your users, you must input a MaxMind GeoIP license key during the setup process or add it later to your configuration file (`.env`).

To obtain a license key, create an account on [MaxMind's website](https://www.maxmind.com/en/geolite2/signup). After you create your account and confirm your email, go to your [MaxMind account page](https://www.maxmind.com/en/accounts/current/license-key) to generate a license key for use with Polr. If you are asked whether your license key will be used for "GeoIP Update", answer no.

Copy your newly generated license key and input it into Polr. 

## Troubleshooting

### I'm having trouble running `php artisan geoip:update`, and I'm using an older version of Polr

If you are on an older version of Polr, your installation may be updated. Update Polr by running `git pull` and then add a new entry to your `.env` configuration file:

```
MAXMIND_LICENSE_KEY="LICENSE_KEY_GOES_HERE"
```

Then, run `php artisan config:cache` and `php artisan geoip:update` again.