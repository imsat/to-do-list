import '../css/app.css'
import {createApp} from "vue";
import {createPinia} from 'pinia'

import App from './App.vue'
// import Validation from './components/Validation.vue'
import router from "./routes";

const store = createPinia()
const app = createApp(App)

app.use(router)
app.use(store)
// app.component('Validation', Validation)
app.mount('#app')
