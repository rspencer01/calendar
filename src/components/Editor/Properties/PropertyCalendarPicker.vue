<!--
  - @copyright Copyright (c) 2019 Georg Ehrke <oc.list@georgehrke.com>
  -
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
	<div v-if="display" class="property-select">
		<div
			class="property-select__icon icon-calendar-dark"
			:title="$t('calendar', 'Calendar')" />

		<div
			class="property-select__input"
			:class="{ 'property-select__input--readonly-calendar-picker': isReadOnly }">
			<CalendarPicker
				v-if="!isReadOnly"
				:calendar="calendar"
				:calendars="calendars"
				:show-calendar-on-select="true"
				@selectCalendar="selectCalendar" />

			<CalendarPickerOption
				v-else
				:color="calendar.color"
				:display-name="calendar.displayName"
				:is-shared-with-me="calendar.isSharedWithMe"
				:owner="calendar.owner" />
		</div>

		<div
			v-if="showCustomColor"
			class="property-select__info">
			<div style="height: 20px;width:20px;border-radius: 3px" :style="{ 'background-color': selectedColor }" @click="openColorPicker" />
			<ColorPicker :value="selectedColor" :open.sync="isColorPickerOpen" @input="changeCustomColor" />
		</div>
	</div>
</template>

<script>
import CalendarPicker from '../../Shared/CalendarPicker'
import CalendarPickerOption from '../../Shared/CalendarPickerOption.vue'
import { ColorPicker } from '@nextcloud/vue/dist/Components/ColorPicker'

export default {
	name: 'PropertyCalendarPicker',
	components: {
		CalendarPickerOption,
		CalendarPicker,
		ColorPicker,
	},
	props: {
		calendar: {
			type: Object,
			default: undefined,
		},
		calendars: {
			type: Array,
			required: true,
		},
		isReadOnly: {
			type: Boolean,
			required: true,
		},
		showCustomColor: {
			type: Boolean,
			default: false,
		},
		customColor: {
			type: String,
			default: null,
		},
	},
	data() {
		return {
			isColorPickerOpen: false
		}
	},
	computed: {
		display() {
			return this.calendar !== undefined
		},
		selectedColor() {
			return this.customColor || this.calendar.color
		},
	},
	methods: {
		/**
		 * Emits the select calendar event
		 *
		 * // TODO: this should emit the calendar id instead
		 * @param {Object} value The calendar Object
		 */
		selectCalendar(value) {
			this.$emit('selectCalendar', value)
		},
		/**
		 * Emits the new custom color
		 *
		 * @param {String} value The new custom color
		 */
		changeCustomColor(value) {
			this.$emit('changeCustomColor', value)
		},
		/**
		 * Opens the color-picker to allow the user
		 * to pick a custom colors
		 */
		openColorPicker() {
			this.isColorPickerOpen = true
		}
	},
}
</script>
