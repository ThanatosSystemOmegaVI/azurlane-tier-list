import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
	plugins: [
		vue(),
		laravel({
			input: ['resources/css/app.css', 'resources/js/app.js'],
			refresh: true,
		}),
	],
  	// server:{ 
    // 	origin:"5.254.117.204",
  	// }
});