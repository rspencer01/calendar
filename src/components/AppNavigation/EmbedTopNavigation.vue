<template>
	<header id="embed-header" role="banner">
		<div class="embed-header__date-section">
			<AppNavigationHeaderDatePicker />
			<AppNavigationHeaderTodayButton />
		</div>
		<div class="embed-header__views-section">
			<AppNavigationHeaderViewButtons />
		</div>
		<!-- TODO have one button per calendar -->
		<div class="embed-header__share-section">
			<Actions default-icon="icon-download">
				<ActionLink
					v-for="calendar in subscriptions"
					:key="calendar.id"
					icon="icon-download"
					target="_blank"
					:href="calendar.url + '?export'">
					{{ $t('calendar', 'Download {name}', { name: calendar.displayName || $t('calendar', 'Untitled calendar') }) }}
				</ActionLink>
			</Actions>
			<Actions default-icon="icon-calendar-dark">
				<ActionButton
					v-for="calendar in subscriptions"
					:key="calendar.id"
					icon="icon-calendar-dark"
					@click.prevent.stop="copySubscriptionLink(calendar)">
					{{ $t('calendar', 'Subscribe to {name}', { name: calendar.displayName || $t('calendar', 'Untitled calendar') }) }}
				</ActionButton>
			</Actions>
		</div>
	</header>
</template>

<script>
import { Actions } from '@nextcloud/vue/dist/Components/Actions'
import { ActionButton } from '@nextcloud/vue/dist/Components/ActionButton'
import { ActionLink } from '@nextcloud/vue/dist/Components/ActionLink'
import {
	mapGetters,
} from 'vuex'
import { generateRemoteUrl } from '@nextcloud/router'

import AppNavigationHeaderDatePicker from './AppNavigationHeader/AppNavigationHeaderDatePicker.vue'
import AppNavigationHeaderTodayButton from './EmbedHeader/EmbedHeaderTodayButton.vue'
import AppNavigationHeaderViewButtons from './EmbedHeader/EmbedHeaderViewButtons.vue'

export default {
	name: 'EmbedTopNavigation',
	components: {
		AppNavigationHeaderDatePicker,
		AppNavigationHeaderTodayButton,
		AppNavigationHeaderViewButtons,
		Actions,
		ActionButton,
		ActionLink,
	},
	computed: {
		...mapGetters({
			subscriptions: 'sortedSubscriptions',
		}),
	},
	methods: {
		async copySubscriptionLink(calendar) {
			const rootURL = generateRemoteUrl('dav')
			const url = new URL(calendar.url + '?export', rootURL)

			if (url.protocol === 'http:') {
				url.protocol = 'webcal:'
			}
			if (url.protocol === 'https:') {
				url.protocol = 'webcals:'
			}

			// copy link for calendar to clipboard
			try {
				await this.$copyText(url)
				this.$toast.success(this.$t('calendar', 'Calendar link copied to clipboard.'))
			} catch (error) {
				console.debug(error)
				this.$toast.error(this.$t('calendar', 'Calendar link could not be copied to clipboard.'))
			}
		},
	},
}
</script>
