<template>
  <v-layout>
    <v-flex>
      <v-card>
        <v-form>
          <v-container fluid>
            <v-layout justify-center row wrap>
              <v-flex xs8 sm8 md8 lg5>
                <h5 class="display-1">Training Details</h5>
              </v-flex>
            </v-layout>

            <v-layout justify-center row wrap>
              <v-flex xs8 sm8 md8 lg5>
                <v-dialog
                ref="dialog"
                v-model="modal"
                :return-value.sync="form.training_date"
                persistent
                lazy
                full-width
                width="300px"
                >
                  <v-text-field
                  slot="activator"
                  :value="dateFormatted"
                  label="Training Date"
                  append-icon="event"
                  outline
                  clearable
                  readonly
                  ></v-text-field>

                  <v-date-picker
                  v-model="training_date"
                  name="Training Date"
                  v-validate="'required'"
                  :error-messages="errors.first('Training Date')"
                  :min="nowDate" 
                  :allowed-dates="allowedDates"
                  :reactive="reactive"
                  width="100%"
                  color="green"
                  scrollable
                  dark
                  >
                    <v-spacer></v-spacer>
                    <v-btn flat color="green" @click="modal = false">Cancel</v-btn>
                    <v-btn flat color="green" @click="$refs.dialog.save(form.training_date)">OK</v-btn>
                  </v-date-picker>
                </v-dialog>
              </v-flex>
            </v-layout>
            <v-layout justify-center row wrap>
              <v-flex xs8 sm8 md8 lg5>
                <v-select
                label="Training Time"
                name="Training Time"
                v-model="training_time"
                v-validate="'required'"
                :error-messages="errors.first('Training Time')"
                :items="training_times"
                item-text="label"
                item-value="value"
                outline
                ></v-select>
              </v-flex>
            </v-layout>
           <!--  <v-layout justify-center row wrap>
              <v-flex xs8 sm8 md8 lg5>
                <v-dialog
                  ref="dialog2"
                  v-model="timePickerModal"
                  :return-value.sync="form.training_time"
                  persistent
                  lazy
                  full-width
                  width="290px"
                >
                  <v-text-field
                  slot="activator"
                  :value="timeFormatted"
                  label="Training Time"
                  append-icon="access_time"
                  outline
                  clearable
                  readonly
                  ></v-text-field>

                  <v-time-picker
                    v-if="timePickerModal"
                    v-model="training_time"
                    full-width
                  >
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="timePickerModal = false">Cancel</v-btn>
                    <v-btn flat color="primary" @click="$refs.dialog2.save(form.training_time)">OK</v-btn>
                  </v-time-picker>
                </v-dialog>
              </v-flex>
            </v-layout> -->

            <v-layout justify-center row wrap>
              <v-flex xs8 sm8 md8 lg5>
                <v-select
                label="Training Venue"
                name="Training Venue"
                v-model="training_venue"
                v-validate="'required'"
                :error-messages="errors.first('Training Venue')"
                :items="training_venues"
                outline
                ></v-select>
              </v-flex>
            </v-layout>

            <v-layout justify-center row wrap>
              <v-flex xs8 sm8 md8 lg5>
                <v-text-field
                label="Training Venue Address"
                name="Training Venue Address"
                v-model="training_address"
                v-validate="'required'"
                :error-messages="errors.first('Training Venue Address')"
                outline
                ></v-text-field>
              </v-flex>
            </v-layout>

            <v-layout justify-center row wrap>
              <v-flex xs8 sm8 md8 lg5>
                <v-text-field
                label="Additional Request"
                name="Additional Request"
                v-model="remarks"
                :error-messages="errors.first('Additional Request')"
                outline
                ></v-text-field>
              </v-flex>
            </v-layout>

            <v-layout justify-center row wrap>
              <v-flex xs8 sm8 md8 lg5>
                <!-- Training Form Participants -->
                <TrainingFormParticipants />
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>
        <v-layout justify-end row>
          <v-btn 
            @click="back"
            color="red darken-1" 
            flat dark
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
import moment from 'moment'
import axios from 'axios'
import TrainingFormParticipants from './TrainingFormParticipants'

export default {
  name: 'TrainingForm',
  components: {TrainingFormParticipants},
  data() {
    return {
      // date: new Date().toISOString().substr(0, 10),
      modal: false,
      reactive: true,
      training_venues: ['Fleet Customer Premises', 'Isuzu Training Center', 'Dealer Premises'], // Isuzu Training Center
      training_times: [
        { label : 'AM', value: 'AM' },
        { label : 'PM', value: 'PM' },
        { label : 'Whole day', value: 'Whole day' },
      ],
      disabledDates: [],
      shouldDisable: false,
      timePickerModal: false,
      time: '',
      nowDate: new Date().toISOString().slice(0,10),
      date: new Date(), 
      picker: new Date().toISOString().substr(0, 10),
     
    
    }
  },
  computed: {
    ...mapState('request', ['form']),
    dateFormatted () {
      return this.form.training_date ? moment(this.form.training_date).format('dddd, MMMM Do YYYY') : ''
    },
    timeFormatted () {
      return this.form.training_time ? this.convertTime(this.form.training_time) : ''
    },
    isFormInvalid () {
      return Object.keys(this.fields).some(key => this.fields[key].invalid);
    },
    training_date: {
      get () {
        return this.$store.state.request.form.training_date
      },
      set (val) {
        this.$store.commit('request/UPDATE_FORM', {key:'training_date',value:val})
      }
    },
    training_time: {
      get () {
        return this.$store.state.request.form.training_time
      },
      set (val) {
        this.$store.commit('request/UPDATE_FORM', {key:'training_time',value:val})
      }
    },
    training_venue: {
      get () {
        return this.$store.state.request.form.training_venue
      },
      set (val) {
        if (val === 'Isuzu Training Center') {
          this.training_address = 'Isuzu Philippines Corporation 114 Technology Avenue Phase II, Laguna Technopark Biñan Laguna 4024 Philippines'
        }
        else {
          this.training_address = ''
        }

        this.$store.commit('request/UPDATE_FORM', {key:'training_venue',value:val})
      }
    },
    training_address: {
      get () {
        return this.$store.state.request.form.training_address
      },
      set (val) {
        this.$store.commit('request/UPDATE_FORM', {key:'training_address',value:val})
      }
    },
    company_name: {
      get () {
        return this.$store.state.request.form.company_name
      },
      set (val) {
        this.$store.commit('request/UPDATE_FORM', {key:'company_name',value:val})
      }
    },
    remarks: {
      get () {
        return this.$store.state.request.form.remarks
      },
      set (val) {
        this.$store.commit('request/UPDATE_FORM', {key:'remarks',value:val})
      }
    },
    training_participants: {
      get () {
        return this.$store.state.request.form.training_participants
      },
      set (val) {
        this.$store.commit('request/UPDATE_FORM', {key:'training_participants',value:val})
      }
    },
    getEndDate() {
     var endDate = new Date(this.date.getFullYear(), this.date.getMonth() + 1, 10);
     return endDate.toISOString().slice(0,10)
    }
  },
  mounted () {
    this.getDisabledDates()
  },
  methods: {
    updateForm (field, value) {
      this.$store.commit('request/UPDATE_FORM', {key:field,value:value})
    },

    allowedDates (date) {
      return this.disabledDates.indexOf(date) === -1 
    },

    getDisabledDates () {
      axios.get(`${this.api_url}guest/schedules/get`)
      .then(({data}) => {
        var dates = []
        data.map((date) => {
          date.date_range.forEach(element => {
            dates.push(element)
          })
        })

        this.addDisabledDates().forEach(element => {
          dates.push(element)
        });

        this.disabledDates = dates
      })
      .catch((error) => {
        console.log(error.response)
      })
    },

    back () {
      this.$store.commit('request/BACK_PAGE')
    },

    proceed () {
      if (this.training_participants.length <= 0) {
        alert('Fill out training participants')
      }
      else {
        this.$store.commit('request/NEXT_PAGE')
      }
    },
    /**
     * Convert 24hr time into 12hr time (12:00 AM)
     */
    convertTime (time) {
      // Check correct time format and split into components
      time = time.toString().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

      if (time.length > 1) { // If time format correct
        time = time.slice (1);  // Remove full string match value
        time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
        time[0] = +time[0] % 12 || 12; // Adjust hours
      }
      return time.join (''); // return adjusted time or original string
    },

    addDisabledDates () {
      var now = moment().format('YYYY-MM-DD')
      var tomorrow = moment(now).add(1, 'day').format('YYYY-MM-DD')
      var days = 13;
      var disabled = [];

      disabled.push(moment().format('YYYY-MM-DD'));
      for (let index = 0; index < days; index++) {
        disabled.push(moment(tomorrow).add(index, 'day').format('YYYY-MM-DD'))
      }

      return disabled
    },
  }
}
</script>
