import { vitePreprocess } from '@sveltejs/vite-plugin-svelte'; // import a helper to handle extra features (like modern CSS)

export default   // export this object so the Svelte compiler can read your settings
{
  preprocess: vitePreprocess(),   // run these extra steps (preprocessing) before turning .svelte into JS
  compilerOptions: 
   {
    accessors: true
  }
};