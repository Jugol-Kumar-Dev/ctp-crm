import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin"
import {defineConfig} from "vite";
import path from 'path';
export default defineConfig({
    plugins:[
        vue(),
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'],
            refresh: true,
        }),
    ],
    resolve:{
        alias:{
            '@': path.resolve(__dirname, 'resources/js')
        }
    }

    // resolve: {
    //     alias: {
    //         '@global': path.resolve(__dirname, 'src/global'),
    //     },
    // },
})
