## API Error Text Codes
To diagnose an unexpected or unhandled error, turn on the `APP_DEBUG` flag by setting
it to `true` in `.env`

`SERVER_ERROR`: A generic, unhandled error has occured.

`JSON_ONLY`: Only JSON-encoded data is available for this endpoint.

`MISSING_PARAMETERS`: Invalid or missing parameters.

`NOT_FOUND`: Object not found.

`ACCESS_DENIED`: User is not authorized to access the object.

`INVALID_ANALYTICS_TYPE`: Invalid analytics type requested.

`CREATION_ERROR`: An error occurred while creating the object.

`AUTH_ERROR`: An error occured while attempting to authenticate the user to the API.

`QUOTA_EXCEEDED`: User's API usage has exceeded alloted quota.

`ANALYTICS_ERROR`: Invalid bounds or unexpected error while fetching analytics data.
