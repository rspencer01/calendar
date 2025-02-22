/**
 * @copyright Copyright (c) 2019 Georg Ehrke
 * @author Georg Ehrke <oc.list@georgehrke.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

import { getConfigValueFromHiddenInput } from './settings.js'

/**
 * Gets the initial view
 *
 * @returns {String}
 */
export function getInitialView() {
	return getConfigValueFromHiddenInput('initial-view') || 'dayGridMonth'
}

/**
 * Prefixes a desired route name based on the current route
 *
 * @param {String} currentRouteName The name of the current route
 * @param {String} toRouteName The name of the desired route
 * @returns {String}
 */
export function getPrefixedRoute(currentRouteName, toRouteName) {
	if (currentRouteName.startsWith('Embed')) {
		return 'Embed' + toRouteName
	}

	if (currentRouteName.startsWith('Public')) {
		return 'Public' + toRouteName
	}

	return toRouteName
}
