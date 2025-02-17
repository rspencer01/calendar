<!--
  - @copyright Copyright (c) 2019 Georg Ehrke <oc.list@georgehrke.com>
  - @author Georg Ehrke <oc.list@georgehrke.com>
  -
  - @license GNU AGPL version 3 or any later version
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program. If not, see <http://www.gnu.org/licenses/>.
  -
  -->

<template>
	<transition-group v-if="!isPublic"
		id="calendars-list"
		name="list"
		tag="ul">
		<AppNavigationSpacer :key="spacerKey" />
		<CalendarListItemLoadingPlaceholder v-if="loadingCalendars" :key="loadingKeyCalendars" />
		<CalendarListItem
			v-for="calendar in allCalendars"
			:key="calendar.id"
			:calendar="calendar" />
		<CalendarListNew
			v-if="!loadingCalendars"
			:key="newCalendarKey"
			:disabled="loadingCalendars" />
	</transition-group>
	<transition-group v-else
		id="calendars-list"
		name="list"
		tag="ul">
		<CalendarListItemLoadingPlaceholder v-if="loadingCalendars" :key="loadingKeyCalendars" />
		<PublicCalendarListItem
			v-for="calendar in subscriptions"
			:key="calendar.id"
			:calendar="calendar" />
	</transition-group>
</template>

<script>
import {
	mapGetters,
} from 'vuex'
import { AppNavigationSpacer } from '@nextcloud/vue/dist/Components/AppNavigationSpacer'
import CalendarListNew from './CalendarList/CalendarListNew.vue'
import CalendarListItem from './CalendarList/CalendarListItem.vue'
import PublicCalendarListItem from './CalendarList/PublicCalendarListItem.vue'
import CalendarListItemLoadingPlaceholder from './CalendarList/CalendarListItemLoadingPlaceholder.vue'

export default {
	name: 'CalendarList',
	components: {
		AppNavigationSpacer,
		CalendarListNew,
		CalendarListItem,
		CalendarListItemLoadingPlaceholder,
		PublicCalendarListItem,
	},
	props: {
		isPublic: {
			type: Boolean,
			required: true,
		},
		loadingCalendars: {
			type: Boolean,
			default: false,
		},
	},
	computed: {
		...mapGetters({
			allCalendars: 'sortedCalendarsSubscriptions',
			subscriptions: 'sortedSubscriptions',
		}),
		newCalendarKey() {
			return this._uid + '-new-calendar'
		},
		loadingKeyCalendars() {
			return this._uid + '-loading-placeholder-calendars'
		},
		spacerKey() {
			return this._uid + '-spacer'
		},
	},
}
</script>
