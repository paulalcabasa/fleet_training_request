<template>
  <div>
    <v-dialog
      :value="hasNotPickedYet"
      width="500"
      persistent
    >
      <v-card>
        <v-card-title
          class="headline"
          primary-title
          style="color: #444444"
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