# Polr API Documentation
-----------------------------

## API keys
To authenticate a user to Polr, you will need to provide an API key along with
each request to the Polr API, as a GET or POST parameter. (e.g `?key=API_KEY_HERE`)

## Assigning an API key
To assign an API key, log on from an administrator account, head over to the "Admin"
tab, and scroll to the desired user. From there, you can open the API button dropdown to
reset, create, or delete the user's API key. You will also be prompted to set a desired API quota. This is defined as requests per minute. You may allow unlimited requests by making the quota negative. Once the user receives an API key, they will be able to see an "API"
tab in their user panel, which provides the information necessary to interact with the API.

Alternative method: You can also assign a user an API key by editing their entry in the
`users` database table, editing the `api_key` value to the desired API key, `api_active` to the correct value (`1` for active, `0` for inactive), and `api_quota` to the desired API quota (see above).

## Actions
Actions are passed as a segment in the URL. There are currently
two actions implemented:

- `shorten` - shortens a URL
- `lookup` - looks up the destination of a shortened URL

Actions take arguments, which are passed as GET or POST parameters.
See [API endpoints](#api-endpoints) for more information on the actions.

## Response Type
The Polr API will reply in `plain_text` or `json`. The response type can be
set by providing the `response_type` argument to the request. If not provided,
the response type will default to `json`.

Example `json` responses:
```
{
    "action": "shorten",
    "result": "https://example.com/5kq"
}
```

```
{
    "action":"lookup",
    "result": {
        "long_url": "https:\/\/google.com",
        "created_at": {
            "date":"2016-02-12 15:20:34.000000",
            "timezone_type":3,
            "timezone":"UTC"
        },
        "clicks":"0"
    }
}
```

Example `plain_text` responses:

```https://example.com/5kq```

```https://google.com```

## API Endpoints
All API calls will commence with the base URL, `/api/v2/`.

### /api/v2/action/shorten
Arguments:

 - `url`: the URL to shorten (e.g `https://google.com`)
 - `is_secret` (optional): whether the URL should be a secret URL or not. Defaults to `false`. (e.g `true` or `false`)
 - `custom_ending` (optional): a custom ending for the short URL. If left empty, no custom ending will be assigned.


Response: A JSON or plain text representation of the shortened URL.

Example: GET `http://example.com/api/v2/action/shorten?key=API_KEY_HERE&url=https://google.com&custom_ending=CUSTOM_ENDING&is_secret=false`
Response:
```
{
    "action": "shorten",
    "result": "https://example.com/5kq"
}
```

Remember that the `url` argument must be URL encoded.

### /api/v2/action/lookup
The `lookup` action takes a single argument: `url_ending`. This is the URL to
lookup. If it exists, the API will return with the destination of that URL. If
it does not exist, the API will return with the status code 404 (Not Found).

Arguments:

 - `url_ending`: the link ending for the URL to look up. (e.g `5ga`)
 - `url_key` (optional): optional URL ending key for lookups against secret URLs

Remember that the `url` argument must be URL encoded.

Example: GET `http://example.com/api/v2/action/lookup?key=API_KEY_HERE&ending=2`
Response:
```
{
    "action": "lookup",
    "result": "https://google.com"
}
```

## HTTP Error Codes
The API will return an error code if your request was malformed or another error occured while processing your request.

### HTTP 400 Bad Request
This status code is returned in the following circumstances:

- By the `shorten` endpoint
    - In the event that the custom ending provided is already in use, a `400` error code will be returned and the message `custom ending already in use` will be returned as an error.
- By any endpoint
    - Your request will return a `400` if it is malformed or the contents of your arguments do not fit the required data type.

### HTTP 500 Internal Server Error

- By any endpoint
    - The server has encountered an unhandled error. This is most likely due to a problem with your configuration or your server is unable to handle the request due to a bug.

### HTTP 401 Unauthorized

 - By any endpoint
    - You are unauthorized to make the transaction. This is most likely due to an API token mismatch, or your API token has not be set to active.
 - By the `lookup` endpoint
    - You have not provided the valid `url_key` for a secret URL lookup.


### HTTP 404 Not Found

- By the `lookup` endpoint

    - Returned in the circumstance that the short URL to look up was not found in the database.

### HTTP 403 Forbidden

- By the `shorten` endpoint
    -  Your request was understood, but you have exceeded your quota.

## Error Responses
Example `json` error response:
```
{
    "error": "custom ending already in use"
}
```

Example `plain_text` error response:

`custom ending already in use`
