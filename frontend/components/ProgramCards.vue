<template>
  <v-layout>
    <v-flex>
      <v-card>
        <v-container 
        fluid
        >
          <v-layout justify-center row wrap>
            <v-flex xs6 sm8>
              <h5 class="display-1">Choose Program</h5>
            </v-flex>
          </v-layout>

          <v-layout align-center justify-center row fill-height wrap>
            <v-flex
            v-for="(item, index) in training_programs"
            :key="item.training_program_id"
            :class="{'ma-0': $vuetify.breakpoint.smAndDown, 'ma-2': $vuetify.breakpoint.mdAndUp}"
            xs12 sm6 md4
            >
              <v-card class="elevation-5">
                <v-card-title primary-title>
                  <div class="card-title">
                    <h3 class="headline mb-0">{{ item.program_title }}</h3>
                    <div style="min-height: 50px;">{{ item.description }}</div>
                  </div>
                </v-card-title>

                <v-divider></v-divider>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn 
                  @click="showFeatures(index)"
                  color="default" 
                  small
                  >
                    CONTENT
                  </v-btn>
                  <v-btn 
                  @click="openGallery(index)" 
                  color="default" 
                  small
                  >
                    PHOTOS
                  </v-btn>
                  <v-btn 
                  @click="updateForm('training_program_ids', item.training_program_id)" 
                  :color="`${ isProgramSelected(item.training_program_id) ? 'success' : ''}`" 
                  small
                  >
                    {{ isProgramSelected(item.training_program_id) ? 'SELECTED &nbsp;' : 'SELECT' }}
                    <i v-if="isProgramSelected(item.training_program_id)" 
                    class="fa fa-check-circle"></i>
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
        <v-card-actions>
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
          @click="next"
          :disabled="!this.form.training_program_ids.length > 0 ? true : false"
          color="red darken-1" 
          flat
          >
            Proceed &nbsp;
            <v-icon small>fa fa-arrow-circle-right</v-icon>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-flex>

    <ProgramCardGallery :gallery_data="gallery_data" v-on:closeGallery="closeGallery"/>
    <ProgramFeatures :features="feature_data" v-on:hideFeatures="hideFeatures"/>
  </v-layout>
</template>

<script>
import { mapGetters, mapState } from 'vuex'
import ProgramCardGallery from './ProgramCardGallery'
import ProgramFeatures from './ProgramFeatures'

export default {
  name: 'ProgramCards',
  components: {ProgramCardGallery,ProgramFeatures},
  data () {
    return {
      gallery_data: {
        image_index: 0,
        isOpen: false
      },
      button_status: {
        color: '',
        text: '',
        status: ''
      },
      iterator: [],
      feature_data: {
        isFeatureOpen: false,
        features: []
      },
      displayToast: {
        status: false,
        text: ''
      }
    }
  },
  computed: {
    ...mapState('request', ['training_programs','form']),
    ...mapGetters('request', ['getImages','getFeatures'])
  },
  mounted () {
    this.fetchTrainingPrograms()
  },
  methods: {
    showFeatures (index) {
      this.feature_data.isFeatureOpen = true
      this.feature_data.features = this.getFeatures(index)
    },
    hideFeatures () {
      this.feature_data.isFeatureOpen = false
    },
    fetchTrainingPrograms () {
      return this.$store.dispatch('request/setTrainingPrograms')
    },
    openGallery (payload) {
      this.gallery_data = {
        image_index: payload,
        isOpen: true
      }
    },
    closeGallery () {
      this.gallery_data = {
        image_index: 0,
        isOpen: false
      }
    },
    isProgramSelected(training_program_id){
      var training_program_ids = this.form.training_program_ids;
      for(var x in training_program_ids){
        if(training_program_ids[x] === training_program_id){
          return true;
        }
      }
      return false;
    },
    getProgramIndex(training_program_id){
      var training_program_ids = this.form.training_program_ids;
      for(var x in training_program_ids){
        if(training_program_ids[x] === training_program_id){
          return x;
        }
      }
      return false;
    },
    updateForm (field, value) {
      if(this.isProgramSelected(value)){
        var index = this.getProgramIndex(value);
        this.$store.commit('request/SPLICE_FORM', {key:field,value:index});
      }
      else {
        if(this.form.training_program_ids.length >= 2){
          alert('Only two training can be selected.');
          return false;
        }
        this.$store.commit('request/PUSH_FORM', {key:field,value:value});
      }
    },
    back () {
      this.$store.commit('request/BACK_PAGE')
    },
    next () {
      this.$store.commit('request/NEXT_PAGE')
    }
  }
}
</script>
