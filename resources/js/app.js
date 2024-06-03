import { createApp, h } from "vue";
import { createInertiaApp, Link, Head } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import VueFeather from 'vue-feather';
import Required from './components/RequiredBatch'
import Layout from "./Shared/Layout";
import store from './Store'
import { createVbPlugin } from 'vue3-plugin-bootstrap5'
import CKEditor from '@ckeditor/ckeditor5-vue'
import { Alert, Button, Carousel, Collapse, Dropdown, Modal, Offcanvas, Popover, ScrollSpy, Tab, Toast, Tooltip } from 'bootstrap'
let vbPlugin = createVbPlugin({ Alert, Button, Carousel, Collapse, Dropdown, Modal, Offcanvas, Popover, ScrollSpy, Tab, Toast, Tooltip  })
import 'vue-select/dist/vue-select.css';
import vSelect from 'vue-select'
import CoreuiVue from "@coreui/vue";
import { VTooltip, VPopover, VClosePopover } from 'v-tooltip'
import {createPinia} from "pinia";
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import al from "@vuepic/vue-datepicker";
import {moment} from "moment";
import Prism from "prismjs";

import { createToaster } from "@meforma/vue-toaster";
import { PrismEditor } from 'vue-prism-editor';
import 'vue-prism-editor/dist/prismeditor.min.css'; // import the styles

import VueTelInput from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';

window.$toast = createToaster({
    position: 'bottom'
});



createInertiaApp({
  resolve: name => {
    let page = require(`./Pages/${name}`).default;

    if (page.layout === undefined) {
      page.layout = Layout;
    }
    return page;
  },

  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(vbPlugin)
      .use(CoreuiVue)
      .use(store)
        .directive('tooltip', VTooltip)
      .use(CKEditor)
        .use(createPinia())
        .use(moment)
        .use(Prism)
        .use(VueTelInput)
      .component("Link", Link)
      .component("Head", Head)
      .component(VueFeather.name, VueFeather)
      .component("Required", Required)
      .component("v-select", vSelect)
      .component("Datepicker", Datepicker)
      .mount(el);
  },

  title: title => `${title} | Creative Tech Park`
});

InertiaProgress.init({
  color: "red",
  showSpinner: true,
});

