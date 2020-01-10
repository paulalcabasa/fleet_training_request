<template>
  <v-layout>
    <v-flex>
      <v-card>
        <!-- <v-card-title primary-title>
          <div>
            <h5 class="display-1">Choose Program</h5>
          </div>
        </v-card-title> -->

        <v-card-text>
          <v-alert
          :value="true"
          color="red lighten-1"
          >
            <p><i class="fa fa-bell"></i>&nbsp; Important Reminders</p>
            <ol>
              <li>All data provided will serve as our guide for your training request.</li>
              <li>In case you have correction with the information you provided, changes on training venue, please contact your Isuzu dealer immediately.</li>
              <li>
                Please regulary check your registered email address for the updates of your training request. <br>
              </li>
            </ol>
            <br>
            <p>
              In case you need an update on your request you may contact <strong>Isuzu Training Department</strong><br>
              at <strong>(049) 541-0224 local 346</strong> , Monday to Friday, 7:30 A.M - 5:45 P.M and look for <strong>Ms. Clarissa Manimtim</strong>.
            </p>
            <v-btn
            @click="changeButtonState"
            :color="button_status.color"
            >
              <i :class="`fa fa-${button_status.icon}`"></i>&nbsp;
              {{ button_status.text }}
            </v-btn>
          </v-alert>
        </v-card-text>

        <v-card-text>
          <SpecialTrainings />
        </v-card-text>

        <v-layout justify-end row>
          <v-btn 
          @click="back"
          >
            <v-icon small>fa fa-arrow-circle-left</v-icon>&nbsp;
            Back
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn 
          id="submit_button"
          color="red darken-1 white--text" 
          
          @click="submit"
          :disabled="button_status.is_disabled"
          :loading="$store.state.request.isSubmitting"
          >
            Submit
            <template v-slot:loader>
              <span class="custom-loader">
                <v-icon light>cached</v-icon>
              </span>
            </template>
          </v-btn>
        </v-layout>
      </v-card>
    </v-flex>
    <Snackbar />
  </v-layout>
</template>

<script>
import SpecialTrainings from './SpecialTrainings'
import Snackbar from '../dialogs/Snackbar'
import { mapState } from 'vuex'

export default {
  name: 'Submit',
  components: {Snackbar,SpecialTrainings},
  data () {
    return {
      button_status: {
        color: '',
        icon: 'question-circle',
        text: 'Done Reading',
        is_disabled: true
      }
    }
  },
  methods: {
    submit () {
      this.$store.dispatch('request/submitRequest', this.$store.state.request.form)
    },
    back () {
      this.$store.commit('request/BACK_PAGE')
      this.$store.commit('request/RESET_SPECIAL_TRAININGS')
    },
    changeButtonState () {
      this.button_status = Object.assign({}, this.button_status, {
        color: 'green white--text',
        icon: 'check',
        text: 'Already Read',
        is_disabled: false
      })

      this.$vuetify.goTo('#submit_button')
    }
  }
}
</script>

<style scoped>
.custom-loader {
  animation: loader 1s infinite;
  display: flex;
}
@-moz-keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@-webkit-keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@-o-keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
