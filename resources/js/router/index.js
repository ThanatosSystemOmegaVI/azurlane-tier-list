import { createWebHistory, createRouter } from "vue-router";

import Home from '@/components/index.vue';
export const routes = [
	{
		name: 'home',
		path: '/',
		component: Home
	},
	{
		name: 'login',
		path: '/login',
		component: () => import('@/components/login.vue')
	},
	{
		name: '404',
		path: "/:pathMatch(.*)*",
		component: Home
	}
];

const router = createRouter({
	history: createWebHistory(),
	routes: routes,
});

export default router;