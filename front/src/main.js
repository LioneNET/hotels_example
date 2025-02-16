import { createApp } from 'vue'
import { Quasar, Loading, Notify } from 'quasar'
import '@quasar/extras/material-icons/material-icons.css'
import 'quasar/src/css/index.sass'
import './style.css'
import App from './App.vue'

const app = createApp(App)

app.use(Quasar, {
  plugins: {
    Loading,
    Notify
  }
})

app.mount('#app')