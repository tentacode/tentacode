import { defineConfig } from "astro/config";

import tailwindcss from "@tailwindcss/vite";

// https://astro.build/config
export default defineConfig({
	site: "https://ohdit.com",
	build: {
		inlineStylesheets: 'auto', // Inline small CSS files
	},
	vite: {
      	cssCodeSplit: false, // Split CSS per page
		rollupOptions: {
			output: {
				// Better chunking strategy
				manualChunks(id) {
					if (id.includes('node_modules')) {
						return 'vendor';
					}
				}
			}
		},
		server: {
			host: true, // Listens on 0.0.0.0
			fs: {
				// Needed to load fonts in dev mode
				allow: [".."],
			},
		},

		plugins: [tailwindcss()],
	},
});
