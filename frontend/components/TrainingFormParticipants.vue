<template>
  <div>
    <v-card>
      <v-list>
        <template>
          <div>
            <div>
              <v-list-tile>
                <v-list-tile-action>
                  <v-btn color="success" fab small flat dark @click="addParticipant">
                    <v-icon medium>add_circle</v-icon>
                  </v-btn>
                </v-list-tile-action>
                <v-list-tile-content>
                    <v-layout row wrap>
                      <v-flex xs12 md7>
                        <v-select 
                        label="Choose participants"
                        placeholder="--"
                        v-model="participant.participant"
                        :items="default_participants"
                        style="padding-top:1.5em"
                        ></v-select>
                      </v-flex>
                      <v-divider class="mx-2" inset vertical></v-divider>
                      <v-flex xs12 md4>
                        <v-text-field
                        label="Target headcount"
                        v-model.number="participant.quantity"
                        required
                        style="padding-top:1.5em"
                        ></v-text-field>
                      </v-flex>
                    </v-layout>
                </v-list-tile-content>
              </v-list-tile>
            </div>
          </div>
        </template>
        <template v-for="(item, index) in form.training_participants">
          <v-divider
          :key="index"
          ></v-divider>
          <div :key="item.participant">
            <div>
              <v-list-tile>
                <v-list-tile-action>
                  <v-btn color="red" fab small flat dark @click="removeParticipant(index)">
                    <v-icon medium>cancel</v-icon>
                  </v-btn>
                </v-list-tile-action>
                <v-list-tile-content>
                  <v-list-tile-sub-title>
                    <v-chip color="green" text-color="white">
                      <v-avatar class="green darken-4">{{ item.quantity }}</v-avatar>
                      {{ item.participant }}
                    </v-chip>
                  </v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
            </div>
          </div>
        </template>
      </v-list>
    </v-card>

    <v-dialog v-model="manual_participants" max-width="290" persistent>
      <v-card>
          <v-card-title
          class="headline"
          primary-title
          >
            Enter desired participants
          </v-card-title>
          <v-card-text>
              <div class="form-group">
                  <v-text-field
                  label="Participant"
                  v-model="participant.participant"
                  outline
                  required
                  ></v-text-field>
              </div>
              <div class="form-group">
                  <v-text-field
                  label="Quantity"
                  type="number"
                  v-model.number="participant.quantity"
                  outline
                  required
                  ></v-text-field>
              </div>
          </v-card-text>
          <v-card-actions style="margin-top: -18px;">
              <v-spacer></v-spacer>
              <v-btn color="red darken-1" flat @click="manual_participants = false">Cancel</v-btn>
              <v-btn color="green darken-1" flat @click="addParticipant">Save</v-btn>
          </v-card-actions>
      </v-card>
  </v-dialog>
  </div>
</template>

<script>
import { mapGetters, mapState } from 'vuex'

export default {
  data() {
    return {
      default_participants: ['Driver', 'Mechanic', 'Fleet Head', 'Others'],
      participant: {
        participant: '',
        quantity: 0
      },
      manual_participants: false
    }
  },
  watch: {
    participant: {
      handler (v) {
        if (this.participant.participant == 'Others') {
          this.resetParticipant()
          this.manual_participants = true
        }
      },
      deep: true
    }
  },
  computed: {
    ...mapState('request', ['form'])
  },
  methods: {
    updateForm (field, value) {
      this.$store.commit('request/UPDATE_FORM', {key:field,value:value})
    },
    addParticipant () {
      let error = false
      this.form.training_participants.forEach(element => {
        if (this.participant.participant == element.participant) {
          error = true
        }
      });

      if (error == true) return
      if (this.participant.participant == 'Others') return
      if (this.participant.participant != '' && this.participant.quantity != '') {
        this.$store.commit('request/PUSH_FORM', {key:'training_participants',value:this.participant})
        this.resetParticipant()
      }
    },
    removeParticipant (index) {
      this.$store.commit('request/SPLICE_FORM', {key:'training_participants',value:index})
    },
    resetParticipant () {
      this.participant = Object.assign({}, this.participant, {
        participant: '',
        quantity: 0
      })
    }
  }
}
</script>

