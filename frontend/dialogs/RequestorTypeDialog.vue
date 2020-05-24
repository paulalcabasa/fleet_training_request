<template>
  <div>
    <v-dialog
      :value="hasNotPickedYet"
      width="700"
      persistent
    >

       <v-card>
       
        <v-card-text style="padding-top: 0;">
          <v-container>
            <v-layout>
              <v-flex>
                Due to the COVID19 pandemic, all trainings will be conducted online (video conference). 
              </v-flex>
            </v-layout>
            <v-layout>
              <v-flex>
                <Br/>
                    <p>Note: The following will be used and is requested to be prepared for the online training: </p>
                    <ul>
                        <li>Internet connection, at least 3mbps</li>
                        <li>Video conferencing application (ZOOM)</li>
                        <li>Laptop or desktop with camera</li>
                        <li>Projector and speaker video conference projection</li>
                        <li>Venue for the video conference (In compliance with the government safety protocol on social distancing, maximum of five (5) person per session is recommended)</li>
                    </ul>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
       <v-divider></v-divider>
      <v-card>
        <v-card-title
          primary-title
          style="color: #444444;font-size:12pt;font-weight:bold;"

        >
          Please select the type of requestor
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-layout 
            align-center
            wrap>
              <v-flex xs6 class="text-xs-center">
                <v-avatar size="100">
                  <img
                    src="../assets/images/customer.jpg"
                    alt="image"
                  >
                </v-avatar>
              </v-flex>
              <v-flex xs6 class="text-xs-center">
                <v-avatar size="100">
                  <img
                    src="../assets/images/dealer.png"
                    alt="image"
                  >
                </v-avatar>
              </v-flex>
              <v-flex xs6 class="text-xs-center">
                <v-btn color="success" @click="changeRequestorType('customer')">Fleet Customer</v-btn>
              </v-flex>
              <v-flex xs6 class="text-xs-center">
                <v-btn color="success" @click="changeRequestorType('dealer')">Dealer</v-btn>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters, mapState } from 'vuex'

export default {
  name: 'requestor-dialog',
  computed: {
    ...mapGetters('request', [
      'getRequestor'
    ]),
    hasNotPickedYet() {
      return this.getRequestor === '' ? 1 : 0 
    }
  },
  // created() {
  //   this.changeRequestorType('dealer') //--> for dev
  // },
  methods: {
    changeRequestorType(requestor) {
      this.$store.dispatch('request/requestorType', requestor)
    }
  }
}
</script>