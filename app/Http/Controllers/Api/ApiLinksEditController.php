<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\Link;

class ApiLinksEditController extends ApiController
{
	public static function listLinks(Request $request) {
		/**
		 * List all (shortened) links created by the authenticated user
		 *
		 * @param Request $request
		 * @return mixed
		 */
		//
		// Initialize incoming parameters
		//
		$response_type = $request->input('response_type', 'json');

		// Initialize user
		$user = self::getApiUserInfo($request);

		//
		// Fetch valid links belonging to the same user
		//
		$links = Link::where('creator', $user->username)->get();

		$responseLinks = [];
		foreach ($links as $link) {
			$responseLinks[] = [
				'short_url' => $link['short_url'],
				'long_url' => $link['long_url'],
				'is_disabled' => ($link['is_disabled'] == 1),
				'clicks' => (int) $link['clicks'],
				'updated_at' => $link['updated_at'],
				'created_at' => $link['created_at']
			];
		}

		if (!empty($responseLinks)) {
			return self::encodeResponse($responseLinks, 'listLinks', $response_type, $link['long_url']);
		} else {
			abort(404, "No links found.");
		}
	}

	public static function updateLinks(Request $request) {
		/**
		 * Update links attributes (for links that belong to the authenticated user)
		 *
		 * @param Request $request
		 * @return mixed
		 */
		//
		// Initialize incoming parameters
		//
		$response_type = $request->input('response_type', 'json');
		$content = $request->getContent();
		$links = json_decode($content, true);

		// Initialize user
		$user = self::getApiUserInfo($request);

		//
		// Validation
		//
		// Check if JSON decoding went as expected.
		if (json_last_error() != JSON_ERROR_NONE) {
			abort(400, 'Input JSON error:' . json_last_error_msg());
		}
		if (!self::checkRequiredArgs([$links])) {
			abort(400, "Missing required arguments.");
		}
		if (!is_array($links)) {
			abort(400, "Invalid links-JSON provided");
		}

		foreach ($links as $link) {
			//
			// Validate incoming link
			//
			if (empty($link['short_url'])) {
				abort(400, "Missing required link attribute: short_url.");
			}

			if (empty($link['long_url'])
				&& empty($link['is_disabled'])
			) {
				abort(400, "Missing required link attributes: long_url OR is_disabled.");
			}

			//
			// Update link DB record
			//
			Link::where('creator', $user->username)
				->where('short_url', $link['short_url'])
				->update([
					'long_url' => $link['long_url'],
					'is_disabled' => $link['is_disabled']
				]);
		}

		return self::encodeResponse(true, 'updateLinks', $response_type, 'ok');
	}
}