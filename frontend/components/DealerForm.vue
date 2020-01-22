<template>
  <v-layout>
    <v-flex>
      <v-card>
        <v-form>
          <v-container fluid>
            <v-layout justify-center row wrap>
              <v-flex xs6 sm6>
                <h5 class="display-1">Requesting Isuzu Dealer</h5>
              </v-flex>
            </v-layout>

            <v-layout justify-center row wrap>
              <v-flex xs6 sm6>
                <v-select
                label="Isuzu Dealership Name"
                name="Isuzu Dealership Name"
                v-model="dealership_name"
                v-validate="'required'"
                :items="dealers"
                item-text="dealer"
                item-value="dealer_id"
                :error-messages="errors.first('Dealership Name')"
                search-input
                outline
                required
                >
                </v-select>
              </v-flex>
            </v-layout>

            <v-layout justify-center row wrap>
              <v-flex xs6 sm3>
                <v-text-field
                label="Name of requester"
                name="Requestor Name"
                v-model="requestor_name"
                v-validate="'required'"
                :error-messages="errors.first('Requestor Name')"
                outline
                required
                ></v-text-field>
              </v-flex>

              <v-flex xs12 sm3>
                <v-text-field
                label="Position"
                name="Position"
                v-model="position"
                v-validate="'required'"
                :error-messages="errors.first('Position')"
                outline
                required
                ></v-text-field>
              </v-flex>
            </v-layout>

            <v-layout justify-center row wrap>
              <v-flex xs6 sm3>
                <v-text-field
                label="Email Address"
                name="Email"
                v-model="email"
                v-validate="'required|email'"
                :error-messages="errors.first('Email')"
                outline
                required
                ></v-text-field>
              </v-flex>

              <v-flex xs12 sm3>
                <v-text-field
                label="Contact Number"
                name="Contact"
                v-model.number="contact"
                v-validate="'required|max:10|min:10|integer'"
                :error-messages="errors.first('Contact')"
                type="number"
                outline
                required
                ></v-text-field>
              </v-flex>
            </v-layout>

          </v-container>
        </v-form>
        <v-layout justify-end row>
           <v-btn 
       
            @click="back"
            color="red darken-1" 
            flat
            >
              <v-icon small>fa fa-arrow-circle-left</v-icon>&nbsp;
              Back
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn 
            @click="proceed"
            :disabled="isFormInvalid"
            color="red darken-1" 
            flat
            >
              Proceed &nbsp;
              <v-icon small>fa fa-arrow-circle-right</v-icon>
            </v-btn>
        </v-layout>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
import { mapState } from 'vuex'
import axios from 'axios'

export default {
  name: 'DealerForm',
  data () {
    return {
      dealers: []
    }
  },
  computed: {
    ...mapState('request', ['form']),
    isFormInvalid () {
      return Object.keys(this.fields).some(key => this.fields[key].invalid);
    },
    dealership_name: {
      get () {
        return this.$store.state.request.form.dealer_info.dealership_name
      },
      set (val,text) {
        this.$store.commit('request/UPDATE_DEALER_FORM', {key:'dealership_name',value:val})
      }
    },
    requestor_name: {
      get () {
        return this.$store.state.request.form.dealer_info.requestor_name
      },
      set (val) {
        this.$store.commit('request/UPDATE_DEALER_FORM', {key:'requestor_name',value:val})
      }
    },
    position: {
      get () {
        return this.$store.state.request.form.dealer_info.position
      },
      set (val) {
        this.$store.commit('request/UPDATE_DEALER_FORM', {key:'position',value:val})
      }
    },
    email: {
      get () {
        return this.$store.state.request.form.dealer_info.email
      },
      set (val) {
        this.$store.commit('request/UPDATE_DEALER_FORM', {key:'email',value:val})
      }
    },
    contact: {
      get () {
        return this.$store.state.request.form.dealer_info.contact
      },
      set (val) {
        this.$store.commit('request/UPDATE_DEALER_FORM', {key:'contact',value:val})
      }
    }
  },
  mounted () {
    this.getDealers()
/*      this.$store.commit('request/NEXT_PAGE');
    this.$store.commit('request/NEXT_PAGE');
    this.$store.commit('request/NEXT_PAGE');
    this.$store.commit('request/NEXT_PAGE'); 
    this.$store.commit('request/NEXT_PAGE');   */
  },
  methods: {
    updateForm (field, value) {
      this.$store.commit('request/UPDATE_DEALER_FORM', {key:field,value:value})
    },
    proceed () {
      this.$store.commit('request/NEXT_PAGE')
    },
    back () {
      this.$store.commit('request/BACK_PAGE')
    },
    getDealers () {
      axios.get(`${this.api_url}guest/dealers/get`)
      .then(({data}) => {
        data.forEach(element => {
          element.dealer = element.dealer + ' | ' + element.branch
        });
        this.dealers = data
      })
      .catch((error) => {
        console.log(error)
      })
    }
  }
}
</script>