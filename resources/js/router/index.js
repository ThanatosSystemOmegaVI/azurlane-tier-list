import { createWebHistory, createRouter } from "vue-router";

import index from '@/components/index.vue';
let authenticated = sessionStorage.email !== undefined && sessionStorage.email !== null && sessionStorage.email !== "" && sessionStorage.email.length > 0 ? true : false;
export const routes = [
	{
		name: 'index',
		path: '/',
		component: index
	},
	{
		name: 'login',
		path: '/login',
		component: () => import('@/components/login.vue')
	},
	{
		name: 'ships',
		path: '/ships',
		component: () => import('@/components/ships.vue'),
		meta: { authRequired: true },
	},
	{
		name: '404',
		path: "/:pathMatch(.*)*",
		component: index
	}
];

const router = createRouter({
	history: createWebHistory(),
	routes: routes,
});

router.beforeEach((to, from, next) => {
	if (to.authRequired) {
		if (authenticated) {
			next();
		} else {
			next('/login');
		}
	} else {
		next();
	}
});

export default router;