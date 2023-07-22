<script setup>
import Cookies from 'js-cookie'
import ApiService from '@pigment/api-service'
import AppMenuItem from '@/components/PrimeVue/AppMenuItem.vue'

const menu = ref([
	{
		items: [
			{ label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/app' },
			{
				label: 'Submissions',
				icon: 'pi pi-fw pi-clone',
				to: '/app/submissions',
			},
			{
				label: 'Appointments',
				icon: 'pi pi-fw pi-list',
				to: '/app/appointments',
			},
			{
				label: 'Clients',
				icon: 'pi pi-fw pi-id-card',
				to: '/app/clients',
			},
		],
	},
	// {
	//   label: 'UI Components',
	//   items: [
	//     {
	//       label: 'Form Layout',
	//       icon: 'pi pi-fw pi-id-card',
	//       to: '/uikit/formlayout'
	//     },
	//     { label: 'Input', icon: 'pi pi-fw pi-check-square', to: '/uikit/input' },
	//     {
	//       label: 'Float Label',
	//       icon: 'pi pi-fw pi-bookmark',
	//       to: '/uikit/floatlabel'
	//     },
	//     {
	//       label: 'Invalid State',
	//       icon: 'pi pi-fw pi-exclamation-circle',
	//       to: '/uikit/invalidstate'
	//     },
	//     {
	//       label: 'Button',
	//       icon: 'pi pi-fw pi-mobile',
	//       to: '/uikit/button',
	//       class: 'rotated-icon'
	//     },
	//     { label: 'Table', icon: 'pi pi-fw pi-table', to: '/uikit/table' },
	//     { label: 'List', icon: 'pi pi-fw pi-list', to: '/uikit/list' },
	//     { label: 'Tree', icon: 'pi pi-fw pi-share-alt', to: '/uikit/tree' },
	//     { label: 'Panel', icon: 'pi pi-fw pi-tablet', to: '/uikit/panels' },
	//     { label: 'Overlay', icon: 'pi pi-fw pi-clone', to: '/uikit/overlay' },
	//     { label: 'Media', icon: 'pi pi-fw pi-image', to: '/uikit/media' },
	//     {
	//       label: 'Menu',
	//       icon: 'pi pi-fw pi-bars',
	//       to: '/uikit/menu',
	//       preventExact: true
	//     },
	//     { label: 'Message', icon: 'pi pi-fw pi-comment', to: '/uikit/messages' },
	//     { label: 'File', icon: 'pi pi-fw pi-file', to: '/uikit/file' },
	//     { label: 'Chart', icon: 'pi pi-fw pi-chart-bar', to: '/uikit/chart' },
	//     { label: 'Misc', icon: 'pi pi-fw pi-circle', to: '/uikit/misc' }
	//   ]
	// },
	// {
	//   label: 'Prime Blocks',
	//   items: [
	//     {
	//       label: 'Free Blocks',
	//       icon: 'pi pi-fw pi-eye',
	//       to: '/blocks',
	//       badge: 'NEW'
	//     },
	//     {
	//       label: 'All Blocks',
	//       icon: 'pi pi-fw pi-globe',
	//       url: 'https://www.primefaces.org/primeblocks-vue',
	//       target: '_blank'
	//     }
	//   ]
	// },
	// {
	//   label: 'Utilities',
	//   items: [
	//     {
	//       label: 'PrimeIcons',
	//       icon: 'pi pi-fw pi-prime',
	//       to: '/utilities/icons'
	//     },
	//     {
	//       label: 'PrimeFlex',
	//       icon: 'pi pi-fw pi-desktop',
	//       url: 'https://www.primefaces.org/primeflex/',
	//       target: '_blank'
	//     }
	//   ]
	// },
	// {
	//   label: 'Pages',
	//   icon: 'pi pi-fw pi-briefcase',
	//   to: '/pages',
	//   items: [
	//     {
	//       label: 'Landing',
	//       icon: 'pi pi-fw pi-globe',
	//       to: '/landing'
	//     },
	//     {
	//       label: 'Auth',
	//       icon: 'pi pi-fw pi-user',
	//       items: [
	//         {
	//           label: 'Login',
	//           icon: 'pi pi-fw pi-sign-in',
	//           to: '/auth/login'
	//         },
	//         {
	//           label: 'Error',
	//           icon: 'pi pi-fw pi-times-circle',
	//           to: '/auth/error'
	//         },
	//         {
	//           label: 'Access Denied',
	//           icon: 'pi pi-fw pi-lock',
	//           to: '/auth/access'
	//         }
	//       ]
	//     },
	//     {
	//       label: 'Crud',
	//       icon: 'pi pi-fw pi-pencil',
	//       to: '/crud'
	//     },
	//     {
	//       label: 'Timeline',
	//       icon: 'pi pi-fw pi-calendar',
	//       to: '/timeline'
	//     },
	//     {
	//       label: 'Not Found',
	//       icon: 'pi pi-fw pi-exclamation-circle',
	//       to: '/notfound'
	//     },
	//     {
	//       label: 'Empty',
	//       icon: 'pi pi-fw pi-circle-off',
	//       to: '/empty'
	//     }
	//   ]
	// },
	// {
	//   label: 'Hierarchy',
	//   items: [
	//     {
	//       label: 'Submenu 1',
	//       icon: 'pi pi-fw pi-bookmark',
	//       items: [
	//         {
	//           label: 'Submenu 1.1',
	//           icon: 'pi pi-fw pi-bookmark',
	//           items: [
	//             { label: 'Submenu 1.1.1', icon: 'pi pi-fw pi-bookmark' },
	//             { label: 'Submenu 1.1.2', icon: 'pi pi-fw pi-bookmark' },
	//             { label: 'Submenu 1.1.3', icon: 'pi pi-fw pi-bookmark' }
	//           ]
	//         },
	//         {
	//           label: 'Submenu 1.2',
	//           icon: 'pi pi-fw pi-bookmark',
	//           items: [{ label: 'Submenu 1.2.1', icon: 'pi pi-fw pi-bookmark' }]
	//         }
	//       ]
	//     },
	//     {
	//       label: 'Submenu 2',
	//       icon: 'pi pi-fw pi-bookmark',
	//       items: [
	//         {
	//           label: 'Submenu 2.1',
	//           icon: 'pi pi-fw pi-bookmark',
	//           items: [
	//             { label: 'Submenu 2.1.1', icon: 'pi pi-fw pi-bookmark' },
	//             { label: 'Submenu 2.1.2', icon: 'pi pi-fw pi-bookmark' }
	//           ]
	//         },
	//         {
	//           label: 'Submenu 2.2',
	//           icon: 'pi pi-fw pi-bookmark',
	//           items: [{ label: 'Submenu 2.2.1', icon: 'pi pi-fw pi-bookmark' }]
	//         }
	//       ]
	//     }
	//   ]
	// },
	// {
	//   label: 'Get Started',
	//   items: [
	//     {
	//       label: 'Documentation',
	//       icon: 'pi pi-fw pi-question',
	//       to: '/documentation'
	//     },
	//     {
	//       label: 'View Source',
	//       icon: 'pi pi-fw pi-search',
	//       url: 'https://github.com/primefaces/sakai-nuxt',
	//       target: '_blank'
	//     }
	//   ]
	// }
])

const handleLogout = async () => {
	const res = await ApiService.auth.logout()
	Cookies.remove('authStore')

	return navigateTo('/', { external: true })
}
</script>

<template>
	<div class="h-screen sticky top-0">
		<div
			class="flex flex-col items-center justify-between md:py-4 md:px-4 h-full flex-shrink-0 md:w-[240px] bg-indigo-800 px-1"
		>
			<ul class="layout-menu self-start">
				<template v-for="(item, i) in menu" :key="item">
					<AppMenuItem v-if="!item.separator" :item="item" :index="i" />
					<li v-if="item.separator" class="menu-separator" />
				</template>
			</ul>

			<ul class="layout-menu self-start">
				<li class="layout-root-menuitem text-gray-200 pl-2 text-sm">
					<button
						@click="handleLogout"
						class="layout-menuitem-text hidden md:block"
					>
						Logout
					</button>
					<button
						@click="handleLogout"
						class="layout-menuitem-text block md:hidden"
					>
						X
					</button>
				</li>
			</ul>
		</div>
	</div>
</template>

<style lang="scss" scoped></style>
