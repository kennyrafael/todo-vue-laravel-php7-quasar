import VueMoment from 'vue-moment'
import moment from 'moment-timezone'

// "async" is optional;
// more info on params: https://quasar.dev/quasar-cli/cli-documentation/boot-files#Anatomy-of-a-boot-file
export default async ({ app, router, Vue }) => {
  Vue.use(VueMoment, { moment })
}
