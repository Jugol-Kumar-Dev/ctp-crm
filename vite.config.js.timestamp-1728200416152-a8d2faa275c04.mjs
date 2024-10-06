// vite.config.js
import vue from "file:///D:/jk-office-projects/ctp-crm/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import laravel from "file:///D:/jk-office-projects/ctp-crm/node_modules/laravel-vite-plugin/dist/index.js";
import { defineConfig } from "file:///D:/jk-office-projects/ctp-crm/node_modules/vite/dist/node/index.js";
import path from "path";
var __vite_injected_original_dirname = "D:\\jk-office-projects\\ctp-crm";
var vite_config_default = defineConfig({
  plugins: [
    vue(),
    laravel({
      input: ["resources/js/app.js", "resources/css/app.css"],
      refresh: true
    })
  ],
  resolve: {
    alias: {
      "@": path.resolve(__vite_injected_original_dirname, "resources/js")
    }
  }
  // resolve: {
  //     alias: {
  //         '@global': path.resolve(__dirname, 'src/global'),
  //     },
  // },
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJEOlxcXFxqay1vZmZpY2UtcHJvamVjdHNcXFxcY3RwLWNybVwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiRDpcXFxcamstb2ZmaWNlLXByb2plY3RzXFxcXGN0cC1jcm1cXFxcdml0ZS5jb25maWcuanNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL0Q6L2prLW9mZmljZS1wcm9qZWN0cy9jdHAtY3JtL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHZ1ZSBmcm9tIFwiQHZpdGVqcy9wbHVnaW4tdnVlXCI7XG5pbXBvcnQgbGFyYXZlbCBmcm9tIFwibGFyYXZlbC12aXRlLXBsdWdpblwiXG5pbXBvcnQge2RlZmluZUNvbmZpZ30gZnJvbSBcInZpdGVcIjtcbmltcG9ydCBwYXRoIGZyb20gJ3BhdGgnO1xuZXhwb3J0IGRlZmF1bHQgZGVmaW5lQ29uZmlnKHtcbiAgICBwbHVnaW5zOltcbiAgICAgICAgdnVlKCksXG4gICAgICAgIGxhcmF2ZWwoe1xuICAgICAgICAgICAgaW5wdXQ6IFsncmVzb3VyY2VzL2pzL2FwcC5qcycsICdyZXNvdXJjZXMvY3NzL2FwcC5jc3MnXSxcbiAgICAgICAgICAgIHJlZnJlc2g6IHRydWUsXG4gICAgICAgIH0pLFxuICAgIF0sXG4gICAgcmVzb2x2ZTp7XG4gICAgICAgIGFsaWFzOntcbiAgICAgICAgICAgICdAJzogcGF0aC5yZXNvbHZlKF9fZGlybmFtZSwgJ3Jlc291cmNlcy9qcycpXG4gICAgICAgIH1cbiAgICB9XG5cbiAgICAvLyByZXNvbHZlOiB7XG4gICAgLy8gICAgIGFsaWFzOiB7XG4gICAgLy8gICAgICAgICAnQGdsb2JhbCc6IHBhdGgucmVzb2x2ZShfX2Rpcm5hbWUsICdzcmMvZ2xvYmFsJyksXG4gICAgLy8gICAgIH0sXG4gICAgLy8gfSxcbn0pXG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQStRLE9BQU8sU0FBUztBQUMvUixPQUFPLGFBQWE7QUFDcEIsU0FBUSxvQkFBbUI7QUFDM0IsT0FBTyxVQUFVO0FBSGpCLElBQU0sbUNBQW1DO0FBSXpDLElBQU8sc0JBQVEsYUFBYTtBQUFBLEVBQ3hCLFNBQVE7QUFBQSxJQUNKLElBQUk7QUFBQSxJQUNKLFFBQVE7QUFBQSxNQUNKLE9BQU8sQ0FBQyx1QkFBdUIsdUJBQXVCO0FBQUEsTUFDdEQsU0FBUztBQUFBLElBQ2IsQ0FBQztBQUFBLEVBQ0w7QUFBQSxFQUNBLFNBQVE7QUFBQSxJQUNKLE9BQU07QUFBQSxNQUNGLEtBQUssS0FBSyxRQUFRLGtDQUFXLGNBQWM7QUFBQSxJQUMvQztBQUFBLEVBQ0o7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBT0osQ0FBQzsiLAogICJuYW1lcyI6IFtdCn0K
