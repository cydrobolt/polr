# API Documentation

## API Endpoints
All API calls should be directed to `/api.php`.

## API keys
You will need to request an API key from a developer in order to use the Polr
API. Once you have one, send it as the GET or POST variable `apikey`. This is
required for every API call. If the API key is not sent, or is invalid, the API
will return with the status code 401 (Unauthorized).

## Actions
Actions are passed in the GET or POST variable `action`. There are currently
two actions implemented:

- `shorten` - shortens a URL
- `lookup` - looks up the destination of a shortened URL

Actions take arguments, which are passed as GET or POST parameters.

### `shorten`
The `shorten` action takes a single argument: `url`. This is the URL to
shorten. The API will return with a plain text response containing a
shortened URL.

Example: GET `http://polr.cf/api.php?apikey=hunter2&action=shorten&url=google.com`

Remember that the `url` argument must be urlencoded (unless it is passed as a
POST parameter).

### `lookup`
The `lookup` action takes a single argument: `url`. This is the URL to
lookup. If it exists, the API will return with the destination of that URL. If
it does not exist, the API will return with the status code 404 (Not Found).

Example: GET `http://polr.cf/api.php?apikey=hunter2&action=lookup&url=http://polr.cf/3`

Remember that the `url` argument must be urlencoded (unless it is passed as a
POST parameter).
