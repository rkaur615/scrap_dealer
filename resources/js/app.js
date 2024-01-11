require('./bootstrap');

import { createApp } from 'vue'
import Notifications from '@kyvg/vue3-notification'

// console.log('Vue',Vue);
// Vue.use(VueToastify);

import HelloWorld from './components/Welcome.vue'
import Availability from './components/Availability.vue'
import ManageProduct from './components/ManageProduct.vue'
import AddRequirement from './components/AddRequirement.vue'
import ViewRequirement from './components/ViewRequirement.vue'
import ChangePassword from './components/ChangePassword.vue'
import ViewPublicProfile from './components/ViewPublicProfile.vue'
import ChangePDetails from './components/ChangePDetails.vue'
import Catalog from './components/Catalog.vue'

const app = createApp({})
app.use(Notifications);

app.component('Availability', Availability)
app.component('view-public-profile', ViewPublicProfile)
app.component('manage-product', ManageProduct)
app.component('add-requirement', AddRequirement)
app.component('view-requirement', ViewRequirement)
app.component('add-catalog', Catalog)
app.component('change-password', ChangePassword)
app.component('change-pdetails', ChangePDetails)

app.mount('#app')

