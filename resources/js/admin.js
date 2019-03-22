import Vue from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faCoffee } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import ElementUI from 'element-ui'
import i18n from './lang'
import VCharts from 'v-charts'
import echarts from 'echarts'
import 'element-ui/lib/theme-chalk/index.css'
import './assets/ali-icon/iconfont.css'
import './assets/ali-icon/ali.css'
import './assets/css/mojito.css'
import App from './Admin.vue';

library.add(faCoffee)

Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.config.productionTip = false

Vue.use(VCharts)
Vue.use(echarts)
Vue.prototype.$echarts=echarts

import router from './router'
import store from './store'
import config from './config'

Vue.use(ElementUI, {
    i18n: (key, value) => i18n.t(key, value)
})

Vue.prototype.$config = config
Vue.prototype.$provider = 'admin'
i18n.locale = config[Vue.prototype.$provider].locale ? config[Vue.prototype.$provider].locale : 'en'

/* eslint-disable no-new */
const app = new Vue({
    el: '#app',
    router,
    store,
    i18n,
    render: h => h(App)
})
