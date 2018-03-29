APP_ENV=production

# Set to true if debugging
APP_DEBUG=false

# 32-character key (e.g 3EWBLwxTfh%*f&xRBqdGEIUVvn4%$Hfi)
APP_KEY="{{{$APP_KEY}}}"

# Your app's name (shown on interface)
APP_NAME="{{$APP_NAME}}"

# Protocol to access your app. e.g https://
APP_PROTOCOL="{{$APP_PROTOCOL}}"

# Your app's external address (e.g example.com)
APP_ADDRESS="{{$APP_ADDRESS}}"

# Your app's bootstrap stylesheet
# e.g https://maxcdn.bootstrapcdn.com/bootswatch/3.3.5/flatly/bootstrap.min.css
APP_STYLESHEET="{{$APP_STYLESHEET}}"

# Set to today's date (e.g November 3, 2015)
POLR_GENERATED_AT="{{$POLR_GENERATED_AT}}"

# Set to true after running setup script
# e.g true
POLR_SETUP_RAN={{$POLR_SETUP_RAN}}

DB_CONNECTION=mysql
# Set to your DB host (e.g localhost)
DB_HOST="{{{$DB_HOST}}}"
# DB port (e.g 3306)
DB_PORT={{$DB_PORT}}
# Set to your DB name (e.g polr)
DB_DATABASE="{{{$DB_DATABASE}}}"
# DB credentials
# e.g root
DB_USERNAME="{{{$DB_USERNAME}}}"
DB_PASSWORD="{{{$DB_PASSWORD}}}"

# Polr Settings

# Set to true to show an interface to logged off users
# If false, set the SETTING_INDEX_REDIRECT
# You may login by heading to /login if the public interface is off
SETTING_PUBLIC_INTERFACE={{$ST_PUBLIC_INTERFACE}}

# Set to true to allow signups, false to disable (e.g true/false)
POLR_ALLOW_ACCT_CREATION={{$POLR_ALLOW_ACCT_CREATION}}

# Set to true to require activation by email (e.g true/false)
POLR_ACCT_ACTIVATION={{$POLR_ACCT_ACTIVATION}}

# Set to true to require reCAPTCHAs on sign up pages
# If this setting is enabled, you must also provide your reCAPTCHA keys
# in POLR_RECAPTCHA_SITE_KEY and POLR_RECAPTCHA_SECRET_KEY
POLR_ACCT_CREATION_RECAPTCHA={{$POLR_ACCT_CREATION_RECAPTCHA}}

# Set to true to require users to be logged in before shortening URLs
SETTING_SHORTEN_PERMISSION={{$ST_SHORTEN_PERMISSION}}

# You must set SETTING_INDEX_REDIRECT if SETTING_PUBLIC_INTERFACE is false
# Polr will redirect logged off users to this URL
SETTING_INDEX_REDIRECT={{$ST_INDEX_REDIRECT}}

# Set to true if you wish to redirect 404s to SETTING_INDEX_REDIRECT
# Otherwise, an error message will be shown
SETTING_REDIRECT_404={{$ST_REDIRECT_404}}

# Set to true to enable password recovery
SETTING_PASSWORD_RECOV={{$ST_PASSWORD_RECOV}}

# Set to true to generate API keys for each user on registration
SETTING_AUTO_API={{$ST_AUTO_API}}

# Set to true to allow anonymous API access
SETTING_ANON_API={{$ST_ANON_API}}

# Set the anonymous API quota per IP
SETTING_ANON_API_QUOTA={{$ST_ANON_API_QUOTA}}

# Set to true to use pseudorandom strings rather than using a counter by default
SETTING_PSEUDORANDOM_ENDING={{$ST_PSEUDOR_ENDING}}

# Set to true to record advanced analytics
SETTING_ADV_ANALYTICS={{$ST_ADV_ANALYTICS}}

# Set to true to restrict registration to a specific email domain
SETTING_RESTRICT_EMAIL_DOMAIN={{$ST_RESTRICT_EMAIL_DOMAIN}}

# A comma-separated list of permitted email domains
SETTING_ALLOWED_EMAIL_DOMAINS="{{$ST_ALLOWED_EMAIL_DOMAINS}}"

# reCAPTCHA site key
POLR_RECAPTCHA_SITE_KEY="{{$POLR_RECAPTCHA_SITE_KEY}}"

# reCAPTCHA secret key
POLR_RECAPTCHA_SECRET_KEY="{{$POLR_RECAPTCHA_SECRET}}"

# Set each to blank to disable mail
@if($MAIL_ENABLED)
MAIL_DRIVER=smtp
# e.g mailtrap.io
MAIL_HOST="{{$MAIL_HOST}}"
# e.g 2525
MAIL_PORT="{{$MAIL_PORT}}"
MAIL_USERNAME="{{$MAIL_USERNAME}}"
MAIL_PASSWORD="{{{$MAIL_PASSWORD}}}"
# e.g noreply@example.com
MAIL_FROM_ADDRESS="{{$MAIL_FROM_ADDRESS}}"
MAIL_FROM_NAME="{{$MAIL_FROM_NAME}}"
@endif

APP_LOCALE=en
APP_FALLBACK_LOCALE=en

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=database

_API_KEY_LENGTH=15
_ANALYTICS_MAX_DAYS_DIFF=365
_PSEUDO_RANDOM_KEY_LENGTH=5

# FILESYSTEM_DRIVER=local
# FILESYSTEM_CLOUD=s3

# S3_KEY=null
# S3_SECRET=null
# S3_REGION=null
# S3_BUCKET=null

# RACKSPACE_USERNAME=null
# RACKSPACE_KEY=null
# RACKSPACE_CONTAINER=null
# RACKSPACE_REGION=null

# Set to 32 or 62 -- do not touch after initial configuration
POLR_BASE={{$ST_BASE}}

# Do not touch
POLR_RELDATE="{{env('VERSION_RELMONTH')}} {{env('VERSION_RELDAY')}}, {{env('VERSION_RELYEAR')}}"
POLR_VERSION="{{env('VERSION')}}"
POLR_SECRET_BYTES=2

TMP_SETUP_AUTH_KEY="{{$TMP_SETUP_AUTH_KEY}}"
