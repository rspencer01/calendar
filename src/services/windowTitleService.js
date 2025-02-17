/**
 * @copyright Copyright (c) 2019 Georg Ehrke
 *
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
import dateRangeFormat from '../filters/dateRangeFormat.js'
import { getDateFromFirstdayParam } from '../utils/date.js'

const originalWindowTitle = document.title

/**
 * This function listens to the router and
 * automatically adjusts the title of the window
 *
 * @param {VueRouter} router The VueJS Router instance
 */
export default function(router) {
	router.beforeEach((to, from, next) => {
		if (!to.params.firstDay) {
			next()
			return
		}

		const date = getDateFromFirstdayParam(to.params.firstDay)
		const currentView = to.params.view

		const title = dateRangeFormat(date, currentView, 'en')
		document.title = [
			title,
			originalWindowTitle,
		].join(' - ')

		next()
	})
}
