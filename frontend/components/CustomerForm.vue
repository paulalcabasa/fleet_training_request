<template>
  <v-layout>
    <v-flex>
      <v-card>
        <v-form>
          <v-container fluid>
            <v-layout justify-center row wrap>
              <v-flex xs6 sm8>
                <h5 class="display-1">Customer Details</h5>
              </v-flex>
            </v-layout>
            
            <v-layout justify-center row wrap>
              <v-flex xs6 sm8>
                <v-text-field
                label="Company Name"
                name="Company Name"
                v-model="company_name"
                v-validate="'required'"
                :error-messages="errors.first('Company Name')"
                outline
                required
                ></v-text-field>
              </v-flex>
            </v-layout>

            <v-layout justify-center row wrap>
              <v-flex xs6 sm8>
                <v-text-field
                label="Office Address"
                name="Office Address"
                v-model="office_address"
                v-validate="'required'"
                :error-messages="errors.first('Office Address')"
                outline
                required
                ></v-text-field>
              </v-flex>
            </v-layout>

            <v-layout justify-center row wrap>
              <v-flex xs6 sm4>
                <v-text-field
                label="Contact Person"
                name="Contact Person"
                v-model="contact_person"
                v-validate="'required'"
                :error-messages="errors.first('Contact Person')"
                outline
                required
                ></v-text-field>
              </v-flex>
              <v-flex xs6 sm4>
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
              <v-flex xs6 sm4>
                <v-text-field
                label="Email Address"
                name="Email"
                v-model="email"
                v-validate="'required|email'"
                :error-messages="errors.first('Email')"
                outline
                hint="Note: Confirmation and other details will be sent to this email address"
                required
                ></v-text-field>
              </v-flex>
              <v-flex xs6 sm4>
                <v-text-field
                label="Mobile Number"
                name="Mobile Number"
                v-model.number="contact_number"
                v-validate="'required|max:10|min:10|integer'"
                :error-messages="errors.first('Mobile Number')"
                type="number"
                outline
                required
                ></v-text-field>
              </v-flex>
            </v-layout>

            <v-layout justify-center row wrap>
              <v-flex xs6 sm4>
                <v-select
                label="Isuzu Dealer"
                name="Isuzu Dealer"
                v-model="selling_dealer"
                v-validate="'required'"
                :error-messages="errors.first('Selling Dealer')"
                :items="dealers"
                item-text="dealer"
                item-value="dealer_id"
                deletable-chips
                chips
                color="red"
                search-input
                multiple
                outline
                required
                >
                </v-select>
              </v-flex>

              <v-flex xs6 sm4>
                <v-select
                label="Owned Isuzu Vehicles"
                name="Owned Isuzu Vehicles"
                v-model="unit_models"
                v-validate="'required'"
                :error-messages="errors.first('Owned Isuzu Vehicles')"
                :items="models"
								item-text="model_name"
                item-value="model_name"
                deletable-chips
                chips
                color="red"
                search-input
                multiple
                outline
                required
                >
                </v-select>
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
import { mapGetters, mapState } from 'vuex'
import axios from 'axios'

export default {
  name: 'CustomerForm',
  data () {
    return {
      dealers: [],
      models: []
    }
  },
  computed: {
    ...mapState('request', ['form']),
    isFormInvalid () {
      return Object.keys(this.fields).some(key => this.fields[key].invalid);
    },
    company_name: {
      get () {
        return this.$store.state.request.form.company_name
      },
      set (val) {
        this.$store.commit('request/UPDATE_FORM', {key:'company_name',value:val})
      }
    },
    office_address: {
      get () {
        return this.$store.state.request.form.office_address
      },
      set (val) {
        this.$store.commit('request/UPDATE_FORM', {key:'office_address',value:val})
      }
    },
    contact_person: {
      get () {
        return this.$store.state.request.form.contact_person
      },
      set (val) {
        this.$store.commit('request/UPDATE_FORM', {key:'contact_person',value:val})
      }
    },
    position: {
      get () {
        return this.$store.state.request.form.position
      },
      set (val) {
        this.$store.commit('request/UPDATE_FORM', {key:'position',value:val})
      }
    },
    email: {
      get () {
        return this.$store.state.request.form.email
      },
      set (val) {
        this.$store.commit('request/UPDATE_FORM', {key:'email',value:val})
      }
    },
    contact_number: {
      get () {
        return this.$store.state.request.form.contact_number
      },
      set (val) {
        this.$store.commit('request/UPDATE_FORM', {key:'contact_number',value:val})
      }
    },
    selling_dealer: {
      get () {
        return this.$store.state.request.form.selling_dealer
      },
      set (val) {
        this.$store.commit('request/UPDATE_FORM', {key:'selling_dealer',value:val})
      }
    },
    unit_models: {
      get () {
        return this.$store.state.request.form.unit_models
      },
      set (val) {
        this.$store.commit('request/UPDATE_FORM', {key:'unit_models',value:val})
      }
    },
  },
  mounted () {
    this.fetchDealers()
    this.fetchUnitModels()
  },
  methods: {
    updateForm (field, value) {
      this.$store.commit('request/UPDATE_FORM', {key:field,value:value})
    },
    fetchDealers () {
      axios.get(`${this.api_url}guest/dealers/get`)
      .then(({data}) => {
        data.forEach(element => {
          element.dealer = element.dealer + ' | ' + element.branch
        });
        this.dealers = data
      })
      .catch((error) => {
        console.log(error.response)
      })
    },
    fetchUnitModels () {
      axios.get(`${this.api_url}guest/unit_models/get`)
      .then(({data}) => {
        this.models = data;
      })
      .catch((error) => {
        console.log(error);
      });
    },
    proceed () {
      this.$store.commit('request/NEXT_PAGE')
    },
    back () {
      this.$store.commit('request/BACK_PAGE')
    }
  }
}
</script>