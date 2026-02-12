import { defineConfig } from 'vite'; //helper for vite so it can check settings
import { svelte } from '@sveltejs/vite-plugin-svelte'; // import the plugin that lets Vite compile .svelte files


export default defineConfig(
{
  server: 
  {
    port: 3000,
    strictPort: true  // fail if 3000 is taken
  },

  plugins: [svelte()],  // tell Vite “use the Svelte plugin” during dev and build
  build: // start the section of settings used when you run `vite build`
  {
    outDir: 'public', //tells vite where to place the final optimized files
  },
});